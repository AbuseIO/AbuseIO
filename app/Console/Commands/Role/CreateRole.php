<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\StoreRoleRequest;
use AbuseIO\Models\Role;
use Illuminate\Console\Command;
use Validator;

class CreateRole extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:create 
                            {name : Name of the role} 
                            {description : Description of the role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new role in the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentName = $this->argument('name');
        $inputArgumentDescription = $this->argument('description');

        $role = Role::make([
            'name' => $inputArgumentName,
            'description' => $inputArgumentDescription,
        ]);

        $validator = Validator::make($role->toArray(), new StoreRoleRequest()->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if (!$role->save()) {
            $this->error('Failed to save the role.');
            return $this->getSaveFailedExitCode();
        }

        $this->info('Role created successfully.');
        return $this->getSuccessExitCode();
    }
}
