<?php

namespace AbuseIO\Console\Commands\Housekeeper;

use AbuseIO\Jobs\Notification;
use Illuminate\Console\Command;

/**
 * Class NotificationsCommand.
 */
class NotificationsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'housekeeper:notifications
                            {--l|list : Shows a list of pending notifications }
                            {--s|send : Sends out pending notifications manually }
                            {--t|ticket= : Sends out notification for a specific ticket }
                            {--r|reference= : Sends out notification for a specific contact reference }
                            {--f|force : Sends out notification, regardless if there pending notifications }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of send out pending notifications';

    /**
     * They headers of the table.
     *
     * @var array
     */
    protected $headers = [
        'Ticket',
        'IP Address',
        'IP Contact Reference',
        'IP Contact Email',
        'IP Contact RPC host',
        'Domain name',
        'Domain Contact Reference',
        'Domain Contact Email',
        'Domain Contact RPC host',
    ];

    /**
     * They fields of the table / database row.
     *
     * @var array
     */
    protected $fields = [
        'id',
        'ip',
        'ip_contact_reference',
        'ip_contact_email',
        'ip_contact_api_host',
        'domain',
        'domain_contact_reference',
        'domain_contact_email',
        'domain_contact_api_host',
    ];

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->fields = array_combine($this->fields, $this->fields);
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        if (empty($this->option('list')) &&
            empty($this->option('send'))

        ) {
            $this->error('Invalid or incomplete option(s) used, try --help');

            return false;
        }

        $notification = new Notification();
        $searchTicket = false;
        $searchReference = false;
        $searchForce = false;

        if ($this->option('ticket') !== null) {
            $searchTicket = $this->option('ticket');
        }
        if ($this->option('reference') !== null) {
            $searchReference = $this->option('reference');
        }
        if ($this->option('force') !== null) {
            $searchForce = $this->option('force');
        }

        $notifications = $notification->buildList($searchTicket, $searchReference, $searchForce);

        if (!empty($this->option('list')) && $this->option('list') === true) {
            if (empty($notifications)) {
                return true;
            }
            if (!is_array($notifications)) {
                $this->error('Error(s) received while building notifications list:'.PHP_EOL.$notifications);

                return false;
            }

            /*
             * Apply field filtering for output
             */
            $list = [];

            foreach ($notifications as $customerReference => $notificationTypes) {
                foreach ($notificationTypes as $notificationType => $tickets) {
                    foreach ($tickets as $ticket) {
                        $list[$notificationType][] = array_intersect_key($ticket->toArray(), $this->fields);
                    }
                }
            }

            foreach ($list as $notificationType => $table) {
                $this->info("Notifications for {$notificationType} contacts:");
                $this->table($this->headers, $table);
                $this->info('');
            }
        }

        if (!empty($this->option('send')) && $this->option('send') === true) {
            $errors = $notification->walkList($notifications);

            if ($errors !== true) {
                $this->error("Errors ({$errors}) while sending notifications. Details logged under JOB ".getmypid());
            } else {
                $this->info('Successfully send out notifications. Details logged under JOB '.getmypid());
            }
        }

        return true;
    }
}
