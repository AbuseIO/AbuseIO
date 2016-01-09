<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Contact;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Brand
 */
class ListCommand extends AbstractListCommand
{

    protected $filterArguments = ["name"];

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id', "Name", "Email", "Api host", "Api key"];

    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $contact  \AbuseIO\Models\Contact|null */
        foreach ($list as $contact) {
            $result[] = [$contact->id, $contact->name, $contact->email, $contact->api_host, $contact->api_key];
        }
        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Contact::where('name', 'like', "%{$filter}%")->get();
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return Contact::all();
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "contact";
    }
}

