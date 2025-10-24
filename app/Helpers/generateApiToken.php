<?php
use Illuminate\Support\Str;

/**
 * @return string
 */
function generateApiToken()
{
    //MISSING-ABUSEIO5
    //What is the coeect output (was _toSTring ?!
    return Str::uuid()->toString();
}
