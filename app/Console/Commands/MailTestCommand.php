<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailTestCommand extends Command
{
    protected $signature = 'mail:test {--to=} {--subject=SMTP CN/FQDN test} {--message=This is a test email from mail:test}';
    protected $description = 'Send a test email and validate SMTP certificate CN and system FQDN/hosts configuration.';

    public function handle(): int
    {
        $this->info('Running mail/test diagnostics...');

        // 1) Read SMTP config
        $smtp = Config::get('mail.mailers.smtp', []);
        $host = $smtp['host'] ?? env('MAIL_HOST');
        $port = (int)($smtp['port'] ?? env('MAIL_PORT', 25));
        $encryption = $smtp['encryption'] ?? env('MAIL_ENCRYPTION');

        $this->line("SMTP host: {$host}");
        $this->line("SMTP port: {$port}");
        $this->line('SMTP encryption: '.($encryption ?? 'null'));

        // 2) Connect to SMTP and inspect certificate CN/SAN via TLS (supports STARTTLS on port 25)
        $certInfo = null;
        $expectedHost = $host;
        try {
            $context = stream_context_create([
                'ssl' => [
                    'capture_peer_cert' => true,
                    'SNI_enabled' => true,
                    'peer_name' => $expectedHost,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $transport = ($encryption === 'ssl') ? 'ssl' : 'tcp';
            $socket = stream_socket_client("{$transport}://{$host}:{$port}", $errno, $errstr, 8, STREAM_CLIENT_CONNECT, $context);
            if ($socket === false) {
                throw new \RuntimeException("SMTP connect failed: {$errstr} ({$errno})");
            }

            // If we connected with TCP (no implicit TLS), attempt SMTP STARTTLS upgrade.
            if ($transport === 'tcp') {
                stream_set_timeout($socket, 5);
                $read = function () use ($socket) {
                    $resp = '';
                    while (($line = fgets($socket)) !== false) {
                        $resp .= $line;
                        // Response lines end when status code + space appears (not '-')
                        if (preg_match('/^\d{3} /', $line)) {
                            break;
                        }
                        // Avoid long waits
                        $meta = stream_get_meta_data($socket);
                        if ($meta['timed_out']) break;
                    }
                    return trim($resp);
                };

                $write = function ($cmd) use ($socket) {
                    fwrite($socket, $cmd."\r\n");
                };

                // Read server greeting
                $greet = $read();
                // EHLO with local hostname
                $heloHost = gethostname() ?: 'localhost';
                $write("EHLO {$heloHost}");
                $ehlo = $read();
                // Request STARTTLS
                $write('STARTTLS');
                $starttls = $read();
                if (!preg_match('/^220\b/', $starttls)) {
                    // Fall back to HELO STARTTLS for some servers (rare), otherwise cannot inspect cert
                    $write("HELO {$heloHost}");
                    $helo = $read();
                    $write('STARTTLS');
                    $starttls = $read();
                }

                if (!preg_match('/^220\b/', $starttls)) {
                    $this->warn('Server did not accept STARTTLS; cannot inspect certificate over plain SMTP.');
                } else {
                    // Enable TLS and capture certificate
                    stream_context_set_option($socket, 'ssl', 'capture_peer_cert', true);
                    stream_context_set_option($socket, 'ssl', 'peer_name', $expectedHost);
                    stream_context_set_option($socket, 'ssl', 'SNI_enabled', true);
                    stream_context_set_option($socket, 'ssl', 'verify_peer', false);
                    stream_context_set_option($socket, 'ssl', 'verify_peer_name', false);

                    $cryptoOk = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                    if ($cryptoOk) {
                        $params = stream_context_get_params($socket);
                        if (isset($params['options']['ssl']['peer_certificate'])) {
                            $certRes = $params['options']['ssl']['peer_certificate'];
                            $certInfo = openssl_x509_parse($certRes);
                        }
                    } else {
                        $this->warn('Failed to start TLS; certificate inspection unavailable.');
                    }
                }
            } else {
                // Implicit TLS: certificate should be available immediately
                $params = stream_context_get_params($socket);
                if (isset($params['options']['ssl']['peer_certificate'])) {
                    $certRes = $params['options']['ssl']['peer_certificate'];
                    $certInfo = openssl_x509_parse($certRes);
                }
            }

            fclose($socket);
        } catch (\Throwable $e) {
            $this->error('Failed to fetch SMTP certificate: '.$e->getMessage());
        }

        if ($certInfo) {
            $cn = $certInfo['subject']['CN'] ?? null;
            $sans = [];
            if (isset($certInfo['extensions']['subjectAltName'])) {
                $sansStr = $certInfo['extensions']['subjectAltName'];
                foreach (explode(', ', $sansStr) as $entry) {
                    if (str_starts_with($entry, 'DNS:')) {
                        $sans[] = substr($entry, 4);
                    }
                }
            }
            $this->info('SMTP certificate CN: '.($cn ?? 'unknown'));
            if (!empty($sans)) {
                $this->line('SMTP certificate SANs: '.implode(', ', $sans));
            }

            $matches = ($cn === $expectedHost) || in_array($expectedHost, $sans, true);
            if (!$matches) {
                $this->warn("Hostname mismatch: expected '{$expectedHost}', certificate CN is '{$cn}'.");
                $suggest = $cn ?: (reset($sans) ?: '');
                if (!empty($suggest)) {
                    $this->warn("Hint: set MAIL_HOST={$suggest} to match the certificate.");
                }
            } else {
                $this->info('Hostname matches certificate CN/SAN.');
            }
        } else {
            $this->warn('Could not retrieve SMTP certificate details (server may not present TLS on connect).');
        }

        // 3) FQDN checks
        $fqdn = trim(shell_exec('hostname --fqdn 2>/dev/null') ?? '');
        if ($fqdn === '') {
            $this->error('hostname --fqdn is empty or failed. Configure system FQDN.');
        } else {
            $this->info('System FQDN: '.$fqdn);
            // Check /etc/hosts mapping for 127.0.1.1
            $hosts = @file('/etc/hosts', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
            $mappingOk = false;
            foreach ($hosts as $line) {
                $line = trim(preg_replace('/#.*/', '', $line));
                if ($line === '') continue;
                $parts = preg_split('/\s+/', $line);
                if (count($parts) >= 2 && $parts[0] === '127.0.1.1') {
                    $names = array_slice($parts, 1);
                    if (in_array($fqdn, $names, true) && in_array(explode('.', $fqdn)[0], $names, true)) {
                        $mappingOk = true;
                        break;
                    }
                }
            }
            if (!$mappingOk) {
                $short = explode('.', $fqdn)[0];
                $this->warn('Invalid /etc/hosts 127.0.1.1 mapping.');
                $this->line("Example correct entry: 127.0.1.1 {$short} {$fqdn}");
            } else {
                $this->info('/etc/hosts mapping for 127.0.1.1 looks correct.');
            }
        }

        // 4) Optional: send a test email
        $to = $this->option('to') ?: (Config::get('main.emailparser.fallback_mail') ?? Config::get('mail.from.address'));
        $from = Config::get('main.notifications.from_address') ?? Config::get('mail.from.address') ?? 'no-reply@'.($fqdn ?: 'localhost');
        try {
            Mail::raw($this->option('message'), function ($mail) use ($to, $from) {
                $mail->from($from, 'MailTest');
                $mail->to($to);
                $mail->subject($this->option('subject'));
            });
            $this->info("Queued test email to {$to} using mailer: ".Config::get('mail.default'));
        } catch (\Throwable $e) {
            $this->error('Failed to send test email: '.$e->getMessage());
        }

        return 0;
    }
}