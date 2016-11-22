<?php

/**
 * @return string|\Webpatser\Uuid\Uuid
 */
function generateApiToken()
{
    return Uuid::generate(4)->__toString();
}
