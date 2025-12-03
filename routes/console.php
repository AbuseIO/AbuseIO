<?php

Schedule::command('housekeeper:run')->cron(config('main.housekeeping.housekeeper_cron'));
Schedule::command('housekeeper:notifications --send')->cron(config('main.housekeeping.notifications_cron'));
Schedule::command('collector:runall')->cron(config('main.housekeeping.collectors_cron'));
Schedule::command('statistics:run')->cron(config('main.housekeeping.collect_statistics_cron'));
