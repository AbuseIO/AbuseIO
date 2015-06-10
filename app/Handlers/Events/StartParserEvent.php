<?php namespace AbuseIO\Handlers\Events;

use AbuseIO\Events\EmailParsedEvent;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class StartParserEvent implements ShouldBeQueued
{

    use InteractsWithQueue;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EmailParsedEvent  $event
     * @return void
     */
    public function handle(EmailParsedEvent $event)
    {
        // So lets not have parsers directly insert data into the database and rather have them return an array
        // with the data set it managed to figure out AND its endresult status. If a mail cannot be handled fully
        // we are just going to ignore the rest and have the admin sort the error first.
        // We could consider adding an option that allows admins to accept the events that actually work and partially
        // deny the rest, but that would give problems with a new attempt of parsing (although we can filter duplicate
        // events at that point.
        //
        // Run the 'php artisan queue:work --daemon --sleep=3 --tries=1' command as a daemon for queue running?
        // Possible to check wither the queue is actually running stuff?

        /* TODO: call up the $parser and either pass the email parts (yes both, so some notifiers put bits in either
           TODO: (cont) of them and not both of them.
           Format:
                event [
                    Source
                    IP
                    Domain
                    URI
                    Timestamp
                    Infoblob
                ];
        */

        // TODO: If the result is OK, then call up the validator

        // TODO: if the validator agrees, then save the data into the database
        // TODO: Phase 1 : Log the evidence
        // TODO: Phase 2 : Save evidence for existing tickets and link them
        // TODO: Phase 3 : Create a new ticket and link evidence for it

        // TODO: return a valid response to let the EmailParseCommand know of the result

        dd($event);
    }

}
