<?php

namespace AbuseIO\Console\Commands\Permission;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Permission;
use Illuminate\Console\Command;

class ListPermission extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:list {--filter= : Filter permissions by id or name}
                                            {--json : Output the permissions in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all permissions or listed permissions based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputFilter = $this->option('filter');
        $permissionSelectQuery = Permission::select('id', 'name', 'description');

        if ($inputFilter) {
            $permissions = $permissionSelectQuery->where('id', $inputFilter)
                ->orWhere('name', 'like', '%' . $inputFilter . '%')
                ->get();
        } else {
            $permissions = $permissionSelectQuery->get();
        }

        if (empty($permissions) || $permissions->count() == 0) {
            $this->error('No permissions found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($permissions->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Id', 'Name', 'Description'],
                $permissions->map(function ($permission) {
                    return [
                        $permission->id,
                        $permission->name,
                        $permission->description,
                    ];
                })->toArray()
            );
        }


        return $this->getSuccessExitCode();
    }
}
