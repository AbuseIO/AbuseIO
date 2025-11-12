<?php

// Script: generate-eml.php
// Purpose: Generate Shadowserver-like sample EMLs from CSV files in reports/.
// Location: dev-tools/shadowserver/
// Usage: php generate-eml.php [--out dir] [--dry-run]
// - Reads CSV files under dev-tools/shadowserver/reports.
// - Produces EML files that mirror extra/notifier-samples/shadowserver format.
// - Attachment filename: "<date>-<feed>-sample-<scope>.csv.zip" where scope is asn/ip.
// - Subject: "[SAMPLE] Shadowserver <feed>-sample Report: <date>".

declare(strict_types=1);

function stderr(string $msg): void
{
    fwrite(STDERR, $msg."\n");
}

function base64_zip_csv(string $csvPath): string
{
    // We need an in-memory ZIP containing the CSV file named according to samples.
    // Use ZipArchive to create a temporary file then base64 encode its contents.
    $zip = new ZipArchive();
    $tmpZip = tempnam(sys_get_temp_dir(), 'sszip_');
    if ($tmpZip === false) {
        throw new RuntimeException('Failed to create temp file for zip');
    }
    if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new RuntimeException('Failed to open zip at '.$tmpZip);
    }

    // The file name inside the zip should be just the attachment filename without .eml.
    // We'll pass the desired internal name from the caller for correctness.
    return [$zip, $tmpZip];
}

function create_eml(
    string $date,
    string $feed,
    string $scope,
    string $csvPath,
    string $outDir,
    bool $dryRun
): string {
    // Build headers
    $boundaryId = bin2hex(random_bytes(16));
    $boundary = 'b1_'.$boundaryId;
    $messageId = '<'.$boundaryId.'@reports.shadowserver.org>';
    $from = 'Root User <autoreports@shadowserver.org>';
    $subject = sprintf('[SAMPLE] Shadowserver %s-sample Report: %s', $feed, $date);
    $attachmentName = sprintf('%s-%s-sample-%s.csv.zip', $date, $feed, $scope);

    // Create a zip containing the CSV file with the internal filename matching the attachment name but without .eml
    $zip = new ZipArchive();
    $tmpZip = tempnam(sys_get_temp_dir(), 'sszip_');
    if ($tmpZip === false) {
        throw new RuntimeException('Failed to create temp file for zip');
    }
    if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new RuntimeException('Failed to open zip at '.$tmpZip);
    }
    // Name inside zip should be "<date>-<feed>-sample-<scope>.csv"
    $internalCsvName = sprintf('%s-%s-sample-%s.csv', $date, $feed, $scope);
    $csvContent = file_get_contents($csvPath);
    if ($csvContent === false) {
        throw new RuntimeException('Failed to read CSV: '.$csvPath);
    }
    $zip->addFromString($internalCsvName, $csvContent);
    $zip->close();
    $zipBytes = file_get_contents($tmpZip);
    if ($zipBytes === false) {
        throw new RuntimeException('Failed to read temp zip: '.$tmpZip);
    }
    $attachmentBase64 = rtrim(chunk_split(base64_encode($zipBytes)), "\n");
    @unlink($tmpZip);

    $lines = [];
    $lines[] = sprintf('Date: %s', date('D, d M Y H:i:s O', strtotime($date.' 20:28:33 +0100')));
    $lines[] = sprintf('From: %s', $from);
    $lines[] = sprintf('Message-ID: %s', $messageId);
    $lines[] = 'X-Priority: 3';
    $lines[] = 'X-Mailer: PHPMailer 5.2.9 (https://github.com/PHPMailer/PHPMailer/)';
    $lines[] = 'MIME-Version: 1.0';
    $lines[] = 'Content-Type: multipart/mixed;';
    $lines[] = sprintf("\tboundary=\"%s\"", $boundary);
    $lines[] = 'Content-Transfer-Encoding: 8bit';
    $lines[] = 'To: abuse@isp.tld';
    $lines[] = sprintf('Subject: %s', $subject);
    $lines[] = '';
    $lines[] = sprintf('--%s', $boundary);
    $lines[] = 'Content-Type: text/plain; charset=us-ascii';
    $lines[] = '';
    $lines[] = 'The report content can be obtained from the following link:';
    $lines[] = 'http://dl.shadowserver.org/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    $lines[] = 'The report is xxxx bytes and contains xxxx events.';
    $lines[] = 'For more information on this report go to: https://www.shadowserver.org/wiki/pmwiki.php/Services/xxxxxxxxxxxxxx';
    $lines[] = '';
    $lines[] = '';
    $lines[] = sprintf('--%s', $boundary);
    $lines[] = sprintf('Content-Type: application/octet-stream; name="%s"', $attachmentName);
    $lines[] = 'Content-Transfer-Encoding: base64';
    $lines[] = sprintf('Content-Disposition: attachment; filename=%s', $attachmentName);
    $lines[] = '';
    $lines[] = $attachmentBase64;
    $lines[] = '';
    $lines[] = '';
    $lines[] = sprintf('--%s--', $boundary);

    $emlContent = implode("\n", $lines)."\n";
    $outFile = rtrim($outDir, '/');
    $outFile .= '/'.sprintf('%s-%s-sample-%s.csv.zip.eml', $date, $feed, $scope);

    if ($dryRun) {
        return $emlContent; // Caller can echo for preview
    }
    if (!is_dir($outDir)) {
        if (!mkdir($outDir, 0775, true) && !is_dir($outDir)) {
            throw new RuntimeException('Failed to create output directory: '.$outDir);
        }
    }
    if (file_put_contents($outFile, $emlContent) === false) {
        throw new RuntimeException('Failed to write EML: '.$outFile);
    }

    return $outFile;
}

