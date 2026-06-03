<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Brand;
use Illuminate\Console\Command;

class ListBrand extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:list {--filter= : Filters brands on name}
                                       {--json : Output the list in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a list of all brands or listed brands based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputOptionFilter = $this->option('filter');
        $brandQuery = Brand::select('id', 'name', 'company_name');

        if (!$inputOptionFilter) {
            $brands = $brandQuery->get();
        } else {
            $brands = $brandQuery->where('name', 'like', "%{$inputOptionFilter}%")->get();
        }

        if ($brands->isEmpty()) {
            $this->error('No brands found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($brands->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Id', 'Name', 'Company name'],
                $brands->map(function ($brand) {
                    return [
                        'Id' => $brand->id,
                        'Name' => $brand->name,
                        'Company name' => $brand->company_name,
                    ];
                })->toArray()
            );
        }

        return $this->getSuccessExitCode();
    }
}
