<?php

/**
 * This helper function can be used to get the url data.
 *
 * @param  string $str
 * @return mixed
 */
function getUrlData($url)
{
    if (!empty($url)) {
        $pslManager = new Pdp\PublicSuffixListManager();
        $urlParser = new Pdp\Parser($pslManager->getList());
        $urlData = $urlParser->parseUrl($url)->toArray();

        return $urlData;
    }
}
