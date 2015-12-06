<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use AbuseIO\Models\User;
use Carbon;

class ShowCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'user:show
                            {--user= : Use the user email or id to show user details }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows the details of an user';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['User ID', 'User', 'First Name', 'Last Name', 'Language', 'Disabled', 'Created', 'Last modified'];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = ['id', 'email', 'first_name', 'last_name', 'locale', 'disabled', 'created_at', 'updated_at'];

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (empty($this->option('user'))) {
            $this->warn('no email or id argument was passed, try help');
            return false;
        }

        $user = false;
        if (!is_object($user)) {
            $user = User::where('email', $this->option('user'))->first();
        }

        if (!is_object($user)) {
            $user = User::find($this->option('user'));
        }

        if (!is_object($user)) {
            $this->error('Unable to find user with this criteria');
            return false;
        }

        $table = [ ];
        $counter = 0;
        foreach (array_combine($this->headers, $this->fields) as $header => $field) {
            $counter++;
            $table[$counter][] = $header;

            if ($header == 'Disabled') {
                $table[$counter][] = (boolean)$user->$field ? 'YES' : 'NO';
            } else {
                $table[$counter][] = (string)$user->$field;
            }
        }

        $this->table(['User Setting', 'User Value'], $table);

        return true;
    }
}
