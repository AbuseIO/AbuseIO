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
        return self::makeWithDataDateAndLifcycle($data, $date, $lifecycle)->save();
    }

    private static function makeWithDataDateAndLifcycle($data, Carbon $date, $lifecycle)
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

    public static function getTotalSeries()
    {
        return [
            'legend' => 'total',
            'data' => DB::table('ticket_graph_points')
                ->selectRaw('day_date, sum(count) as count')
                ->where('lifecycle', '=', 'created_at')
                ->groupBy('day_date')
                ->orderBy('day_date')
                ->get()
            ];

    }
}
