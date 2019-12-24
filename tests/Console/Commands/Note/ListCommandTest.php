<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use DatabaseTransactions;

    private $noteList;

    /**
     * the list of test fixture to test against.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public function initDB()
    {
        //$this->assertTrue(true);
        $this->noteList = factory(Note::class, 10)->create();
    }

    public function testHeaders()
    {
        $this->initDB();
        $exitCode = Artisan::call('note:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Ticket id', 'Submitter', 'text', 'Hidden', 'Viewed'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    public function testAll()
    {
        $this->initDB();
        $exitCode = Artisan::call('note:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertStringContainsString($this->noteList->get(0)->submitter, $output);
        $this->assertStringContainsString($this->noteList->get(1)->submitter, $output);
    }

    public function testFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'note:list',
            [
                '--filter' => $this->noteList->get(0)->submitter,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertStringContainsString($this->noteList->get(0)->submitter, $output);
        $this->assertStringNotContainsString($this->noteList->get(1)->submitter, $output);
    }

    public function testNotFoundFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'note:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No note found for given filter.', Artisan::output());
    }
}
