<?php
/**
 * Created by IntelliJ IDEA.
 * User: jover
 * Date: 28/05/2018
 * Time: 10:08.
 */

namespace AbuseIO\Console\Commands\GDPR;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Ticket;
use Illuminate\Console\Command;

class ReportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'gdpr:report
                            {email : The email address to create a report for. }
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a report with all data related to an email address';

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function handle()
    {
        $success = true;
        $email = $this->argument('email');

        $this->info('Creating report for: '.$email."\n");

        try {
            // find all contacts
            $contacts = Contact::withTrashed()->where('email', '=', $email)->get();

            // find all tickets
            $tickets = Ticket::withTrashed()->where('ip_contact_email', '=', $email)->get();
            $tickets = $tickets->merge(Ticket::withTrashed()->where('domain_contact_email', '=', $email)->get());

            $this->report($contacts, 'AbuseIO\Models\Contact', $email);
            $this->report($tickets, 'AbuseIO\Models\Ticket', $email);
        } catch (\Exception $e) {
            $this->error('Error: '.$e->getMessage());
            $success = false;
        }

        return $success;
    }

    /**
     * export the object data to a table.
     *
     * @param $objects
     * @param $type
     * @param $email
     */
    public function report($objects, $type, $email)
    {
        $table_headers = [];
        $table_body = [];

        // create the table body
        foreach ($objects as $object) {
            if (empty($table_headers)) {
                $table_headers = array_keys($object->getAttributes());
            }

            $object_values = array_values($object->getAttributes());
            $table_body[] = $object_values;
        }

        $table = $this->makeTable($table_headers, $table_body);

        $this->info("All $type data for $email: ");
        foreach ($table as $row) {
            $this->info($row);
        }

        $this->info("\n");
    }

    /**
     * pretty print the data in a table and return it.
     *
     * @param $header
     * @param $body
     *
     * @return array
     */
    protected function makeTable($header, $body)
    {
        $padding = 4;
        $totalSize = 0;
        $columnSize = [];
        $table = [];

        // calculate the initial size of the columns
        foreach ($header as $column) {
            $columnSize[] = strlen($column) + $padding;
        }

        // use the body column size if it is bigger
        foreach ($body as $row => $rowValue) {
            foreach ($rowValue as $column => $value) {
                $size = strlen(strval($value)) + $padding;
                $body[$row][$column] = strval($value);
                if (array_key_exists($column, $columnSize)) {
                    if ($columnSize[$column] < $size) {
                        $columnSize[$column] = $size;
                    }
                } else {
                    $columnSize[] = $size;
                }
            }
        }

        // calculate the total size of the table
        foreach ($columnSize as $column) {
            // add the column border
            $totalSize = $totalSize + $column + 1;
        }
        // table border
        $totalSize += 1;

        // create the table
        // header
        $table[] = sprintf("%'={$totalSize}s", '');
        $headerNames = '';
        foreach ($header as $key => $column) {
            $headerNames .= sprintf("%' {$columnSize[$key]}s|", $column.' ');
        }
        $table[] = '|'.$headerNames;
        $table[] = sprintf("%'={$totalSize}s", '');

        // body
        foreach ($body as $row) {
            $rowValues = '';
            foreach ($row as $key => $column) {
                $rowValues .= sprintf("%' {$columnSize[$key]}s|", $column.' ');
            }
            $table[] = '|'.$rowValues;
            $table[] = sprintf("%'-{$totalSize}s", '');
        }

        return $table;
    }
}
