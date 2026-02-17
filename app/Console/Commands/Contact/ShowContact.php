<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Contact;
use Illuminate\Console\Command;

class ShowContact extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:show {contact : Use the id or name for a contact to show it.}
                                         {--json : Output the contact details in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows details of a contact based on the given id or name';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentContact = $this->argument('contact');
        $contactSelectQuery = Contact::select('id', 'reference', 'name', 'email', 'api_host', 'enabled', 'account_id');

        $contact = $contactSelectQuery->where('name', 'like', '%' . $inputArgumentContact . '%')
            ->orWhere('id', $inputArgumentContact)
            ->first();

        if (empty($contact)) {
            $this->error('No matching contact was found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($contact->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Id', $contact->id],
                    ['Reference', $contact->reference],
                    ['Name', $contact->name],
                    ['Email', $contact->email],
                    ['Api host', $contact->api_host],
                    ['Enabled', castBoolToString($contact->enabled)],
                    ['Account', $contact->account->name],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
