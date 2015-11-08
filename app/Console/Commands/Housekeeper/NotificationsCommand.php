<?php

namespace AbuseIO\Console\Commands\Housekeeper;

use Illuminate\Console\Command;
use Carbon;
use AbuseIO\Jobs\Notification;
use AbuseIO\Models\Ticket;
use Log;

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
        'IP Contact Email',
        'IP Contact RPC host',
        'Domain name',
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
        'ip_contact_email',
        'ip_contact_rpchost',
        'domain',
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

        if (!empty($this->option('list')) && $this->option('list') === true) {
            $tickets = $this->notificationList();
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
            Log::debug(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                "A notification run has been started"
            );

            $tickets = $this->notificationList();
            if (empty($tickets)) {
                $this->info("No tickets that need to send out notifications");
                return true;
            }

            /*
             * Initiate notification for the ticket by passing it the entire ticket to the sender
             * This will allow manual notifications by calling the send($ticket) remotely
             */
            $counter = 0;
            foreach ($tickets as $ticket) {
                $notification = new Notification;

                $result = $notification->send($ticket);

                if (!$result) {
                    Log::debug(
                        '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                        "Errors occured when sending out notifications, check the log or run in debug mode"
                    );
                    return false;
                } else {
                    $ticket->last_notify_count      = $ticket->events->count();
                    $ticket->last_notify_timestamp  = time();
                    $ticket->notified_count         = $ticket->notified_count + 1;
                    $ticket->save();
                    $counter++;
                }
            }

            Log::debug(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                "Successfully send out {$counter} ticket notifications"
            );
        }

        return true;
    }

    /**
     * Create a list of tickets that need outgoing notifications.
     * @return array
     */
    public function notificationList()
    {
        /*
         * Select a list of tickets that are not closed(2).
         */
        $selection = [ ];

        $tickets = Ticket::where('status_id', '!=', '2')->get();

        foreach ($tickets as $ticket) {
            /*
             * Only send a notification if there is something new to report
             * or if this is the first notification.
             */
            if ($ticket->last_notify_count == $ticket->events->count() &&
                $ticket->last_notify_count != 0
            ) {
                continue;
            } else {
                $selection[] = $ticket;
            }
        }

        return $selection;
    }

    public function notificationSend($ticket)
    {

    }
}
