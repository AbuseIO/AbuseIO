<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

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
    private $noteList;

    public function initDB()
    {
        $this->noteList = factory(Note::class, 10)->create();
    }

    public function testWithValidIdFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'note:show',
            [
                'note' => $this->noteList->get(0)->id,
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach (['Id',  'Ticket id', 'Submitter', 'Text', 'Hidden', 'Viewed'] as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'note:show',
            [
                'note' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No matching note was found.', Artisan::output());
    }
}
