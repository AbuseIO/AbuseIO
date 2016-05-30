<?php

namespace tests\Api\User;

use tests\Api\IndexTestHelper;
use tests\TestCase;

class IndexTest extends TestCase
{
    use IndexTestHelper;

    const URL = '/api/d41d8cd98f00b204e8000998ecf8427e/v1/users';

    // can't test for empty users api because we have no user to authenticate with.
}
