<?php

namespace tests\Api\Brand;

use Illuminate\Support\Facades\DB;
use tests\Api\IndexTestHelper;
use tests\TestCase;

class IndexTest extends TestCase
{
    use IndexTestHelper;

    const URL = '/api/v1/brands';

    protected function truncateTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('accounts')->truncate();
        DB::table('brands')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
