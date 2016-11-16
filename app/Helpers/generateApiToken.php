<?php

function generateApiToken()
{
    return md5(generateGuid());
}
