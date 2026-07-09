<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// This app uses Laravel's newer "slim" bootstrap (bootstrap/app.php has no
// reference to app/Console/Kernel.php), so a schedule defined there is
// never actually loaded - `php artisan schedule:list` confirmed this ran
// with zero tasks registered despite Kernel.php looking correct. This is
// the file Laravel 11+ actually reads schedule definitions from.
// The cPanel cron entry already invokes `schedule:run` every minute, so
// throttling here to every 15 min just adds up to a 14-minute delivery lag
// on top - reminder windows don't align to a fixed :00/:15/:30/:45 grid.
Schedule::command('notifications:send')->everyMinute();
