<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\StoreNetblockRequest;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use Illuminate\Console\Command;
use Validator;

class CreateNetblock extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netblock:create
                            {contact : Id from contact}
                            {first_ip : Start Ip address from netblock}
                            {last_ip : Last Ip address from netblock}
                            {description : Description}
                            {enabled=false : Set the account to be enabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new netblock';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentContact = $this->argument('contact');
        $inputArgumentFirstIp = $this->argument('first_ip');
        $inputArgumentLastIp = $this->argument('last_ip');
        $inputArgumentDescription = $this->argument('description');
        $inputArgumentEnabled = castStringToBool($this->argument('enabled'));

        $contact = Contact::where('id', $inputArgumentContact)->first();
        if (!$contact) {
            $this->error("Could not find contact");
            return $this->getNotFoundExitCode();
        }

        $netblock = Netblock::make([
            'first_ip' => $inputArgumentFirstIp,
            'last_ip' => $inputArgumentLastIp,
            'description' => $inputArgumentDescription,
            'enabled' => $inputArgumentEnabled,
        ]);

        $netblock->contact()->associate($contact);

        $validator = Validator::make($netblock->toArray(), new StoreNetblockRequest()->rules());

        if ($validator->fails()) {
            $this->error("Validation failed: " . implode(", ", $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if (!$netblock->save()) {
            $this->error("Failed to save netblock");
            return $this->getSaveFailedExitCode();
        }

        $this->info("Netblock created successfully.");
        return $this->getSuccessExitCode();
    }
}
