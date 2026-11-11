<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\UpdateRoleRequest;
use AbuseIO\Models\Role;
use Illuminate\Console\Command;
use Validator;

class EditRole extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:edit {id : Role id to edit}
                            {--name= : Name for role}
                            {--description= : Description for the role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edits an existing role';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $inputOptionName = $this->option('name');
        $inputOptionDescription = $this->option('description');

        $role = Role::where('id', $inputArgumentId)->first();

        if (!$role) {
            $this->error('Unable to find role with this criteria.');
            return $this->getNotFoundExitCode();
        }

        $role->name = $inputOptionName ?? $role->name;
        $role->description = $inputOptionDescription ?? $role->description;

        $request = new UpdateRoleRequest();
        $request = $request->merge(['id' => $role->id]);
        $validator = Validator::make($role->toArray(), $request->rules());

        if ($validator->fails()) {
            $this->error('Validation error: ' . implode(' ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if (!$role->update()) {
            $this->error('Failed to save the role.');
            return $this->getSaveFailedExitCode();
        }

        $this->info('The role has been updated.');
        return $this->getSuccessExitCode();
    }
}
