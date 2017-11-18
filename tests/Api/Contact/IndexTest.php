<?php

namespace tests\Api\Contact;

use Illuminate\Support\Facades\DB;
use tests\Api\IndexTestHelper;
use tests\TestCase;

class IndexTest extends TestCase
{
    use IndexTestHelper;

    const URL = '/api/v1/contacts';

    protected function truncateTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('contacts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
