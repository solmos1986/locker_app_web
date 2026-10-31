<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Requiere en el servidor: * * * * * php /ruta/locker_app_web/artisan schedule:run
Schedule::command('app:sync-departamentos')
    ->everyFiveMinutes()
    ->withoutOverlapping();
