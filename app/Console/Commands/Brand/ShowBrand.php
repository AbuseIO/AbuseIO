<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Brand;
use Illuminate\Console\Command;

class ShowBrand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:show {brand : The ID or name of the brand to show}
                                       {--json : Output the brand in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a brand based on the provided ID or name';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputBrandArgument = $this->argument('brand');
        $brand = Brand::select('id', 'name', 'company_name', 'introduction_text')
            ->where('name', 'like', '%' . $inputBrandArgument . '%')
            ->orWhere('id', $inputBrandArgument)
            ->first();

        if (!$brand) {
            $this->error('No matching brand was found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($brand->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Id', $brand->id],
                    ['Name', $brand->name],
                    ['Company name', $brand->company_name],
                    ['Introduction text', $brand->introduction_text],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
