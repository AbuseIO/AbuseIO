<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Brand;
use Illuminate\Console\Command;

class DeleteBrand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:delete {id : The ID of the brand to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a brand from the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $brand = Brand::where('id', $inputArgumentId)->first();

        if (!$brand) {
            $this->error('Unable to find brand with this ID.');
            return $this->getNotFoundExitCode();
        }

        if ($brand->delete()) {
            $this->info('The brand has been deleted from the system.');
            return $this->getSuccessExitCode();
        } else {
            $this->error('Failed to delete the brand.');
            return $this->getDeleteFailedExitCode();
        }
    }
}
