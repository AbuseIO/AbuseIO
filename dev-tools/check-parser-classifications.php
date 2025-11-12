#!/usr/bin/env php
<?php

declare(strict_types=1);

error_reporting(E_ALL);

function stderr(string $msg): void
{
    fwrite(STDERR, $msg);
}

// Resolve project root (/opt/abuseio) from dev-tools
$root = realpath(__DIR__.'/..');
if ($root === false) {
    stderr("Failed to resolve project root from dev-tools\n");
    exit(1);
}

// Load English classifications definitions (canonical keys + aliases)
$classFile = $root.'/resources/lang/en/classifications.php';
if (!is_file($classFile)) {
    stderr("Missing classifications file: $classFile\n");
    exit(1);
}

$definitions = require $classFile;
if (!is_array($definitions)) {
    stderr("Classifications file did not return an array\n");
    exit(1);
}

$defined = [];
foreach ($definitions as $canonKey => $entry) {
    $canon = strtoupper(trim((string) $canonKey));
    if ($canon !== '') {
        $defined[$canon] = $canon; // map to itself
    }
    if (is_array($entry) && isset($entry['aliases']) && is_array($entry['aliases'])) {
        foreach ($entry['aliases'] as $alias) {
            $a = strtoupper(trim((string) $alias));
            if ($a !== '') {
                $defined[$a] = $canon; // alias maps to canonical
            }
        }
    }
}

// Scan parser configs: vendor/abuseio/parser-*/config/*.php
$pattern = $root.'/vendor/abuseio/parser-*/config/*.php';
$files = glob($pattern, GLOB_NOSORT);
if ($files === false) {
    $files = [];
}

$usedClasses = []; // class => list of occurrences
$feedCount = 0;
$parserCount = [];

foreach ($files as $file) {
    // Derive parser name from path segment parser-<name> without regex
    $parser = 'unknown';
    $segments = preg_split('~[\\/]+~', $file);
    foreach ($segments as $seg) {
        if (str_starts_with($seg, 'parser-')) {
            $parser = substr($seg, 7);
            break;
        }
    }
    $cfg = require $file;
    if (!is_array($cfg)) {
        continue;
    }
    $feeds = $cfg['feeds'] ?? null;
    if (!is_array($feeds)) {
        continue;
    }
    $parserCount[$parser] = true;
    foreach ($feeds as $feedName => $feedCfg) {
        $feedCount++;
        if (!is_array($feedCfg)) {
            continue;
        }
        $class = $feedCfg['class'] ?? null;
        if (!is_string($class) || $class === '') {
            continue;
        }
        $norm = strtoupper(trim($class));
        if ($norm === '') {
            continue;
        }
        $usedClasses[$norm][] = [
            'parser'  => $parser,
            'feed'    => (string) $feedName,
            'file'    => substr($file, strlen($root) + 1),
            'enabled' => $feedCfg['enabled'] ?? null,
        ];
    }
}

$uniqueUsed = array_keys($usedClasses);
sort($uniqueUsed);

// Compute missing classifications (not in canonical or aliases)
$missing = [];
foreach ($uniqueUsed as $class) {
    if (!array_key_exists($class, $defined)) {
        $missing[$class] = $usedClasses[$class];
    }
}

// Output
$numParsers = count($parserCount);
echo "Parser classification audit\n";
echo "- Project root: $root\n";
echo '- Parser config files: '.count($files)."\n";
echo "- Parsers found: $numParsers\n";
echo "- Feeds scanned: $feedCount\n";
echo '- Unique classes used: '.count($uniqueUsed)."\n";
echo '- Classes defined (including aliases): '.count($defined)."\n";
echo '- Missing class definitions: '.count($missing)."\n\n";

if (!empty($missing)) {
    echo "Missing classifications (used by parsers but not defined in resources/lang/en/classifications.php):\n";
    foreach ($missing as $class => $occurrences) {
        echo "  - $class (".count($occurrences)." occurrences)\n";
        // Show up to 5 occurrences for quick context
        $shown = 0;
        foreach ($occurrences as $occ) {
            echo '      parser='.$occ['parser'].', feed='.$occ['feed'].', file='.$occ['file']."\n";
            $shown++;
            if ($shown >= 5) {
                break;
            }
        }
    }
} else {
    echo "All parser classifications are defined (considering aliases).\n";
}

exit(0);
