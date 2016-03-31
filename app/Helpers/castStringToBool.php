<?php

/**
 * Function to convert a string to its name in boolean.
 *
 * @param string $str
 *
 * @return bool
 */
function castStringToBool($str)
{
    return $str === 'true' ? true : false;
}
