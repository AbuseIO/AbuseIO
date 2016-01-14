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
 * @property string type
 * @property string timestamp
 * @property string information;
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

    /**
     * Validation rules for this model being created
     *
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'source'                => 'required|string',
            'source_id'             => 'sometimes|string',
            'ip'                    => 'required|integer',
            'domain'                => 'sometimes|string',
            'uri'                   => 'sometimes|uri',
            'timestamp'             => 'required|timestamp',
            'class_id'              => 'required|abuseclass',
            'type_id'               => 'required|abusetype',
            'information'           => 'required|json',
        ];

        return $rules;
    }
}
