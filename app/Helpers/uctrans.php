<?php

/**
 * Translates the given message based on a count and Uppercases the first character.
 *
 * @param  string  $id
 * @param  int     $number
 * @param  array   $parameters
 * @param  string  $domain
 * @param  string  $locale
 * @return string
 */
if (!function_exists('uctrans')) {
    function uctrans($id, $number = 1, array $parameters = [], $domain = 'messages', $locale = null)
    {
        return ucfirst(app('translator')->transChoice($id, $number, $parameters, $domain, $locale));
    }
}
