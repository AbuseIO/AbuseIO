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
    public $class;

    /**
     * @var
     */
    public $type;

    /**
     * @var
     */
    public $timestamp;

    /**
     * @var
     */
    public $information;
}
