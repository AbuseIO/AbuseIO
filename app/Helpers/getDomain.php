<?php

/**
 * This helper function can be used to get a valid domain.tld from an url and return it.
 *
 * @param string $url
 *
 * @return mixed
 */
function getDomain($url)
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

        // Check weither the URL is actually valid
        if (!filter_var($url, FILTER_VALIDATE_URL) === true) {
            return false;
        }

        $pslManager = new Pdp\PublicSuffixListManager();
        $urlParser = new Pdp\Parser($pslManager->getList());
        $urlData = $urlParser->parseUrl($url)->toArray();

        if ($urlParser->isSuffixValid($urlData['registerableDomain']) === false) {
            // Not a valid domain.
            return false;
        } else {
            // Return valid domain
            return $urlData['registerableDomain'];
        }
    } else {
        return false;
    }
}
