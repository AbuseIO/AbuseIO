<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Account;
use Illuminate\Console\Command;

class ListAccount extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:list {--filter= : Filters accounts on name}
                                         {--json : Output in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all accounts or listed accounts based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filter = $this->option('filter');
        if (!empty($filter)) {
            $account = Account::select('id', 'name', 'brand_id', 'disabled')->where('name', 'like', "%{$filter}%")->get();
        } else {
            $account = Account::select('id', 'name', 'brand_id', 'disabled')->get();
        }

        if ($account->isEmpty()) {
            $this->error('No accounts found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($account->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Id', 'Name', 'Brand', 'Disabled'],
                $account->map(function ($acc) {
                    return [
                        'Id' => $acc->id,
                        'Name' => $acc->name,
                        'Brand' => $acc->brand->name,
                        'Disabled' => castBoolToString($acc->disabled),
                    ];
                })->toArray()
            );

        }
        return $this->getSuccessExitCode();
    }
}
