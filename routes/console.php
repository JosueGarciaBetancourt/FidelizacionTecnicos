<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\CheckVentasIntermediadas;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


//Schedule::job(new CheckVentasIntermediadas())->dailyAt('00:00');
Schedule::job(new CheckVentasIntermediadas())->everyMinute();
