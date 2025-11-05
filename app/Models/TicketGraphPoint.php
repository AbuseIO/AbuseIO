<?php

namespace AbuseIO\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketGraphPoint extends Model
{
    use HasFactory;
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

    public static function getDistinctFiltersFor($column)
    {
        return self::distinct($column)->pluck($column, $column)->toArray();
    }

    public static function getStatistics($lifecycle)
    {
        $result = [];

        foreach (['year', 'month', 'week', 'day'] as $period) {
            $timeString = sprintf('1 %s ago', $period);
            $after = date('Y-m-d', strtotime($timeString));

            $result[$period] = self::where('day_date', '>=', $after)
                ->where('lifecycle', '=', $lifecycle)
                ->orderBy('day_date', 'desc')
                ->get()->sum('count');
        }

        return $result;
    }

    public static function getNewDataPointsForYesterday()
    {
        return self::where('day_date', '=', Carbon::yesterday()->toDateString())
            ->where('lifecycle', '=', 'created_at')->get();
    }

    public static function getTouchedDataPointsForYesterday()
    {
        return self::where('day_date', '=', Carbon::yesterday()->toDateString())
            ->where('lifecycle', '=', 'updated_at')->get();
    }

    public static function createNewWithDataForYesterday($data)
    {
        return self::createWithDataDateAndLifecycle($data, Carbon::yesterday(), 'created_at');
    }

    public static function createTouchedDataForYesterday($data)
    {
        return self::createWithDataDateAndLifecycle($data, Carbon::yesterday(), 'updated_at');
    }

    private static function createWithDataDateAndLifecycle($data, Carbon $date, $lifecycle)
    {
        return self::makeWithDataDateAndLifecycle($data, $date, $lifecycle)->save();
    }

    private static function makeWithDataDateAndLifecycle($data, Carbon $date, $lifecycle)
    {
        return new self([
            'count'          => $data->cnt,
            'class'          => $data->class_id,
            'type'           => $data->type_id,
            'status'         => $data->status_id,
            'contact_status' => $data->contact_status_id,
            'lifecycle'      => $lifecycle,
            'day_date'       => $date->toDateString(),
        ]);
    }

    public static function getSeriesByClass($class)
    {
        return [
            'legend' => $class,
            'data'   => self::getDataForLifecycleClass('created_at', $class),
        ];
    }

    public static function __callStatic($name, $arguments)
    {
        $params = array_reverse(
            explode('_', Str::snake($name))
        );

        if (!in_array('lifecycle', $params)) {
            return  parent::__callStatic($name, $arguments);
        }

        $arguments = array_reverse($arguments);

        /*
         * @var string $lifecycle
         * @var string $from
         * @var string $till
         **/

        foreach ($arguments as $key => $argument) {
            ${$params[$key]} = $argument;
        }

        $qb = self::getQueryBuilder($lifecycle);

        if (!empty($from)) {
            $qb->where('day_date', '>=', $from);
        }
        if (!empty($till)) {
            $qb->where('day_date', '<=', $till);
        }

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
            'data'   => self::transformToEChart($dataPoints),
        ];
    }

    private static function resolveLegend($lifecycle, $validScopes)
    {
        if (empty($validScopes)) {
            if ($lifecycle === 'created_at') {
                return trans('misc.total_new_by_day');
            }

            return trans('misc.total_touched_by_day');
        }

        $legend = [];

        foreach ($validScopes as $scope) {
            $legend[] = trans('misc.scope_'.$scope);
        }

        return trans('misc.scope_total').' '.trans('misc.lifecycle_'.$lifecycle).' '.trans('misc.scope_by').' '.implode(', ', $legend);
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

    public static function getFiltersForLifecycle()
    {
        return [
            'created_at' => ucfirst(trans('misc.lifecycle_created_at')),
            'updated_at' => ucfirst(trans('misc.lifecycle_updated_at')),
        ];
    }

    public static function transformToEchart($dataPoints)
    {
        $result = [];

        foreach ($dataPoints as $date => $count) {
            $carbon = Carbon::createFromFormat('Y-m-d', $date);
            $result[] = [$carbon->year, $carbon->month - 1, $carbon->day, (int) $count];
        }

        return $result;
    }

    public static function forwardCallToApi($methodName, $params)
    {
        return forward_static_call_array([self::class, $methodName], $params);
    }
}
