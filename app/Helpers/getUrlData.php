<?php

/**
 * This helper function can be used to get the url data.
 *
 * @param $url
 *
 * @return mixed
 *
 * @internal param string $str
 */
function getUrlData($url)
{
    if (!empty($url)) {
        // Sanitize URL first by removing unwanted chars
        $url = preg_replace("/[\n\r]/", '', $url);

        // Sanitize URL according to RFC1738 (perhaps use RFC3986?)
        $entities = [
            ' ',
        ];
        $replacements = [
            '%20',
        ];

        $url = str_replace($entities, $replacements, $url);

        return array_merge(
            [
                'host'  => '',
                'path'  => '/', // set the default path to root;
                'query' => '', // set default query_string to empty string;
            ],
            parse_url($url)
        );
    }
}
