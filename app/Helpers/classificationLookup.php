<?php

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

/**
 * Resolve a classification key by canonical name or alias.
 *
 * - Accepts a string that may be a canonical classification key or an alias.
 * - Returns the canonical classification key if found.
 * - If not found, logs an error and returns null.
 *
 * Examples:
 *   classificationLookup('PHISHING_WEBSITE') => 'PHISHING_WEBSITE'
 *   classificationLookup('PHISING_WEBSITE')  => 'PHISHING_WEBSITE'
 *
 * @param string|null $keyOrAlias
 * @return string|null
 */
function classificationLookup(?string $keyOrAlias): ?string
{
    if ($keyOrAlias === null || $keyOrAlias === '') {
        return null;
    }

    $translations = (array) Lang::get('classifications');

    // Direct match by canonical key
    if (array_key_exists($keyOrAlias, $translations)) {
        return $keyOrAlias;
    }

    // Match by alias list in translation entries
    foreach ($translations as $canonical => $info) {
        if (isset($info['aliases']) && is_array($info['aliases'])) {
            if (in_array($keyOrAlias, $info['aliases'], true)) {
                return $canonical;
            }
        }
    }

    Log::error("classificationLookup: Unknown classification '{$keyOrAlias}' (no canonical key or alias match)");
    return null;
}