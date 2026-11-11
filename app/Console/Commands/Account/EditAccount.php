<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\UpdateAccountRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use Illuminate\Console\Command;
use Validator;

class EditAccount extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:edit 
                                {id : ID of the account to edit}
                                {--name= : New name of the account}
                                {--brand_id= : New brand ID to associate with this account}
                                {--disabled= : Set to true to disable the account, false to enable it}
                                {--systemaccount= : Set to true to mark as system account, false otherwise}
                                {--refresh_api_token : Refresh the API token for this account}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edits an existing account';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $inputOptionName = $this->option('name');
        $inputOptionBrandId = $this->option('brand_id');
        $inputOptionDisabled = $this->option('disabled');
        $inputOptionSystemAccount = $this->option('systemaccount');
        $inputOptionRefreshApiToken = $this->option('refresh_api_token');

        $account = Account::where('id', $inputArgumentId)->first();

        if (!$account) {
            $this->error('Account not found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('brand_id')) {
            $brand = Brand::where('id', $this->option('brand_id'))->first();

            if (!$brand) {
                $this->error('Brand not found.');
                return $this->getNotFoundExitCode();
            }
        }

        $account->name = $inputOptionName ?? $account->name;
        $account->brand_id = $inputOptionBrandId ?? $account->brand_id;
        $account->disabled = $inputOptionDisabled !== null ? ($inputOptionDisabled == 'true') : $account->disabled;
        $account->systemaccount = $inputOptionSystemAccount !== null ? ($inputOptionSystemAccount == 'true') : $account->systemaccount;
        $account->token = $inputOptionRefreshApiToken ? generateApiToken() : $account->token;

        $request = new UpdateAccountRequest();
        $request->merge(['id' => $account->id]);
        $validator = Validator::make($account->toArray(), $request->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if (!$account->update()) {
            $this->error('Failed to update the account.');
            return $this->getSaveFailedExitCode();
        }

        $this->info('The account has been updated successfully.');
        return $this->getSuccessExitCode();
    }
}
