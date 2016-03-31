<?php

namespace tests\Models;

use AbuseIO\Models\Domain;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class DomainTest extends TestCase
{
    use DatabaseTransactions;

    public function testModelFactory()
    {
        $domain = factory(Domain::class)->create();
        $domainFromDB = Domain::where('name', $domain->name)->first();
        $this->assertEquals($domain->name, $domainFromDB->name);
    }
}
