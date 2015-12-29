<?php

/**
 * Function to covert a boolean to its name in string
 *
 * @param $bool
 * @return string
 */
function castBoolToString($bool)
{
    return $bool === true ? "true": "false";
}
