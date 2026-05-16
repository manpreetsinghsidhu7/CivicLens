<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
| Fetch news from NewsData.io API every hour automatically.
| Run the scheduler with: php artisan schedule:work
*/

Schedule::command('news:fetch')->hourly()->withoutOverlapping()->appendOutputTo(storage_path('logs/news-fetch.log'));
