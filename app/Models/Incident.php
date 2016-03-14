<?php

namespace AbuseIO\Models;

/**
 * Class Incident
 * @package AbuseIO\Models
 * @property string $source
 * @property string $source_id
 * @property string $ip
 * @property string $domain
 * @property string $uri
 * @property string $class
 * @property string $type
 * @property integer $timestamp
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
    public $uri;

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
     * Validation rules for this model being created
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
            'uri'                   => 'sometimes|stringorboolean|uri',
            'timestamp'             => 'required|int|timestamp',
            'class'                 => 'required|abuseclass',
            'type'                  => 'required|abusetype',
            'information'           => 'required|json',
        ];

        return $rules;
    }
}
