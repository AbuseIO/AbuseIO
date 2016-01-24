<?php

/**
 * Function to test a regular expression for it's validity.
 *
 * @param $bool
 * @return string
 */
function isValidRegex($regex)
{
    return (@preg_match($regex, null) !== false);
}
