<?php

/**
 * @return string|\Webpatser\Uuid\Uuid
 */
function generateApiToken()
{
    return Uuid::generate(4);

    return md5(generateGuid());
}
