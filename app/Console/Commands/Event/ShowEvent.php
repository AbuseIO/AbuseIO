<?php

namespace AbuseIO\Console\Commands\Event;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Event;
use Illuminate\Console\Command;

class ShowEvent extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:show {event : The ID of the event}
                                       {--json : Output the event in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows an event based on the provided ID';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgument = $this->argument('event');
        $event = Event::where('id', $inputArgument)->first();

        if (!$event) {
            $this->error("No matching event was found.");
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($event->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Id', $event->id],
                    ['Ticket ID', $event->ticket_id],
                    ['Evidence ID', $event->evidence_id],
                    ['Source', $event->source],
                    ['Timestamp', $event->timestamp],
                    ['Information', $event->information],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
