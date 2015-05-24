<?php namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use PhpMimeMailParser\Attachment;
use PhpMimeMailParser\Parser;
use Config;
use Log;

class EmailParseCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'email:parse';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Parses an incoming email into abuse events.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        // Read from stdin (should be piped from cat or MDA)
        $fd = fopen("php://stdin", "r");
        $raw_email = "";
        while (!feof($fd)) {
            $raw_email .= fread($fd, 1024);
        }
        fclose($fd);

        // Actually parse it
        $parsed_mail = new Parser();
        $parsed_mail->setText($raw_email);

        // Ignore email from our own notification address to prevent mail loops
        // TODO: add

        // Store the raw e-mail into a EML file if the archiving is enabled
        if(Config::get('main.emailparser.store_evidence') === true) {
            $filesystem = new Filesystem;

            $path = storage_path() . '/mailarchive/' . date('Ymd') . '/';
            if (!$filesystem->isDirectory($path)) {
                if(!$filesystem->makeDirectory($path)) {
                    Log::error('Unable to create directory' . $path);
                    $this->exception($raw_email);
                }
            }

            if(empty($parsed_mail->parts[1]['headers']['message-id'])) {
                $file = rand(10,10) . '.eml';
            } else {
                $file = substr($parsed_mail->parts[1]['headers']['message-id'],1,-1) . '.eml';
                $file = preg_replace('/[^a-zA-Z0-9_\.]/', '_', $file);
            }

            if($filesystem->isFile($path . $file)) {
                // No way an e-mail with the same message ID would arrive twice. So this is an error to rage quit for
                Log::error('Received duplicate e-mail with message ID: ' . $parsed_mail->parts[1]['headers']['message-id']);
                $this->exception($raw_email);
            }

            if ( $filesystem->put($path . $file, $raw_email) === false ) {
                Log::error('Unable to write file: ' . $path . $file);
                $this->exception($raw_email);
            }

        }

        // Start with detecting valid ARF e-mail
        $attachments = $parsed_mail->getAttachments();
        $arf = [ ];
        foreach ($attachments as $attachment) {
            if($attachment->contentType == 'message/feedback-report') {
                $arf['report'] = $attachment->getContent();
            }
            if($attachment->contentType == 'message/rfc822') {
                $arf['evidence'] = $attachment->getContent();
            }
            if($attachment->contentType == 'text/plain') {
                $arf['message'] = $attachment->getContent();
            }
        }

        // Use parser mapping to see where to kick it to
        // Start using the quick method using the sender mapping
        $sender_map = Config::get('main.emailparser.sender_map');
        foreach ($sender_map as $regex => $mapping) {
            if (preg_match($regex, $parsed_mail->getHeader('from'))) {
                $parser = $mapping;
                break;
            }
        }

        // If the quick method didnt work fall back to body mapping
        if (empty($parsed_mail)) {
            $body_map = Config::get('main.emailparser.body_map');
            foreach ($bodyMap as $regex => $mapping) {
                if (preg_match($regex, $parsed_mail->getMessageBody())) {
                    $parser = $mapping;
                    break;
                }
            }
        }

        // If we haven't figured out which parser we're going to use, we will never find out so another rage quit
        if (empty($parser)) {
            Log::error('Unable to handle message from: ' . $parsed_mail->getHeader('from') . ' with subject: ' . $parsed_mail->getHeader('subject'));
            $this->exception($raw_email);
        }

        // Kick of the parser
        // Handle parse results

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [ ];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
            // TODO: add debug option
			//['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

    /**
     * We've hit a snag, so we are gracefully killing ourselves after we contact the admin about it.
     *
     * @return mixed
     */
    protected function exception($raw_email)
    {
        // TODO: sent the rawEmail back to admin
        dd();
    }

}
