<?php

namespace AbuseIO\Console\Commands\Netblock;


use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Netblock;
use AbuseIO\Models\Contact;
use Symfony\Component\Console\Input\InputArgument;


class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "netblock";
    }

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["id", "name"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return ['first_ip', 'last_ip','description', 'enabled'];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        /* @var $collection  \Illuminate\Support\Collection|null */
        $collection  = $this->findByContactName($this->argument("netblock"));
        if ($collection === null) {
            $collection = $this->findByFirstIp($this->argument("netblock"));
            if ($collection->count() === 0) {
                $collection = $this->findByLastIp($this->argument("netblock"));
            }
        }

        return $collection;
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'netblock',
                InputArgument::REQUIRED,
                'Use the firstIp, lastIp or contact name for a netblock to show it.')
        ];
    }

    /**
     * @param $name
     * @return $collection  \Illuminate\Support\Collection||null
     */
    private function findByContactName($name)
    {
        $collection = null;
        $contact = Contact::where('name', "=", $name)->first();
        if (null !== $contact) {
            $collection = $contact->netblocks;//->first();
        }
        return $collection;
    }

    /**
     * @param $ip
     * @return $collection \Illuminate\Support\Collection||null
     */
    private function findByFirstIp($ip)
    {
        return $this->findByIp("first_ip", $ip);
    }

    /**
     * @param $ip
     * @return $collection \Illuminate\Support\Collection||null
     */
    private function findByLastIp($ip)
    {
        return $this->findByIp("last_ip", $ip);
    }

    /**
     * @param $ip
     * @return $collection \Illuminate\Support\Collection||null
     */
    private function findByIp($field, $ip)
    {
        $collection = null;
        $allowedFields = ["first_ip", "last_ip"];

        if (in_array($field, $allowedFields)) {
            $filter = '%' . $ip . '%';
            $collection = Netblock::where($field, "like", $filter)->get();//->first();
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
        $result[] = ["contact name", $model->contact->name];

        return $result;
    }
}



//
//use Illuminate\Console\Command;
//use AbuseIO\Models\Netblock;
//use AbuseIO\Models\Contact;
//use Carbon;
//
//class ShowCommand extends Command
//{
//
//    /**
//     * The console command name.
//     * @var string
//     */
//    protected $signature = 'netblock:show
//                            {--filter= : Use the contact email or ip_first or ip_last to show details }
//    ';
//
//    /**
//     * The console command description.
//     * @var string
//     */
//    protected $description = 'Shows the details of an netblock';
//
//    /**
//     * The headers of the table
//     * @var array
//     */
//    protected $headers =['Id', 'Contact', 'First IP', 'Last IP','Description', 'Enabled'];
//
//    /**
//     * The fields of the table / database row
//     * @var array
//     */
//    protected $fields = ['first_ip', 'last_ip','description', 'enabled']; // don't know how to do the field contact conform this syntax.
//
//    /**
//     * Create a new command instance.
//     * @return void
//     */
//    public function __construct()
//    {
//        parent::__construct();
//    }
//
//    /**
//     * Execute the console command.
//     *
//     * @return boolean
//     */
//    public function handle()
//    {
//        if (empty($this->option('filter'))) {
//            $this->warn('no email or ip argument was passed, try --help');
//            return false;
//        }
//        /* @var $netblock  \AbuseIO\Models\Netblock|null */
//        $netblock = $this->findByContactName($this->option("filter")) ?:
//                    $this->findByFirstIp($this->option("filter")) ?:
//                    $this->findByLastIp($this->option("filter"));
//
//
//        if (null !== $netblock) {
//            $this->table($this->headers, $this->transformNetblockToTableBody($netblock));
//        } else {
//            $this->warn("No matching netblocks where found.");
//        }
//        return true;
//    }
//
//    /**
//     * @param Netblock $netblock
//     * @return array
//     */
//    private function transformNetblockToTableBody(Netblock $netblock)
//    {
//        return  [[
//            $netblock->id,
//            $netblock->contact->name,
//            $netblock->first_ip,
//            $netblock->last_ip,
//            $netblock->description,
//            $netblock->enabled
//        ]];
//    }
//
//
//    /**
//     * @param $name
//     * @return Netblock|null
//     */
//    private function findByContactName($name)
//    {
//        $netblock = null;
//        $contact = Contact::where('name', "=", $this->option('filter'))->first();
//        if (null !== $contact) {
//            $netblock = $contact->netblocks->first();
//        }
//        return $netblock;
//    }
//
//    /**
//     * @param $ip
//     * @return Netblock|null
//     */
//    private function findByFirstIp($ip)
//    {
//        return $this->findByIp("first_ip", $ip);
//    }
//
//    /**
//     * @param $ip
//     * @return Netblock|null
//     */
//    private function findByLastIp($ip)
//    {
//        return $this->findByIp("last_ip", $ip);
//    }
//
//    /**
//     * @param $name
//     * @return Netblock|null
//     */
//    private function findByIp($field, $ip)
//    {
//        $netblock = null;
//        $allowedFields = ["first_ip", "last_ip"];
//
//        if (in_array($field, $allowedFields)) {
//            $filter = '%'.$this->option('filter').'%';
//            $netblock = Netblock::where($field, "like", $filter)->first();
//        }
//        return $netblock;
//    }
//}
