<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Models\Brand;
use Illuminate\Console\Command;
use Carbon;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Brand
 */
class ListCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'brand:list
                            {--filter= : Applies a filter on the account name }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available brands';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id', 'Name', 'Company name'];

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
        if (!empty($this->option('filter'))) {
            $brandList = Brand::where('name', 'like', "%{$this->option('filter')}%")->get();
        } else {
            $brandList = Brand::all();
        }

        if (count($brandList) === 0) {
            $this->error("No brand found for given filter.");
        } else {
            $this->table(
                $this->headers,
                $this->transformAccountListToTableBody($brandList)
            );
        }
        return true;
    }


    /**
     * @param $brandList
     * @return array
     */
    public function transformAccountListToTableBody($brandList)
    {
        $result = [];
        /* @var $brand  \AbuseIO\Models\Brand|null */
        foreach ($brandList as $brand) {
            $result[] = [$brand->id, $brand->name, $brand->company_name];
        }
        return $result;
    }
}
