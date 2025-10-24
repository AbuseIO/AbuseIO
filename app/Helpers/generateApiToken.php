<?php

/**
 * @return string|\Webpatser\Uuid\Uuid
 */
function generateApiToken()
{
    //MISSING-ABUSEIO5
    //What is the coeect output (was _toSTring ?!
    return Str::fastUuid();
}
