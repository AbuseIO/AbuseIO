<?php

namespace AbuseIO\Models;

use Illuminate\Support\Str;
use Lang;

/**
 * Class Incident.
 *
 * @property string $source
 * @property string $source_id
 * @property string $ip
 * @property string $domain
 * @property string $class
 * @property string $type
 * @property int    $timestamp
 * @property string $information
 * @property string $remote_api_token
 * @property int    $remote_ticket_id
 * @property string $remote_api_url
 * @property string $remote_ash_link
 */
class Incident
{
    /**
     * @var
     */
    public $source;

    /**
     * @var
     */
    public $source_id;

    /**
     * @var
     */
    public $ip;

    /**
     * @var
     */
    public $domain;

    /**
     * @var
     */
    public $timestamp;

    /**
     * @var
     */
    public $class;

    /**
     * @var
     */
    public $type;

    /**
     * @var
     */
    public $information;

    /**
     * @var
     */
    public $remote_api_token;

    /**
     * @var
     */
    public $remote_api_url;

    /**
     * @var
     */
    public $remote_ticket_id;

    /**
     * @var
     */
    public $remote_ash_link;

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * creates a new Incident object.
     *
     * @param array $values
     *
     * @return Incident
     */
    public static function create($values = [])
    {
        $fields = [
            'source', 'source_id', 'ip',
            'domain', 'timestamp', 'class',
            'type', 'information', 'remote_api_url',
            'remote_api_token', 'remote_ticket_id',
            'remote_ash_link',
        ];

        $incident = new self();
        if (!empty($values)) {
            foreach ($fields as $field) {
                if (array_key_exists($field, $values)) {
                    if (Str::is('timestamp', $field) && !is_int($values[$field])) {
                        $incident->$field = intval($values[$field]);
                    } else {
                        $incident->$field = $values[$field];
                    }
                }
            }
        }

        return $incident;
    }

    /**
     * @param Event $event
     *
     * @return Incident
     */
    public static function fromEvent(Event $event)
    {
        $incident = new self();

        $ticket = Ticket::find($event->ticket_id);

        // fill the fields
        $incident->source = $event->source;
        $incident->source_id = false; // seems to be the default setting
        $incident->ip = $ticket->ip;
        $incident->domain = $ticket->domain;
        $incident->timestamp = $event->timestamp;
        $incident->class = $ticket->class_id;
        $incident->type = $ticket->type_id;
        $incident->information = $event->information;
        $incident->remote_api_url = route('api.v1.tickets.index');
        $incident->remote_api_token = $ticket->api_token;
        $incident->remote_ticket_id = $ticket->id;
        $incident->remote_ash_link =
            route(
                'ash.show',
                [
                    'ticketID' => $ticket->id,
                    'token'    => $ticket->ash_token_ip,
                ]
            );

        return $incident;
    }

    /**
     * Validation rules for this model being created.
     *
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'source'      => 'required|string',
            'source_id'   => 'sometimes|stringorboolean',
            'ip'          => 'required|ip',
            'domain'      => 'sometimes|stringorboolean|domain',
            'timestamp'   => 'required|timestamp',
            'class'       => 'required|abuseclass',
            'type'        => 'required|abusetype',
            'information' => 'required|json',
        ];

        return $rules;
    }

    /**
     * Sanitize Model data, allow for classifications aliases.
     */
    private function sanitize()
    {
        $config = config('main.classifications');
        $classifications = array_keys(Lang::get('classifications'));

        // the incident class is not known
        if (!in_array($this->class, $classifications)) {
            // do we have an alias for the current classification,
            // if so replace it with the alias,
            // if not use the default
            if (in_array($this->class, array_keys($config['aliases']))) {
                $this->class = $config['aliases'][$this->class];
            } else {
                $this->class = $config['default'];
            }
        }
    }

    /**
     * Add toArray method manually as this is not a SQL model.
     */
    public function toArray()
    {
        $this->sanitize();

        return json_decode(json_encode($this), true);
    }
}
