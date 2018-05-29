<?php
/**
 * Created by IntelliJ IDEA.
 * User: jover
 * Date: 28/05/2018
 * Time: 10:09.
 */

namespace AbuseIO\Console\Commands\GDPR;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Ticket;
use Illuminate\Console\Command;

class AnonymizeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'gdpr:anonymize
                            {email : Anonymize all data related to this email address. }
                            {--yes : Confirm the action}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anonymizes all data (Contacts / Tickets) related to an email address';

    public function handle()
    {
        $email = $this->argument('email');
        $confirm = $this->option('yes');
        $randomness = sprintf('%d', time());

        try {
            $contacts = Contact::withTrashed()->where('email', '=', $email)->get();
            $tickets = Ticket::withTrashed()->where('ip_contact_email', '=', $email)->get();
            $tickets = $tickets->merge(Ticket::withTrashed()->where('domain_contact_email', '=', $email)->get());

            $this->info('Found '.$contacts->count().' Contacts and '.$tickets->count().' Tickets for '.$email);

            $this->info('Contacts:');
            foreach ($contacts as $contact) {
                if ($confirm) {
                    $this->info("\t- Anonymizing Contact ".$contact->id);
                    $contact->anonymize($randomness);
                } else {
                    $this->info("\t- Skipping Contact ".$contact->id.' (Dry run)');
                }
            }
            $this->info('Tickets:');
            foreach ($tickets as $ticket) {
                if ($confirm) {
                    $this->info("\t- Anonymizing Ticket ".$ticket->id);
                    $ticket->anonymize($email, $randomness);
                } else {
                    $this->info("\t- Skipping Ticket ".$ticket->id.' (Dry run)');
                }
            }
        } catch (\Exception $e) {
            $this->error('Error: '.$e->getMessage());
        }
    }
}
