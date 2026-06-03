<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\StoreDomainRequest;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use Illuminate\Console\Command;
use Validator;

class CreateDomain extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:create 
                            {name : domain name}
                            {contact_id : the contact_id} 
                            {enabled=false : true|false, Set the account to be enabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new domain';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentName = $this->argument('name');
        $inputArgumentContactId = $this->argument('contact_id');
        $inputArgumentEnabled = castStringToBool($this->argument('enabled'));

        $contact = Contact::where('id', $inputArgumentContactId)->get();
        if (empty($contact) || $contact->count() === 0) {
            $this->error("Could not find contact.");
            return $this->getFailureExitCode();
        }

        $domain = Domain::make([
            'name'       => $inputArgumentName,
            'contact_id' => $inputArgumentContactId,
            'enabled'    => $inputArgumentEnabled,
        ]);

        $validator = Validator::make($domain->toArray(), new StoreDomainRequest()->rules());

        if ($validator->fails()) {
            $this->error("Validation failed: " . implode(", ", $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        $this->info("The domain has been created.");
        return $this->getSuccessExitCode();
    }
}
