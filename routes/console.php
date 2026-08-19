<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Schedule automated activity log cleanup to remove records older than 6 months (180 days).
 */
Schedule::command('activitylog:clean --days=180')
    ->daily()
    ->at('01:00')
    ->withoutOverlapping()
    ->runInBackground();
