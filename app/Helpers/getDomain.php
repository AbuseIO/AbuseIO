<?php

/**
 * This helper function can be used to get a valid domain.tld from an url and return it.
 *
 * @param  string $str
 * @return mixed
 */
function getDomain($url)
{
    if (!empty($url)) {
        $pslManager = new Pdp\PublicSuffixListManager();
        $urlParser = new Pdp\Parser($pslManager->getList());
        $urlData = $urlParser->parseUrl($url)->toArray();

        if ($urlParser->isSuffixValid($urlData['registerableDomain']) === false) {
            // No need to continue, domain is invalid
            return false;
        } else {
            // Return valid domain
            return $urlData['registerableDomain'];
        }
    } else {
        return false;
    }
}
