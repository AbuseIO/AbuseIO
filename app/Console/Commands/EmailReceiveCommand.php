<?php namespace AbuseIO\Console\Commands;

use AbuseIO\Commands\EmailProcess;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputOption;
use Log;
Use Events;
Use Uuid;
Use Carbon;
Use Queue;

class EmailReceiveCommand extends Command
{

    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'email:receive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses an incoming email into abuse events.';

    /**
     * Create a new command instance.
     *
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
    public function fire()
    {

        Log::info('MDA is called to receive an incoming e-mail');

        // Read from stdin (should be piped from cat or MDA)
        $fd = fopen("php://stdin", "r");
        $rawEmail = "";
        while (!feof($fd)) {
            $rawEmail .= fread($fd, 1024);
        }
        fclose($fd);

        $filesystem = new Filesystem;
        $datefolder = Carbon::now()->format('Ymd');
        $path = storage_path() . '/mailarchive/' . $datefolder . '/';
        $file = Uuid::generate(4) . '.eml';
        $filename = $path . $file;

        if (!$filesystem->isDirectory($path)) {
            // If a datefolder does not exist, then create it or die trying
            if (!$filesystem->makeDirectory($path)) {
                Log::error('Unable to create directory: ' . $path);
                $this->exception($rawEmail);
            }
        }

        if ($filesystem->isFile($path . $file)) {
            Log::error('File aready exists: ' . $filename);
            $this->exception($rawEmail);
        }

        if ($filesystem->put($path . $file, $rawEmail) === false) {
            Log::error('Unable to write file: ' . $filename);

            $this->exception($rawEmail);
        }

        Log::info('Pushing incoming email into queue file: ' . $filename);

        $this->dispatch(new EmailProcess($filename));

        Log::info('MDA has successfully received the incoming e-mail');

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [ ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['debug', 'd', InputOption::VALUE_OPTIONAL, 'Enable debugging while pushing e-mail from CLI.', false],
        ];
    }

    /**
     * We've hit a snag, so we are gracefully killing ourselves after we contact the admin about it.
     *
     * @return mixed
     */
    protected function exception($rawEmail)
    {
        Log::error('Email parser is ending with errors. The received e-mail will be bounced to the admin for investigation');
        // TODO: send the rawEmail back to admin
        dd($rawEmail);
    }

}