function detect_scope_from_csv(string $csvPath): string
{
    // Heuristic: if CSV header contains column with 'asn' then use 'asn',
    // else use 'ip'. Many samples are -asn, but AFP example is -ip.
    $fh = fopen($csvPath, 'r');
    if ($fh === false) {
        return 'asn';
    }
    $header = fgetcsv($fh);
    fclose($fh);
    if (is_array($header)) {
        $lower = array_map('strtolower', $header);
        foreach ($lower as $col) {
            if ($col === 'asn' || str_contains($col, 'asn')) {
                return 'asn';
            }
        }
    }

    return 'ip';
}

// Main
[$scriptDir] = [dirname(__FILE__)];
$reportsDir = $scriptDir.'/reports';
$outDir = $scriptDir.'/generated';
$dryRun = false;
foreach ($argv as $arg) {
    if ($arg === '--dry-run') {
        $dryRun = true;
    } elseif (str_starts_with($arg, '--out=')) {
        $outDir = substr($arg, 6);
    }
}

if (!is_dir($reportsDir)) {
    stderr('Reports directory not found: '.$reportsDir);
    exit(1);
}

$files = scandir($reportsDir);
if ($files === false) {
    stderr('Failed to read reports directory: '.$reportsDir);
    exit(1);
}

$count = 0;
foreach ($files as $file) {
    if (!preg_match('/^(\d{4}-\d{2}-\d{2})-(.+)-test\.csv$/', $file, $m)) {
        continue;
    }
    $date = $m[1];
    $feed = $m[2]; // e.g., scan_http, malware_url, etc.
    $csvPath = $reportsDir.'/'.$file;
    $scope = detect_scope_from_csv($csvPath);

    try {
        $result = create_eml($date, $feed, $scope, $csvPath, $outDir, $dryRun);
        $count++;
        if ($dryRun) {
            echo $result;
        } else {
            echo 'Generated: '.$result."\n";
        }
    } catch (Throwable $e) {
        stderr('Error for '.$file.': '.$e->getMessage());
    }
}

if ($count === 0) {
    stderr('No CSV report files matched the expected pattern in '.$reportsDir);
}
