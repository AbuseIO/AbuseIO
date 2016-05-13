<?php

namespace tests\Api\User;

use tests\Api\IndexTestHelper;
use tests\TestCase;

class IndexTest extends TestCase
{
    use IndexTestHelper;

    const URL = '/api/v1/users';

    // can't test for empty users api because we have no user to authenticate with.
}
