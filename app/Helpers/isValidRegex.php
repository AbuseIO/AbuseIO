<?php

/**
 * Function to test a regular expression for it's validity.
 *
 * @param string $regex
 *
 * @return bool
 */
function isValidRegex($regex)
{
    return @preg_match($regex, null) !== false;
}
