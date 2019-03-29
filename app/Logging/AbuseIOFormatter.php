<?php

namespace App\Logging;

class AbuseIOFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param \Illuminate\Log\Logger $logger
     *
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(
                new \Monolog\Formatter\LineFormatter('%channel%.%level_name%: %message%')
            );
        }
    }
}
