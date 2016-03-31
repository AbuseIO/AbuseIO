<?php

/**
 * Function to convert a boolean to its name in string.
 *
 * @param  $bool
 *
 * @return string
 */
function castBoolToString($bool)
{
    return $bool === true ? 'true' : 'false';
}
