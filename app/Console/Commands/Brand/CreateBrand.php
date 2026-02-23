<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\StoreBrandRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use Illuminate\Console\Command;
use Validator;

class CreateBrand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:create 
                                {name : The name of the brand}
                                {company_name : The company name of the brand}
                                {introduction_text? : The introduction text of the brand}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new brand in the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentName = $this->argument('name');
        $inputArgumentCompany = $this->argument('company_name');
        $inputArgumentIntroduction = $this->argument('introduction_text') ?? '';

        $brand = Brand::make([
            'name' => $inputArgumentName,
            'company_name' => $inputArgumentCompany,
            'introduction_text' => $inputArgumentIntroduction,
            'logo' => Brand::getDefaultLogo(),
            'creator_id' => Account::getSystemAccount()->id
        ]);

        $validator = Validator::make($brand->toArray(), new StoreBrandRequest()->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if ($brand->save()) {
            $this->info('The brand has been created');
            return $this->getSuccessExitCode();
        } else {
            $this->error('Failed to save the brand.');
            return $this->getSaveFailedExitCode();
        }

    }
}
