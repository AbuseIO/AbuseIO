<?php

/**
 * Uppercase the first character of the translated string
 * Simple wrapper around the trans_choice() function.
 *
 * @param $string
 * @param $count
 *
 * @return string
 */
if (!function_exists('uctrans')) {
    function uctrans($string, $count = 1)
    {
        return ucfirst(trans_choice($string, $count));
    }
}
