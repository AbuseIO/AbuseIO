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
    if (!empty($url)) {
        // Sanitize URL first by removing unwanted chars
        $url = preg_replace("/[\n\r]/", '', $url);

        // Sanitize URL accourding to RFC1738 (perhaps use RFC3986?)
        $entities = [
            ' ',
        ];
        $replacements = [
            '%20',
        ];
        $url = str_replace($entities, $replacements, $url);

        // Check weither the domain is actually valid
        if (getDomain($url) == false) {
            return false;
        }

        $pslManager = new Pdp\PublicSuffixListManager();
        $urlParser = new Pdp\Parser($pslManager->getList());
        $urlData = $urlParser->parseUrl($url)->toArray();

        $path = $urlData['path'].(!empty($urlData['query']) ? '?'.$urlData['query'] : '');

        // Set the path to root if empty (default)
        if (empty($path)) {
            $path = '/';
        }

        // Sanitize PATH accourding to RFC1738 (perhaps use RFC3986?)
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
