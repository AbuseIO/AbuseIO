<?php

namespace AbuseIO\Models;

/**
 * Class Incident.
 *
 * @property string $source
 * @property string $source_id
 * @property string $ip
 * @property string $domain
 * @property string $class
 * @property string $type
 * @property int $timestamp
 * @property string $information;
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

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'source'                => 'required|string',
            'source_id'             => 'sometimes|stringorboolean',
            'ip'                    => 'required|ip',
            'domain'                => 'sometimes|stringorboolean|domain',
            'timestamp'             => 'required|int|timestamp',
            'class'                 => 'required|abuseclass',
            'type'                  => 'required|abusetype',
            'information'           => 'required|json',
        ];

        return $rules;
    }

    /**
     * Add toArray method manually as this is not a SQL model.
     */
    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
