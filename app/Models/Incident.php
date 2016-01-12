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
    var $source;
    var $source_id;
    var $ip;
    var $domain;
    var $uri;
    var $class;
    var $type;
    var $timestamp;
    var $information;
}
