<?php

namespace AbuseIO\Console\Commands\Brand;

use Illuminate\Console\Command;
use AbuseIO\Models\Brand;
use Carbon;

class ShowCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'brand:show
                            {--id= : Use the id to show details }
                            {--name= : Use the name show details }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows the details of an account';

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
        if (empty($this->option('id')) && empty($this->option('name'))) {
            $this->warn('Pass a name or id argument or try --help');
            return false;
        }
        /* @var $brand  \AbuseIO\Models\Brand|null */
        $brand = $this->findByNameOrId($this->option("name"), $this->option("id"));

        if (null !== $brand) {
            $this->table([], $this->transformBrandToTableBody($brand));
        } else {
            $this->warn("No matching brand was found.");
        }
        return true;
    }


    /**
     * @param Brand $brand
     * @return array
     */
    private function transformBrandToTableBody(Brand $brand)
    {
        return  [
            ["Id", $brand->id],
            ["Name", $brand->name],
            ["Company name", $brand->company_name],
            ["Introduction text", $brand->introduction_text]
        ];
    }

    /**
     * @param $name
     * @param $id
     * @return Account|null
     */
    private function findByNameOrId($name, $id)
    {
        if ($name) {
            return $this->findByName($name);
        }
        return $this->findById($id);
    }


    /**
     * @param $id
     * @return Brand|null
     */
    private function findById($id)
    {
        return Brand::find($id);
    }

    /**
     * @param $name
     * @return Brand|null
     */
    private function findByName($name)
    {
        return Brand::where("name", "like", "%".$name."%")->first();
    }
}
