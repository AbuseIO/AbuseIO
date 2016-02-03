<?php

namespace tests\Console\Commands\Evidence;

use AbuseIO\Models\Evidence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Illuminate\Database\Eloquent\Collection
     */
    private $evidenceList;

    private function initDB()
    {
        $this->evidenceList = factory(Evidence::class, 10)->create();;
    }

    public function testHeaders()
    {
        $this->initDB();
        $exitCode = Artisan::call('evidence:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Filename', 'Sender', 'Subject', 'Created at'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $this->initDB();

        $exitCode = Artisan::call('evidence:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains($this->evidenceList->get(0)->subject, $output);
        $this->assertContains($this->evidenceList->get(1)->subject, $output);
    }

    public function testFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'evidence:list',
            [
                '--filter' => $this->evidenceList->get(3)->sender,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains($this->evidenceList->get(3)->subject, $output);
        $this->assertNotContains($this->evidenceList->get(0)->subject, $output);
    }

    public function testNotFoundFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'evidence:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No evidence found for given filter.', Artisan::output());
    }
}
