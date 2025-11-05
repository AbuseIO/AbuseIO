<?php

namespace AbuseIO\Console\Commands\App;

use AbuseIO\Console\Commands\ExitCodeHooks;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Class InstallationID.
 */
class InstallationID extends Command
{
    use ExitCodeHooks;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'app:id
                            {--show : Simply display the key instead of modifying files. }
                            {--force : Force to overwrite the non-Default key with a newly generated one. }
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the AbuseIO installation ID (required for downstreaming!)';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): int
    {
        if ($this->option('show')) {
            $this->line('<comment>'.$this->laravel['config']['app.id'].'</comment>');

            return $this->getSuccessExitCode();
        }

        if ($this->laravel['config']['app.id'] != 'DEFAULT' &&
            empty($this->option('force'))
        ) {
            $this->info('You already have an installation ID. Not changing the ID unless --force is used');

            return $this->getInvalidOptionExitCode();
        }

        if ($this->laravel['config']['app.id'] != 'DEFAULT' &&
            !empty($this->option('force'))
        ) {
            $this->warn(
                'Changing your installation ID will break your parent relation to any child instance'.PHP_EOL.
                'this is receiving an AbuseIO downstream from you. Doing so will prevent you from  '.PHP_EOL.
                'receiveing updates from these instances!'.PHP_EOL.
                'In nearly all operations you should never change the installation ID, unless you are'.PHP_EOL.
                'very sure you are not using any kind of communication with other AbuseIO installations'
            );

            if (!$this->confirm('Do you wish to continue? [y|N]')) {
                return $this->getInvalidOptionExitCode();
            }
        }

        $id = Str::uuid()->toString();

        $path = base_path('.env');

        $replaces = 0;
        if (file_exists($path)) {
            file_put_contents(
                $path,
                str_replace(
                    'APP_ID='.$this->laravel['config']['app.id'],
                    'APP_ID='.$id,
                    file_get_contents($path),
                    $replaces
                )
            );
        } else {
            $this->error('Unable to set Application key becayse the .env file is not present');

            return $this->getSaveFailedExitCode();
        }

        if ($replaces === 0) {
            $this->error('Unable to set Application key into the .env file, because the variable is not configured');

            return $this->getSaveFailedExitCode();
        }

        $this->laravel['config']['app.id'] = $id;

        $this->info("Application ID [$id] set successfully.");

        return $this->getSuccessExitCode();
    }
}
