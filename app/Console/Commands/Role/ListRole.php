<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Role;
use Illuminate\Console\Command;

class ListRole extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:list {--filter= : Filter by name}
                                      {--json : Output the list in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all roles or listed roles based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputOptionFilter = $this->option('filter');
        $roleSelectQuery = Role::select('id', 'name', 'description');

        if ($inputOptionFilter) {
            $roles = $roleSelectQuery->where('name', 'like', '%' . $inputOptionFilter . '%')->get();
        } else {
            $roles = $roleSelectQuery->get();
        }

        if (empty($roles) || $roles->count() === 0) {
            $this->info('No roles found matching the criteria.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($roles->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['ID', 'Name', 'Description', 'Permissions'],
                $roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'description' => $role->description,
                        'permissions' => $role->permissions()->count(),
                    ];
                })->toArray()
            );
        }

        return $this->getSuccessExitCode();
    }
}
