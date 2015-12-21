<?php

namespace AbuseIO\Console\Commands\Domain;

use Illuminate\Console\Command;
use AbuseIO\Models\Domain;
use Carbon;

class ShowCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'domain:show
                            {--id= : Use the id to show details }
                            {--name= : Use the name show details }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows the details of an domain';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers =['Id', 'Name', 'Contact', 'Enabled','Created', 'Updated', 'Deleted'];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = ['first_ip', 'last_ip','description', 'enabled']; // don't know how to do the field contact conform this syntax.

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
     * @return boolean
     */
    public function handle()
    {
        if (empty($this->option('id')) && empty($this->option("name"))) {
            $this->warn('no --id or --name argument was passed, try --help');
            return false;
        }
        /* @var $domain \AbuseIO\Models\Domain|null */
        $domain = Domain::find($this->option("id")) ?:
                    $this->findByName($this->option("name"));


        if (null !== $domain) {
            $this->table($this->headers, $this->transformDomainToTableBody($domain));
        } else {
            $this->warn("No matching domain where found.");
        }
        return true;
    }

    /**
     * @param Netblock $netblock
     * @return array
     */
    private function transformDomainToTableBody(Domain $domain)
    {
        return  [[
            $domain->id,
            $domain->name,
            $domain->contact->name,
            castBoolToString($domain->enabled),
            $domain->created_at,
            $domain->updated_at,
            $domain->deleted_at
        ]];
    }


    /**
     * @param $name
     * @return Domain|null
     */
    private function findByName($name)
    {
        if (strlen($name) > 0) {
            return Domain::where("name", "like", '%' . $this->option("name"))->first();
        }
        return null;
    }
}
