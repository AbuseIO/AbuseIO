<?php

namespace AbuseIO\Jobs;


use AbuseIO\Models\TicketGraphPoint;
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
        $data = collect(DB::table('tickets')
            ->select(
                DB::raw('count(*) as cnt, class_id, type_id, status_id, contact_status_id')
            )
            ->whereDay('created_at', '=', date('d', strtotime('-7 days')))
            ->groupBy(['class_id', 'type_id', 'status_id', 'contact_status_id'])
            ->get()
        );

        $data->map(function ($data) {
            (new TicketGraphPoint([
                        'count'             => $data->cnt,
                        'class'             => $data->class_id,
                        'type'              => $data->type_id,
                        'status'            => $data->status_id,
                        'contact_status'    => $data->contact_status_id,
                        'lifecycle'         => 'created',
                        'day_date'          => \Carbon\Carbon::now(),
                ]))->save();
        });
    }
}
