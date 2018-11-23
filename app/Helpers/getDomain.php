<?php

/**
 * This helper function can be used to get a valid domain.tld from an url and return it.
 *
 * @param string $domain
 *
 * @return mixed
 */
function getDomain($domain)
{
    if (!empty($domain) && is_string($domain)) {
        // Sanitize domain first by removing unwanted chars
        $domain = preg_replace("/[\n\r]/", '', $domain);

        // Sanitize URL according to RFC1738 (perhaps use RFC3986?)
        $entities = [
            ' ',
        ];
        $replacements = [
            '%20',
        ];
        $domain = str_replace($entities, $replacements, $domain);

        $manager = new Pdp\Manager(new Pdp\Cache(), new Pdp\CurlHttpClient());
        $rules = $manager->getRules(); //$rules is a Pdp\Rules object
        $resolvedDomain = $rules->resolve($domain);

        return $resolvedDomain->isKnown();
    } else {
        return false;
    }
}
