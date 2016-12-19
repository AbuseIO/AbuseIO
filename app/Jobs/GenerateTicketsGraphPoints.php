<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\TicketGraphPoint;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\DB;

class GenerateTicketsGraphPoints extends Job implements SelfHandling
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this
            ->storeNewTicketDataForToday()
            ->storeTouchedDataForToday();
    }

    public static function getNewDataPointsForToday()
    {
        //dd(self::getDataPointsForDateWithScope(Carbon::now(), 'created_at')->toSql());

        return collect(
            self::getDataPointsForDateWithScope(Carbon::now(), 'created_at')->get()
        );
    }

    public static function getTouchedDataPointsForToday()
    {
        return collect(
            self::getDataPointsForDateWithScope(Carbon::now(), 'updated_at')
                ->where('updated_at', '<>', 'created_at')
            ->get()
        );
    }

    private static function getDataPointsForDateWithScope(Carbon $date, $scope)
    {
        return DB::table('tickets')
            ->select(
                DB::raw('count(*) as cnt, class_id, type_id, status_id, contact_status_id')
            )
            ->whereDate($scope, '=', $date->toDateString())
            ->groupBy(['class_id', 'type_id', 'status_id', 'contact_status_id']);
    }

    public function storeNewTicketDataForToday()
    {
        self::getNewDataPointsForToday()
            ->map(
                function ($data) {
                    TicketGraphPoint::createNewWithDataForToday($data);
                }
            );

        return $this;
    }

    public function storeTouchedDataForToday()
    {
        self::getTouchedDataPointsForToday()
            ->map(
                function ($data) {
                    TicketGraphPoint::createTouchedDataForToday($data);
                }
            );

        return $this;
    }
}
