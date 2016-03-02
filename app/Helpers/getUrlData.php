<?php

/**
 * This helper function can be used to get the url data.
 *
 * @param $url
 * @return mixed
 * @internal param string $str
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
