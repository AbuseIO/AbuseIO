<?php

namespace tests\Api\Netblock;

use Illuminate\Support\Facades\DB;
use tests\Api\IndexTestHelper;
use tests\TestCase;

class IndexTest extends TestCase
{
    use IndexTestHelper;

    const URL = '/api/d41d8cd98f00b204e8000998ecf8427e/v1/netblocks';

    protected function truncateTables()
    {
        DB::table('netblocks')->truncate();
    }
}
