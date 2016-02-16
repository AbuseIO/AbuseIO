<?php

/**
 * This helper function can be used to get a valid uri from an url and return it.
 *
 * @param  string $str
 * @return mixed
 */
function getUri($url)
{
    if (!empty($url)) {
        $pslManager = new Pdp\PublicSuffixListManager();
        $urlParser = new Pdp\Parser($pslManager->getList());
        $urlData = $urlParser->parseUrl($url)->toArray();

        $uri = $urlData['path'] .'?'. $urlData['query'];

        // If no path or query given, return false
        return ($uri == '/?') ? false : $uri;
    } else {
        return false;
    }
}
