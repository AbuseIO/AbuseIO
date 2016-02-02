<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use DatabaseTransactions;

    private $note;
    private $note1;

    public function initDB()
    {
        $this->note = factory(Note::class)->create();

        $this->note1 = factory(Note::class)->create();
    }
    
    public function testHeaders()
    {
        $this->initDB();
        $exitCode = Artisan::call('note:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Ticket id', 'Submitter', 'text', 'Hidden', 'Viewed'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $this->initDB();
        $exitCode = Artisan::call('note:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Abusedesk', $output);
        $this->assertContains('Domain Contact', $output);
    }

    public function testFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'note:list',
            [
                '--filter' => $this->note->submitter,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains($this->note->submitter, $output);
        $this->assertNotContains($this->note1->submitter, $output);
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
        $this->assertContains('No note found for given filter.', Artisan::output());
    }
}
