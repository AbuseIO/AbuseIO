<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\StoreAccountRequest;
use AbuseIO\Models\Account;
use Illuminate\Console\Command;
use Validator;

class CreateAccount extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:create 
                            {name : Name of the account} 
                            {brand_id : ID of the brand to associate with this account} 
                            {disabled? : Set to true to disable the account} 
                            {--with_api_key : Include this option to generate an API token for the account}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an account';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $account = Account::make([
            'name' => $this->argument('name'),
            'brand_id' => $this->argument('brand_id'),
            'disabled' => $this->argument('disabled') ?? false,
            'token' => $this->option('with_api_key') ? generateApiToken() : null,
        ]);

        $validator = Validator::make($account->toArray(), new StoreAccountRequest()->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if ($account->save()) {
            $this->info('The account has been created successfully.');
            return $this->getSuccessExitCode();
        } else {
            $this->error('Failed to save the account.');
            return $this->getSaveFailedExitCode();
        }
    }
}
