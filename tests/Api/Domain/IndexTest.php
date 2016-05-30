<?php

namespace tests\Api\Domain;

use Illuminate\Support\Facades\DB;
use tests\Api\IndexTestHelper;
use tests\TestCase;

class IndexTest extends TestCase
{
    use IndexTestHelper;

    const URL = '/api/d41d8cd98f00b204e8000998ecf8427e/v1/domains';

    protected function truncateTables()
    {
        DB::table('domains')->truncate();
    }
}
