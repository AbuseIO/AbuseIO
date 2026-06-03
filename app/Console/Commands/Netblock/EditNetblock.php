<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\UpdateNetblockRequest;
use AbuseIO\Models\Brand;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use Illuminate\Console\Command;
use Validator;

class EditNetblock extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netblock:edit
                            {id : Netblock id to edit}
                            {--contact_id= : Id for contact}
                            {--first_ip= : First ip}
                            {--last_ip= : Last ip}
                            {--description= : Description}
                            {--enabled= : Enabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edits an existing netblock';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $inputOptionContactId = $this->option('contact_id');
        $inputOptionFirstIp = $this->option('first_ip');
        $inputOptionLastIp = $this->option('last_ip');
        $inputOptionDescription = $this->option('description');
        $inputOptionEnabled = castStringToBool($this->option('enabled'));

        $netblock = Netblock::where('id', $inputArgumentId)->first();
        if (!$netblock) {
            $this->error('Unable to find netblock with this criteria');
            return $this->getNotFoundExitCode();
        }

        if ($inputOptionContactId) {
            $contact = Contact::where('id', $inputOptionContactId)->first();
            if (!$contact) {
                $this->error('Unable to find contact with this criteria');
                return $this->getInvalidOptionExitCode();
            }
        }

        $netblock->contact_id = $inputOptionContactId ?? $netblock->contact_id;
        $netblock->first_ip = $inputOptionFirstIp ?? $netblock->first_ip;
        $netblock->last_ip = $inputOptionLastIp ?? $netblock->last_ip;
        $netblock->description = $inputOptionDescription ?? $netblock->description;
        $netblock->enabled = $inputOptionEnabled ?? $netblock->enabled;

        $request = new UpdateNetblockRequest();
        $request->merge(['id' => $netblock->id]);
        $validator = Validator::make($netblock->toArray(), $request->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if (!$netblock->update()) {
            $this->error('Failed to update the netblock.');
            return $this->getSaveFailedExitCode();
        }

        $this->info('The netblock has been updated.');
        return $this->getSuccessExitCode();
    }
}
