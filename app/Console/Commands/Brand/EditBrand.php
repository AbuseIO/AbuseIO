<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\UpdateBrandRequest;
use AbuseIO\Models\Brand;
use Illuminate\Console\Command;
use Validator;

class EditBrand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:edit 
                                    {id : The ID of the brand to edit}
                                    {--name= : The new name of the brand}
                                    {--company_name= : The new company name of the brand} 
                                    {--introduction_text= : The new introduction text of the brand}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edits an existing brand';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $inputOptionName = $this->option('name');
        $inputOptionCompanyName = $this->option('company_name');
        $inputOptionIntroductionText = $this->option('introduction_text');

        $brand = Brand::where('id', $inputArgumentId)->first();

        if (!$brand) {
            $this->error('Unable to find brand with this criteria');
            return $this->getNotFoundExitCode();
        }

        $brand->name = $inputOptionName ?? $brand->name;
        $brand->company_name = $inputOptionCompanyName ?? $brand->company_name;
        $brand->introduction_text = $inputOptionIntroductionText ?? $brand->introduction_text;

        // unset the logo for validation.
        $brandData = $brand->toArray();
        unset($brandData['logo']);

        $request = new UpdateBrandRequest();
        $request->merge(['id' => $brand->id]);
        $validator = Validator::make($brandData, $request->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if ($brand->update()) {
            $this->info('The account has been updated successfully.');
            return $this->getSuccessExitCode();
        }

        $this->error('Failed to update the account.');
        return $this->getSaveFailedExitCode();

    }
}
