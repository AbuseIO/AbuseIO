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
    return null !== $regex && is_string($regex) && @preg_match($regex, null) !== false;
}
