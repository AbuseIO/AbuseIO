<?php

/**
 * This helper function can be used to get a valid uri from an url and return it.
 *
 * @param  string $url
 * @return mixed
 */
function getUri($url)
{
    if (!empty($url)) {
        // Sanitize URL first by removing unwanted chars
        $url = preg_replace("/[\n\r]/", "", $url);

        // Check weither the domain is actually valid
        if (getDomain($url) == false) {
            return false;
        }

        $pslManager = new Pdp\PublicSuffixListManager();
        $urlParser = new Pdp\Parser($pslManager->getList());
        $urlData = $urlParser->parseUrl($url)->toArray();

        $path = $urlData['path'] . (!empty($urlData['query']) ? '?'. $urlData['query'] : '');

        // Set the path to root if empty (default)
        if (empty($path)) {
            $path = '/';
        }

        return $path;

    } else {
        return false;
    }
}
