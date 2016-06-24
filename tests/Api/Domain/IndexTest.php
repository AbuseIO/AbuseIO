<?php

namespace tests\Api\Domain;

use Illuminate\Support\Facades\DB;
use tests\Api\IndexTestHelper;
use tests\TestCase;

class IndexTest extends TestCase
{
    use IndexTestHelper;

    const URL = '/api/v1/domains';

    protected function truncateTables()
    {
        DB::table('domains')->truncate();
    }
}
