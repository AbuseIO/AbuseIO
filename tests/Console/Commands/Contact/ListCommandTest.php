<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Contact;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use DatabaseTransactions;

    private $contacts;

    private $name1;

    private $name2;

    /**
     * this should not be part of the setUp method because the database connection
     * has NOT been setUp properly at that moment.
     */
    private function initDB()
    {
        //        \DB::table('domains')->truncate();
        //        \DB::table('contacts')->truncate();
        //        Contact::all()->delete();

        $this->contacts = factory(Contact::class, 10)->create();

        $this->name1 = $this->contacts->first()->name;
        $this->name2 = $this->contacts->get(1)->name;
    }

    public function testHeaders()
    {
        $this->initDB();

        $exitCode = Artisan::call('contact:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Name', 'Email', 'Api host'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    public function testAll()
    {
        $this->initDB();

        $exitCode = Artisan::call('contact:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertStringContainsString($this->name1, $output);
        $this->assertStringContainsString($this->name2, $output);
    }

    public function testFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'contact:list',
            [
                '--filter' => $this->name1,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertStringContainsString($this->name1, $output);
        $this->assertStringNotContainsString($this->name2, $output);
    }

    public function testNotFoundFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'contact:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No contact found for given filter.', Artisan::output());
    }
}
