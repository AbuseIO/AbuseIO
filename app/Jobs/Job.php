<?php

namespace AbuseIO\Jobs;

use Illuminate\Bus\Queueable;

abstract class Job
{

    use Queueable;

    /**
     * Command has failed
     * @return array
     */
    protected function failed($message)
    {
        return [
            'errorStatus'   => true,
            'errorMessage'  => $message,
            'data'          => '',
        ];
    }

    /**
     * Command is OK
     * @return array
     */
    protected function success($data)
    {
        return [
            'errorStatus'   => false,
            'errorMessage'  => 'Data sucessfully parsed',
            'data'          => $data,
        ];
    }
}
