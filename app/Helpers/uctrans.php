<?php

/**
 * Uppercase the first character of the translated string
 * Simple wrapper around the trans() function.
 *
 * @param  $string
 *
 * @return string
 */
if (!function_exists('uctrans')) {
    function uctrans($string)
    {
        return ucfirst(trans($string));
    }
}
