<?php

namespace tests\Console\Commands\Evidence;

use AbuseIO\Models\Evidence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The list of testing fixtures to test against.
     *
     * @var Illuminate\Database\Eloquent\Collection
     */
    private $evidenceList;

    private function initDB()
    {
        $this->evidenceList = factory(Evidence::class, 10)->create();
    }

    public function testWithValidIdFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'evidence:show',
            [
                'evidence' => $this->evidenceList->get(0)->id,
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach (['Id', 'Filename', 'Sender', 'Subject', 'Created at'] as $el) {
            $this->assertContains($el, $output);
        }
    }

    public function testWithInvalidFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'evidence:show',
            [
                'evidence' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No matching evidence was found.', Artisan::output());
    }
}
