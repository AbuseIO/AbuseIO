<?php

namespace AbuseIO\Console\Commands\GraphPoints;

use AbuseIO\Jobs\GenerateTicketsGraphPoints;
use Illuminate\Bus\Dispatcher;
use Illuminate\Console\Command;

/**
 * Class ListCommand.
 */
class CreateTicketPointsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticketpoints:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generates the ticket points for today';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Dispatcher $dispatcher)
    {
        $tickets = $dispatcher->dispatch(new GenerateTicketsGraphPoints());

        dd(($tickets));
    }
}
