<?php namespace AbuseIO\Console\Commands;

use AbuseIO\Events\EmailParsedEvent;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use PhpMimeMailParser\Attachment;
use PhpMimeMailParser\Parser;
use Config;
use Log;
Use Event;

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
        // Read from stdin (should be piped from cat or MDA)
        $fd = fopen("php://stdin", "r");
        $rawEmail = "";
        while (!feof($fd)) {
            $rawEmail .= fread($fd, 1024);
        }
        fclose($fd);

        // Actually parse it
        $parsedMail = new Parser();
        $parsedMail->setText($rawEmail);

        // Ignore email from our own notification address to prevent mail loops
        // TODO: add

        // Store the raw e-mail into a EML file if the archiving is enabled
        if(Config::get('main.emailparser.store_evidence') === true) {
            $filesystem = new Filesystem;

            $path = storage_path() . '/mailarchive/' . date('Ymd') . '/';
            if (!$filesystem->isDirectory($path)) {
                if(!$filesystem->makeDirectory($path)) {
                    Log::error('Unable to create directory' . $path);
                    $this->exception($rawEmail);
                }
            }

            if(empty($parsedMail->parts[1]['headers']['message-id'])) {
                $file = rand(10,10) . '.eml';
            } else {
                $file = substr($parsedMail->parts[1]['headers']['message-id'],1,-1) . '.eml';
                $file = preg_replace('/[^a-zA-Z0-9_\.]/', '_', $file);
            }

            if($filesystem->isFile($path . $file)) {
                // No way an e-mail with the same message ID would arrive twice. So this is an error to rage quit for
                Log::error('Received duplicate e-mail with message ID: ' . $parsedMail->parts[1]['headers']['message-id']);
                $this->exception($rawEmail);
            }

            if ( $filesystem->put($path . $file, $rawEmail) === false ) {
                Log::error('Unable to write file: ' . $path . $file);
                $this->exception($rawEmail);
            }

        }

        // Start with detecting valid ARF e-mail
        $attachments = $parsedMail->getAttachments();
        $arfEmail = [ ];
        foreach ($attachments as $attachment) {
            if($attachment->contentType == 'message/feedback-report') {
                $arfEmail['report'] = $attachment->getContent();
            }
            if($attachment->contentType == 'message/rfc822') {
                $arfEmail['evidence'] = $attachment->getContent();
            }
            if($attachment->contentType == 'text/plain') {
                $arfEmail['message'] = $attachment->getContent();
            }
        }

        // Use parser mapping to see where to kick it to
        // Start using the quick method using the sender mapping
        $senderMap = Config::get('main.emailparser.sender_map');
        foreach ($senderMap as $regex => $mapping) {
            if (preg_match($regex, $parsedMail->getHeader('from'))) {
                $parser = $mapping;
                break;
            }
        }

        // If the quick method didnt work fall back to body mapping
        if (empty($parsedMail)) {
            $bodyMap = Config::get('main.emailparser.body_map');
            foreach ($bodyMap as $regex => $mapping) {
                if (preg_match($regex, $parsedMail->getMessageBody())) {
                    $parser = $mapping;
                    break;
                }
            }
        }

        // If we haven't figured out which parser we're going to use, we will never find out so another rage quit
        if (empty($parser)) {
            Log::error('Unable to handle message from: ' . $parsedMail->getHeader('from') . ' with subject: ' . $parsedMail->getHeader('subject'));
            $this->exception($rawEmail);
        } else {
            Log::info('Received message from: '. $parsedMail->getHeader('from') . ' with subject: ' . $parsedMail->getHeader('subject') . ' heading to parser: ' . $parser);
        }

        $response = Event::fire(new EmailParsedEvent($parser, $rawEmail, $arfEmail));
        // Handle parse results (did it fire?)

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
            // TODO: add debug option
			//['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
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
        // TODO: sent the rawEmail back to admin
        dd();
    }

}
