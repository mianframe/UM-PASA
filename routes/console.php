<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('um-pasa:expire-stale-requests --days=7')
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->onOneServer();
