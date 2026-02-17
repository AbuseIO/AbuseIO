<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\UpdateDomainRequest;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use Illuminate\Console\Command;
use Validator;

class EditDomain extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:edit 
                            {id : The ID of the domain to edit}
                            {--contact_id= : Contact id for domain}
                            {--name= : Name}
                            {--enabled= : true|false, Set the domain to be enabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edits an existing domain';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $inputOptionContactId = $this->option('contact_id');
        $inputOptionName = $this->option('name');
        $inputOptionEnabled = $this->option('enabled');

        $domain = Domain::where('id', $inputArgumentId)->first();
        if (!$domain) {
            $this->error('Unable to find domain with this criteria');
            return $this->getNotFoundExitCode();
        }

        if ($inputOptionContactId && !Contact::where('id', $inputOptionContactId)->first()) {
            $this->error('Unable to find contact with this criteria');
            return $this->getNotFoundExitCode();
        }

        $domain->contact_id = $inputOptionContactId ?? $domain->contact_id;
        $domain->name = $inputOptionName ?? $domain->name;
        $domain->enabled = castStringToBool($inputOptionEnabled) ?? $domain->enabled;

        $request = new UpdateDomainRequest();
        $request->merge(['id' => $domain->id]);
        $validator = Validator::make($domain->toArray(), $request->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if ($domain->update()) {
            $this->info('Domain updated successfully.');
            return $this->getSuccessExitCode();
        }

        $this->error('Failed to update domain.');
        return $this->getSaveFailedExitCode();
    }
}
