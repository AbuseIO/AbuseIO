<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Account;
use Illuminate\Console\Command;

class ShowAccount extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:show
                                {account : Name of the account to show}
                                {--json : Output the account details in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows an account';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputAccountArgument = $this->argument('account');
        $accountSelectQuery = Account::select('id', 'name', 'brand_id', 'disabled', 'description', 'token');

        $account = $accountSelectQuery->where('name', 'like', '%' . $inputAccountArgument . '%')
            ->orWhere('id', $inputAccountArgument)->first();

        if (empty($account)) {
            $this->error('No matching account was found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($account->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Id', $account->id],
                    ['Name', $account->name],
                    ['Brand', $account->brand->name],
                    ['Disabled', castBoolToString($account->disabled)],
                    ['Description', $account->description],
                    ['Api token', $account->token],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
