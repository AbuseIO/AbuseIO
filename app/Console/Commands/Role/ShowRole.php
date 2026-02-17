<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Role;
use Illuminate\Console\Command;

class ShowRole extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:show {role : Use the id or the name for a role to show it.}
                                      {--json : Output the role in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a role based on the provided ID or name';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputOptionArgument = $this->argument('role');
        $role = Role::select('id', 'name', 'description')
            ->where('id', $inputOptionArgument)
            ->orWhere('name', $inputOptionArgument)
            ->first();

        if (!$role) {
            $this->error('No matching role was found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($role->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Id', $role->id],
                    ['Name', $role->name],
                    ['Description', $role->description],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
