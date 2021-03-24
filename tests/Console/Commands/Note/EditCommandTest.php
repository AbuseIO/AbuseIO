<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var \AbuseIO\Models\Note
     */
    private $noteHidden;

    /**
     * @var \AbuseIO\Models\Note
     */
    private $noteViewed;

    private function initDB()
    {
        $this->noteHidden = factory(Note::class)->create(['hidden' => false]);
        $this->noteViewed = factory(Note::class)->create(['viewed' => false]);
    }

    public function testWithoutId()
    {
        ob_start();
        Artisan::call('note:edit');
        $this->assertStringContainsString('Edit a note', ob_get_clean());
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'note:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find note with this criteria', Artisan::output());
    }

    public function testWithHidden()
    {
        $this->initDB();

        $this->assertFalse((bool) $this->noteHidden->hidden);

        $exitCode = Artisan::call(
            'note:edit',
            [
                'id'       => $this->noteHidden->id,
                '--hidden' => 'true',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The note has been updated', Artisan::output());

        $this->assertTrue((bool) Note::find($this->noteHidden->id)->hidden);
    }

    public function testEnabled()
    {
        $this->initDB();

        $this->assertFalse((bool) Note::find($this->noteViewed->id)->viewed);

        $exitCode = Artisan::call(
            'note:edit',
            [
                'id'       => $this->noteViewed->id,
                '--viewed' => 'true',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The note has been updated', Artisan::output());
        /*
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->assertTrue((bool) Note::find($this->noteViewed->id)->viewed);
    }
}
