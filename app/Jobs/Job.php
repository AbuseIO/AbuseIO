<?php

namespace AbuseIO\Jobs;

use Illuminate\Bus\Queueable;

/**
 * Abstract Class Job.
 */
abstract class Job
{
    use Queueable;

    /**
     * Command has failed.
     *
     * @param string $message
     *
     * @return array
     */
    protected function error($message)
    {
        return [
            'errorStatus'  => true,
            'errorMessage' => $message,
            'data'         => '',
        ];
    }

    /**
     * Command is OK.
     *
     * @param array $data
     *
     * @return array
     */
    protected function success($data)
    {
        return [
            'errorStatus'  => false,
            'errorMessage' => 'Data sucessfully parsed',
            'data'         => $data,
        ];
    }
}
