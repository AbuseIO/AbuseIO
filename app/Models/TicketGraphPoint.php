<?php

namespace AbuseIO\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketGraphPoint extends Model
{
    /**
     * Class TicketGraphPoint.
     *
     * @property int id
     * @property date day_date
     * @property string class
     * @property string type
     * @property string status
     * @property int count
     * @property string lifecycle
     */
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'day_date',
        'class',
        'type',
        'status',
        'count',
        'lifecycle',
    ];

    public static function getStatistics($lifecycle)
    {
        $today = date('Y-m-d');
        $oneYearAgo = date('Y-m-d', strtotime($today.' -1 year'));

        $collection = self::where('day_date', '>=', $oneYearAgo)
            ->where('lifecycle', '=', $lifecycle)
            ->groupBy('day_date')
            ->orderBy('day_date', 'desc')
            ->get();

        return [
            "year" => $collection->take(365)->sum('count'),
            "month" => $collection->take(30)->sum('count'),
            "day" => $collection->take(1)->sum('count')
        ];
    }

    public static function getNewDataPointsForToday()
    {
        return self::where('day_date', '=', Carbon::now()->toDateString())
            ->where('lifecycle', '=', 'created_at')->get();
    }

    public static function getTouchedDataPointsForToday()
    {
        return self::where('day_date', '=', Carbon::now()->toDateString())
            ->where('lifecycle', '=', 'updated_at')->get();
    }

    public static function createNewWithDataForToday($data)
    {
        return self::createWithDataDateAndLifecycle($data, Carbon::now(), 'created_at');
    }

    public static function createTouchedDataForToday($data)
    {
        return self::createWithDataDateAndLifecycle($data, Carbon::now(), 'updated_at');
    }

    private static function createWithDataDateAndLifecycle($data, Carbon $date, $lifecycle)
    {
        return self::makeWithDataDateAndLifecycle($data, $date, $lifecycle)->save();
    }

    private static function makeWithDataDateAndLifecycle($data, Carbon $date, $lifecycle)
    {
        return new self([
            'count'             => $data->cnt,
            'class'             => $data->class_id,
            'type'              => $data->type_id,
            'status'            => $data->status_id,
            'contact_status'    => $data->contact_status_id,
            'lifecycle'         => $lifecycle,
            'day_date'          => $date->toDateString(),
        ]);
    }

    public static function getTotalTouchedSeries()
    {
        return [
            'legend' => 'total touched',
            'data'   => self::getDataWithLifecycle('updated_at'),
        ];
    }
    
    public static function getSeriesByClass($class)
    {
        return [
            'legend' => $class,
            'data' => self::getDataForLifecycleClass('created_at', $class)
        ];
    }

    public static function __callStatic($name, $arguments)
    {
        $params = array_reverse(
            explode("_", snake_case($name))
        );

        if (!in_array('lifecycle', $params)) {
            return  parent::__callStatic($name, $arguments);
        }

        $arguments = array_reverse($arguments);

        /** @var string $lifecycle **/

        foreach ($arguments as $key => $argument) {
            $$params[$key] = $argument;
        }

        $qb = self::getQueryBuilder($lifecycle);

        $validScopes = [];

        foreach (['class', 'status', 'type'] as $scope) {
            if (isset($$scope)) {
                self::scope($scope, $$scope, $qb);
                $validScopes[] = $scope;
            }
        }

        $dataPoints = [];
        foreach ($qb->get() as $data) {
            $dataPoints[$data->day_date] = $data->count;
        }

        return [
                'legend' => self::resolveLegend($lifecycle, $validScopes),
                'data' => $dataPoints,
            ];
    }
    
    private static function resolveLegend($lifecycle, $validScopes)
    {
        if (empty($validScopes)) {
            if ($lifecycle === 'created_at') {
                return 'Total new';
            }
            return 'Total touched';
        }
    }

    private static function scope($name, $value, $qb)
    {
        $value = is_string($value) ? [$value] : $value;
        $qb->where(function ($query) use ($value, $name) {
            foreach ($value as $valueItem) {
                $query->orWhere($name, '=', $valueItem);
            }
        });

        $qb->groupBy($name);
    }

    private static function getQueryBuilder($lifecycle)
    {
        return DB::table('ticket_graph_points')
        ->selectRaw('day_date, sum(count) as count')
        ->where('lifecycle', '=', $lifecycle)
        ->groupBy('day_date')
        ->orderBy('day_date');
    }
    
    public function getCompoundStatistics()
    {
    }
}
