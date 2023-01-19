<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use DatabaseTransactions;

    private $accounts;
    private $name1;
    private $name2;

    public function testHeaders()
    {
        $this->initDB();

        $exitCode = Artisan::call('account:list', []);

        $this->assertEquals(Command::SUCCESS, $exitCode);

        $headers = ['Id', 'Name', 'Brand', 'Disabled'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    public function testAll()
    {
        $this->initDB();
        $exitCode = Artisan::call('account:list', []);

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $output = Artisan::output();
        $this->assertStringContainsString($this->name1, $output);
        $this->assertStringContainsString($this->name2, $output);
    }

    public function testFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'account:list',
            [
                '--filter' => $this->name1,
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $output = Artisan::output();
        $this->assertStringContainsString($this->name1, $output);
        $this->assertStringNotContainsString($this->name2, $output);
    }

    public function testNotFoundFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'account:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('No account found for given filter.', Artisan::output());
    }

    /**
     * this should not be part of the setUp method because the database connection
     * has NOT been setUp properly at that moment.
     */
    private function initDB()
    {
        Account::where('id', '!=', 1)->delete();

        $this->accounts = factory(Account::class, 10)->create();

        $this->name1 = $this->accounts->first()->name;
        $this->name2 = $this->accounts->get(1)->name;
    }
}
