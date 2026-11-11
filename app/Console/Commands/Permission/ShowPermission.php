<?php

namespace AbuseIO\Console\Commands\Permission;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Permission;
use Illuminate\Console\Command;

class ShowPermission extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:show {permission : Use the id for a permission to show it.}
                                            {--json : Output the permission in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a permission based on the provided ID';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentPermission = $this->argument('permission');
        $permission = Permission::select('id' , 'name', 'description')->where('id', $inputArgumentPermission)->first();

        if (!$permission) {
            $this->error('No matching permission was found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($permission->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Id', $permission->id],
                    ['Name', $permission->name],
                    ['Description', $permission->description],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
