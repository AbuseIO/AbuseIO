<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\StoreContactRequest;
use AbuseIO\Models\Contact;
use Illuminate\Console\Command;
use Validator;

class CreateContact extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:create 
                                        {name : Name of the contact}
                                        {reference : Reference of the contact}
                                        {account_id : Account ID associated with the contact}
                                        {enabled : Is the contact enabled (true/false)}
                                        {email : Email address of the contact}
                                        {api_host : API host URL of the contact}
                                        {--with_api_key : Generate an API key for the contact}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new contact';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentName = $this->argument('name');
        $inputArgumentReference = $this->argument('reference');
        $inputArgumentAccountId = $this->argument('account_id');
        $inputArgumentEnabled = castStringToBool($this->argument('enabled'));
        $inputArgumentEmail = $this->argument('email');
        $inputArgumentApiHost = $this->argument('api_host');
        $inputOptionWithApiKey = $this->option('with_api_key') ? generateApiToken() : null;

        $contact = Contact::make([
            'name' => $inputArgumentName,
            'reference' => $inputArgumentReference,
            'account_id' => $inputArgumentAccountId,
            'enabled' => $inputArgumentEnabled,
            'email' => $inputArgumentEmail,
            'api_host' => $inputArgumentApiHost,
            'token' => $inputOptionWithApiKey,
        ]);

        $validator = Validator::make($contact->toArray(), new StoreContactRequest()->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if ($contact->save()) {
            $this->info('The contact has been created successfully.');
            return $this->getSuccessExitCode();
        } else {
            $this->error('Failed to save the contact.');
            return $this->getSaveFailedExitCode();
        }
    }
}
