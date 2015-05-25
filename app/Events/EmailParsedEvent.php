<?php namespace AbuseIO\Events;

use AbuseIO\Events\Event;

use Illuminate\Queue\SerializesModels;

class EmailParsedEvent extends Event {

	use SerializesModels;
    public $parser;
    public $rawEmail;
    public $arfEmail;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($parser, $rawEmail, $arfEmail)
	{
		$this->parser = $parser;
        $this->rawEmail = $rawEmail;
        $this->arfEmail = $arfEmail;
	}

}
