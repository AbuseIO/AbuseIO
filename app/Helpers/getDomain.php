<?php

/**
 * Validate a domain string without relying on external libraries.
 *
 * Rules based on tests:
 * - No scheme (e.g., http://, https://, ftp://)
 * - No path or query (no '/' and no '?')
 * - Allow sanitizing CR/LF embedded in structured text
 * - Must be a valid hostname with at least one dot and TLD length >= 2
 *
 * @param string $domain
 *
 * @return bool
 */
function getDomain($domain)
{
    if (empty($domain) || !is_string($domain)) {
        return false;
    }

    // Remove carriage returns/newlines that may appear in structured text
    $domain = preg_replace("/[\n\r]/", '', $domain);

    // RFC1738 style replacement used previously (retain behavior for spaces)
    $domain = str_replace(' ', '%20', $domain);

    // Reject if scheme is present
    if (strpos($domain, '://') !== false) {
        return false;
    }

    // Reject if path or query present
    if (strpos($domain, '/') !== false || strpos($domain, '?') !== false) {
        return false;
    }

    // Basic domain regex:
    // - total length up to 253
    // - labels 1-63 chars: alnum or hyphen, no leading/trailing hyphen
    // - at least one dot
    // - TLD alpha, length >= 2
    $pattern = '/^(?=.{1,253}$)(?:[A-Za-z0-9](?:[A-Za-z0-9-]{0,61}[A-Za-z0-9])?\.)+[A-Za-z]{2,}$/';

    return (bool) preg_match($pattern, $domain);
}
