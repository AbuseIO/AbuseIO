<?php

namespace tests\Api\Ticket;

use Illuminate\Support\Facades\DB;
use tests\Api\IndexTestHelper;
use tests\TestCase;

class IndexTest extends TestCase
{
    use IndexTestHelper;

    const URL = '/api/v1/tickets';

    protected function truncateTables()
    {
        DB::table('tickets')->truncate();
    }
}
