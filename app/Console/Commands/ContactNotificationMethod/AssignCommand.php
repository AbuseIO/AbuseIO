<?php

namespace AbuseIO\Console\Commands\ContactNotificationMethod;

use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Contact;
use AbuseIO\Services\NotificationService;
use Illuminate\Console\Command;

/**
 * Class AssignCommand.
 */
class AssignCommand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'notification_method:assign
                            {--method= : The method name or ID where the notifiction method will be assigned to }
                            {--contact= : The contact name (e-mail) or ID of which contact the notification method will be assigned to }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a notification method to a contact';

    /**
     * {@inheritdoc}.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        if (empty($this->option('contact')) &&
            empty($this->option('method'))
        ) {
            throw new \RuntimeException('Missing options for the notification method and/or contact (e-mail) to select');

            return false;
        }

        $method = false;
        $contact = false;

        if (!empty($this->option('contact'))) {
            if (!is_object($contact)) {
                $contact = Contact::where('name', $this->option('contact'))->first();
            }

            if (!is_object($contact)) {
                $contact = Contact::where('email', $this->option('contact'))->first();
            }

            if (!is_object($contact)) {
                $contact = Contact::find($this->option('contact'));
            }
        }

        if (!empty($this->option('method'))) {
            if (NotificationService::isValidMethod($this->option('method'))) {
                $method = $this->option('method');
            }
        }

        if (!is_object($contact)) {
            $this->error('Unable to find contact with this contact info');

            return false;
        }
        if ($method === false) {
            $this->error('Unable to find method with this method name');

            return false;
        }
        $contact->addNotificationMethod(['method' => $method]);
        $this->info("The notification method {$method} has been granted to contact {$contact->email}");

        return true;
    }
}
