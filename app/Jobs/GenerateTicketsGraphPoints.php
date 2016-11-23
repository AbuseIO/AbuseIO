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
        return self::getDataPointsForDateWithScope(Carbon::now(), 'created_at');
    }

    public static function getNewDataPointsForYesterday()
    {
        return self::getDataPointsForDateWithScope(Carbon::yesterday(), 'created_at');
    }

    public static function getTouchedDataPointsForToday()
    {
        return self::getDataPointsForDateWithScope(Carbon::now(), 'updated_at');
    }

    public static function getTouchedDataPointsForYesterday()
    {
        return self::getDataPointsForDateWithScope(Carbon::yesterday(), 'updated_at');
    }

    private static function getDataPointsForDateWithScope(Carbon $date, $scope)
    {
        return collect(DB::table('tickets')
            ->select(
                DB::raw('count(*) as cnt, class_id, type_id, status_id, contact_status_id')
            )
            ->whereDay($scope, '=', $date->toDateString())
            ->groupBy(['class_id', 'type_id', 'status_id', 'contact_status_id'])
            ->get()
        );
    }

    private function storeNewTicketDataForToday()
    {
        self::getNewDataPointsForToday()
            ->map(
                function ($data) {
                    TicketGraphPoint::createNewWithDataForToday($data);
                }
            );

        return $this;
    }

    private function storeTouchedDataForToday()
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
