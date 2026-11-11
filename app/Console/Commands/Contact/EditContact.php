<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\UpdateContactRequest;
use AbuseIO\Models\Contact;
use Illuminate\Console\Command;
use Validator;

class EditContact extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:edit 
                                        {id : Contact id to edit}
                                        {--name= : Contact name} 
                                        {--reference= : Contact reference} 
                                        {--account_id= : Account id} 
                                        {--enabled= : Enabled} 
                                        {--email= : Email} 
                                        {--api_host= : Api host}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit an existing contact';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $inputOptionName = $this->option('name');
        $inputOptionReference = $this->option('reference');
        $inputOptionAccountId = $this->option('account_id');
        $inputOptionEnabled = castStringToBool($this->option('enabled'));
        $inputOptionEmail = $this->option('email');
        $inputOptionApiHost = $this->option('api_host');

        $contact = Contact::where('id', $inputArgumentId)->first();

        if (!$contact) {
            $this->error('Unable to find contact.');
            return $this->getNotFoundExitCode();
        }

        $contact->name = $inputOptionName ?? $contact->name;
        $contact->reference = $inputOptionReference ?? $contact->reference;
        $contact->account_id = $inputOptionAccountId ?? $contact->account_id;
        $contact->enabled = $inputOptionEnabled ?? $contact->enabled;
        $contact->email = $inputOptionEmail ?? $contact->email;
        $contact->api_host = $inputOptionApiHost ?? $contact->api_host;

        $request = new UpdateContactRequest();
        $request->merge(['id' => $contact->id]);
        $validator = Validator::make($contact->toArray(), $request->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if (!$contact->update()) {
            $this->error('Failed to update the contact.');
            return $this->getSaveFailedExitCode();
        }

        $this->info('The contact has been updated successfully.');
        return $this->getSuccessExitCode();
    }
}
