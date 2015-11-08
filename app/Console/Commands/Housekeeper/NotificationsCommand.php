<?php

namespace AbuseIO\Console\Commands\Housekeeper;

use Illuminate\Console\Command;
use Carbon;
use AbuseIO\Jobs\Notification;

class NotificationsCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'housekeeper:notifications
                            {--list : Shows a list of pending notifications }
                            {--send : Sends out pending notifications manually }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'List of send out pending notifications';

    /**
     * They headers of the table
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
        'Domain Contact RPC host'
    ];

    /**
     * They fields of the table / database row
     * @var array
     */
    protected $fields = [
        'id',
        'ip',
        'ip_contact_reference',
        'ip_contact_email',
        'ip_contact_rpchost',
        'domain',
        'domain_contact_reference',
        'domain_contact_email',
        'domain_contact_rpchost'
    ];

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (empty($this->option('list')) &&
            empty($this->option('send'))

        ) {
            $this->error('Invalid option(s) used, try --help');
            return false;
        }

        $notification = new Notification;

        if (!empty($this->option('list')) && $this->option('list') === true) {
            $tickets = $notification->buildList();

            if (empty($tickets)) {
                return true;
            }

            /*
             * Apply field filtering for output
             */
            $list = [];
            foreach ($tickets as $ticket) {
                $list = $ticket->get($this->fields);
            }

            $this->table($this->headers, $list);
        }

        if (!empty($this->option('send')) && $this->option('send') === true) {

            $errors = $notification->walkList();

            if ($errors !== 0) {
                $this->error("Errors ({$errors}) while sending notifications. Details logged under JOB " . getmypid());
            } else {
                $this->info("Successfully send out notifications. Details logged under JOB " . getmypid());
            }

        }

        return true;
    }
}
