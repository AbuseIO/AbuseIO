<?php

/**
 * This helper function can be used to get a valid uri from an url and return it.
 *
 * @param string $url
 *
 * @return mixed
 */
function getUri($url)
{
    if (!empty($url) && $urlData = getUrlData($url)) {
        $url = preg_replace("/[\n\r]/", '', $url);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check weither the domain is actually valid
        if (getDomain($urlData['host']) == false) {
            return false;
        }

        $path = $urlData['path'].($urlData['query'] ? '?'.$urlData['query'] : '');

        // Sanitize PATH according to RFC1738 (perhaps use RFC3986?)
        $entities = [
            ' ',
        ];
        $replacements = [
            '%20',
        ];
        $path = str_replace($entities, $replacements, $path);

        return $path;
    } else {
        return false;
    }
}
