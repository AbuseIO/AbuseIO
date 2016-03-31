<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ShowCommand.
 */
class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}.
     */
    protected function getAsNoun()
    {
        return 'netblock';
    }

    /**
     * {@inherit docs}.
     */
    protected function getAllowedArguments()
    {
        return ['id', 'name'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getFields()
    {
        return ['first_ip', 'last_ip', 'description', 'enabled'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getCollectionWithArguments()
    {
        /* @var $collection  \Illuminate\Support\Collection|null */
        $collection = $this->findByContactName($this->argument('netblock'));
        if ($collection === null) {
            $collection = $this->findByFirstIp($this->argument('netblock'));
            if ($collection->count() === 0) {
                $collection = $this->findByLastIp($this->argument('netblock'));
            }
        }

        return $collection;
    }

    /**
     * {@inherit docs}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'netblock',
                InputArgument::REQUIRED,
                'Use the firstIp, lastIp or contact name for a netblock to show it.'
            ),
        ];
    }

    /**
     * @param $name
     *
     * @return $collection \Illuminate\Support\Collection||null
     */
    private function findByContactName($name)
    {
        $collection = null;
        $contact = Contact::where('name', '=', $name)->first();
        if (null !== $contact) {
            $collection = $contact->netblocks; //->first();
        }

        return $collection;
    }

    /**
     * @param $ip
     *
     * @return $collection \Illuminate\Support\Collection||null
     */
    private function findByFirstIp($ip)
    {
        return $this->findByIp('first_ip', $ip);
    }

    /**
     * @param $ip
     *
     * @return $collection \Illuminate\Support\Collection||null
     */
    private function findByLastIp($ip)
    {
        return $this->findByIp('last_ip', $ip);
    }

    /**
     * @param $field
     * @param $ip
     *
     * @return null $collection \Illuminate\Support\Collection||null
     */
    private function findByIp($field, $ip)
    {
        $collection = null;
        $allowedFields = ['first_ip', 'last_ip'];

        if (in_array($field, $allowedFields)) {
            $filter = '%'.$ip.'%';
            $collection = Netblock::where($field, 'like', $filter)->get(); //->first();
        }

        return $collection;
    }

    /**
     * @param $model
     *
     * @return array
     */
    protected function transformObjectToTableBody($model)
    {
        $result = [];
        foreach ($model->getAttributes() as $key => $value) {
            $heading = ucfirst(str_replace('_', ' ', $key));
            $result[] = [$heading, $value];
        }
        $result[] = ['contact name', $model->contact->name];

        return $result;
    }
}
