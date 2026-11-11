<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Contact;
use Illuminate\Console\Command;

class ListContact extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:list {--filter= : Filter contacts by name}
                                         {--json : Output in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all contacts or listed contacts based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputOptionFilter = $this->option('filter');
        $contactSelectQuery = Contact::select('id', 'name', 'email', 'api_host');

        if ($inputOptionFilter) {
            $contacts = $contactSelectQuery->where('name', 'like', "%{$inputOptionFilter}%")->get();
        } else {
            $contacts = $contactSelectQuery->get();
        }

        if ($contacts->isEmpty()) {
            $this->error('No contacts found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($contacts->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Id', 'Name', 'Email', 'Api host'],
                $contacts->map(function ($contact) {
                    return [
                        'Id' => $contact->id,
                        'Name' => $contact->name,
                        'Email' => $contact->email,
                        'Api host' => $contact->api_host,
                    ];
                })->toArray()
            );
        }

        return $this->getSuccessExitCode();
    }
}
