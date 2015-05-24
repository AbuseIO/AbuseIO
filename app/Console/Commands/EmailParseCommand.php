<?php namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use PhpMimeMailParser\Attachment;
use PhpMimeMailParser\Parser;
use Config;

class EmailParseCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'email:parse';

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
        // read from stdin
        $fd = fopen("php://stdin", "r");
        $rawEmail = "";
        while (!feof($fd)) {
            $rawEmail .= fread($fd, 1024);
        }
        fclose($fd);

        // parse it
        $parser = new Parser();
        $parser->setText($rawEmail);

        // Store the raw e-mail into a EML file if the archiving is enabled
        if(Config::get('main.emailparser.store_evidence') === true) {
            $filesystem = new Filesystem;

            $path = storage_path() . '/mailarchive/' . date('Ymd') . '/';
            if (!is_dir($path)) {
                if(!$filesystem->makeDirectory($path)) {
                    // TODO Log something great
                }
            }

            if(empty($parser->parts[1]['headers']['message-id'])) {
                $file = rand(10,10) . '.eml';
            } else {
                $file = substr($parser->parts[1]['headers']['message-id'],1,-1) . '.eml';
                $file = preg_replace('/[^a-zA-Z0-9_\.]/', '_', $file);
            }

            if ( $filesystem->put($path . $file, $rawEmail) === false ) {
                // TODO Log something great
                // TODO Bounce to admin
                echo $path . $file . PHP_EOL;
            }

        }



        // Start with detecting valid ARF e-mail
        $attachments = $parser->getAttachments();
        $arf = [ ];
        foreach ($attachments as $attachment) {
            if($attachment->contentType == 'message/feedback-report') {
                $arf['report'] = $attachment->getContent();
            }
            if($attachment->contentType == 'message/rfc822') {
                $arf['evidence'] = $attachment->getContent();
            }
            if($attachment->contentType == 'text/plain') {
                $arf['message'] = $attachment->getContent();
            }
        }


	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			//['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			//['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
