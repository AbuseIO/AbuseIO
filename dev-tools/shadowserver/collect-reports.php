#!/usr/bin/env php
<?php

declare(strict_types=1);

function stderr(string $msg): void {
    fwrite(STDERR, $msg);
}

// Load .env from project root and read shadowserver_* keys
function load_env_file(string $path): array {
    $vars = [];
    if (!is_file($path)) {
        return $vars;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return $vars;
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || $line[0] === ';') {
            continue;
        }
        if (str_starts_with($line, 'export ')) {
            $line = substr($line, 7);
        }
        $pos = strpos($line, '=');
        if ($pos === false) { continue; }
        $name = trim(substr($line, 0, $pos));
        $value = trim(substr($line, $pos + 1));
        if ($value !== '' && ($value[0] === '"' || $value[0] === '\'')) {
            $quote = $value[0];
            if (str_ends_with($value, $quote)) {
                $value = substr($value, 1, -1);
            }
        }
        $vars[$name] = $value;
        // Also expose to process env for compatibility
        @putenv($name . '=' . $value);
    }
    return $vars;
}

function env_get(array $vars, string $key, ?string $default = null): ?string {
    if (array_key_exists($key, $vars)) {
        return $vars[$key];
    }
    $v = getenv($key);
    if ($v !== false) { return (string)$v; }
    return $default;
}

function api_call(string $method, array $request, array $cfg, int $timeout = 45): string {
    $url = $cfg['uri'] . $method;
    $request['apikey'] = $cfg['key'];
    $request_string = json_encode($request, JSON_UNESCAPED_SLASHES);
    if ($request_string === false) {
        throw new RuntimeException('Failed to encode JSON request');
    }
    $hmac2 = hash_hmac('sha256', $request_string, $cfg['secret']);

    $ch = curl_init($url);
    if ($ch === false) {
        throw new RuntimeException('Failed to initialize curl');
    }
    $headers = [
        'Content-Type: application/json',
        'HMAC2: ' . $hmac2,
    ];
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $request_string,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => $timeout,
        CURLOPT_TIMEOUT => $timeout,
    ]);
    $res = curl_exec($ch);
    if ($res === false) {
        $err = curl_error($ch);
        $code = curl_errno($ch);
        curl_close($ch);
        throw new RuntimeException("API request failed: [$code] $err");
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode < 200 || $httpCode >= 300) {
        // Return raw content; caller may decide how to handle
        return $res;
    }
    return $res;
}

// Add a download_url field based on 'id' for each item
function add_download_urls(array $items): array {
    foreach ($items as $i => $item) {
        if (isset($item['id']) && is_string($item['id']) && $item['id'] !== '') {
            $items[$i]['download_url'] = 'https://dl.shadowserver.org/' . $item['id'];
        }
    }
    return $items;
}

// Download a single report to ./reports/<file>
function download_report(string $url, string $filename, int $timeout = 120): bool {
    // Always write to dev-tools/shadowserver/reports
    $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'reports';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0775, true) && !is_dir($dir)) {
            stderr("Failed to create reports directory: $dir\n");
            return false;
        }
    }
    $basename = basename($filename);
    $dest = $dir . DIRECTORY_SEPARATOR . $basename;

    $fp = fopen($dest, 'wb');
    if ($fp === false) {
        stderr("Failed to open destination file: $dest\n");
        return false;
    }

    $ch = curl_init($url);
    if ($ch === false) {
        fclose($fp);
        stderr("Failed to initialize curl for download\n");
        return false;
    }
    curl_setopt_array($ch, [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CONNECTTIMEOUT => $timeout,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_FAILONERROR => true,
        // Ensure NOTHING goes to STDOUT; capture internally and write via callback
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_WRITEFUNCTION => function($ch, $data) use ($fp) {
            return fwrite($fp, $data);
        },
    ]);
    $ok = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($ok === false || $httpCode < 200 || $httpCode >= 300) {
        $err = curl_error($ch);
        curl_close($ch);
        fclose($fp);
        // Remove partial file on failure
        @unlink($dest);
        stderr("Download failed (HTTP $httpCode): $url\n" . ($err ? "Error: $err\n" : ''));
        return false;
    }
    curl_close($ch);
    fclose($fp);
    $size = @filesize($dest);
    if ($size === false || $size === 0) {
        stderr("Downloaded file is empty: $dest\n");
        @unlink($dest);
        return false;
    }
    return true;
}

// Download all reports present in items using download_url and file
function download(array $items): void {
    foreach ($items as $item) {
        $url = $item['download_url'] ?? null;
        $file = $item['file'] ?? null;
        if (!$url || !$file) { continue; }
        stderr("Downloading $file from $url\n");
        download_report($url, $file);
    }
}

function main(): int {
    // Resolve project root (/opt/abuseio) and load .env
    $root = dirname(__DIR__, 2);
    $envPath = $root . DIRECTORY_SEPARATOR . '.env';
    $envVars = load_env_file($envPath);
    // Static request based on original usage example
    $method = 'reports/list';
    $obj = [
        'reports' => ['test'],
        'limit' => 1500,
        'format' => 'json', // ensure API returns JSON structure
    ];

    // Read API config from .env using shadowserver_* keys
    $cfg = [
        'key' => (string)env_get($envVars, 'shadowserver_key', ''),
        'secret' => (string)env_get($envVars, 'shadowserver_secret', ''),
        'uri' => (string)env_get($envVars, 'shadowserver_uri', ''),
    ];
    $cfg['uri'] = rtrim($cfg['uri'], '/') . '/';
    if ($cfg['key'] === '' || $cfg['secret'] === '' || $cfg['uri'] === '/') {
        stderr("Missing Shadowserver API config. Set shadowserver_key, shadowserver_secret, shadowserver_uri in .env at $envPath\n");
        return 1;
    }

    // Execute API call
    try {
        $res = api_call($method, $obj, $cfg);
    } catch (Throwable $e) {
        stderr("API Exception: " . $e->getMessage() . "\n");
        return 1;
    }

    // Parse response and enrich
    $parsed = json_decode($res, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($parsed)) {
        // Do not leak API response (which may be CSV/text) to STDOUT.
        stderr("API response was not valid JSON; suppressing stdout.\n");
        return 1;
    }
    $enriched = add_download_urls($parsed);
    // Suppress JSON listing on STDOUT to avoid noisy output; proceed to downloads only.

    // Download each available report into ./reports/
    download($enriched);
    return 0;
}

exit(main());
