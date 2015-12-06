<?php

namespace AbuseIO\Console\Commands\Permission;

use Illuminate\Console\Command;
use AbuseIO\Models\Permission;
use Carbon;

class ListCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'permission:list
                            {--filter : Applies a filter on the permission name }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available permissions';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['ID', 'Name', 'Description'];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = ['id', 'name', 'description'];

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
        if (!empty($this->option('filter'))) {
            $permissions = Permission::where('name', 'like', "%{$this->option('filter')}%")->get($this->fields);
        } else {
            $permissions = Permission::all($this->fields);
        }

        $this->table($this->headers, $permissions);
    }
}
