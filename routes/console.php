<?php

use App\Console\Commands\ProcessOutageSchedules;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Process outage schedules every minute — auto-flips feeder status at scheduled times
Schedule::command(ProcessOutageSchedules::class)->everyMinute();
