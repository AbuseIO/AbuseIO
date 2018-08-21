<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\TicketGraphPoint;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateTicketsGraphPoints extends Job
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
        // only create graphpoints once
        if (count(TicketGraphPoint::where('day_date', '=', \Carbon::yesterday())->get()) > 0) {
            Log::info('TicketGraphPoint collector has already runned today, skipping');

            return;
        }

        $this
            ->storeNewTicketDataForYesterday()
            ->storeTouchedDataForYesterday();
    }

    public static function getNewDataPointsForYesterday()
    {
        return collect(
            self::getDataPointsForDateWithScope(Carbon::yesterday(), 'created_at')->get()
        );
    }

    public static function getTouchedDataPointsForYesterday()
    {
        return collect(
            self::getDataPointsForDateWithScope(Carbon::yesterday(), 'updated_at')
                ->whereRaw('updated_at != created_at')->get()
        );
    }

    private static function getDataPointsForDateWithScope(Carbon $date, $scope)
    {
        return DB::table('tickets')
            ->select(
                DB::raw('count(*) as cnt, class_id, type_id, status_id, contact_status_id')
            )
            ->whereDate($scope, '=', $date->toDateString())
            ->whereNull('deleted_at')
            ->groupBy(['class_id', 'type_id', 'status_id', 'contact_status_id']);
    }

    public function storeNewTicketDataForYesterday()
    {
        self::getNewDataPointsForYesterday()
            ->map(
                function ($data) {
                    TicketGraphPoint::createNewWithDataForYesterday($data);
                }
            );

        return $this;
    }

    public function storeTouchedDataForYesterday()
    {
        self::getTouchedDataPointsForYesterday()
            ->map(
                function ($data) {
                    TicketGraphPoint::createTouchedDataForYesterday($data);
                }
            );

        return $this;
    }

    public function __destruct()
    {
        Log::debug(
            get_class($this).': Statistics collector has completed its run'
        );
    }
}
