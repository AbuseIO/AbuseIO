<?php

namespace AbuseIO\Console\Commands\Ticket;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Ticket;
use Illuminate\Console\Command;

class ListTicket extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:list {--filter=}
                                        {--json : Output the list in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all tickets in the system, optionally filtered by ID';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputOptionFilter = $this->option('filter');
        $ticketSelectQuery = Ticket::select('id', 'ip', 'domain', 'class_id', 'type_id');

        if ($inputOptionFilter) {
            $tickets = $ticketSelectQuery->where('id', $inputOptionFilter)->get();
        } else {
            $tickets = $ticketSelectQuery->get();
        }

        if (empty($tickets) || $tickets->count() === 0) {
            $this->error('No tickets found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($tickets->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Id', 'Ip', 'Domain', 'Class id', 'Type id'],
                $tickets->toArray()
            );
        }

        return $this->getSuccessExitCode();
    }
}
