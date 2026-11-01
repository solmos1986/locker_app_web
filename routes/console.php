<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Requiere en el servidor: * * * * * php /ruta/locker_app_web/artisan schedule:run
// Se activa con SYNC_DEPARTAMENTOS_ENABLED=true (config/app.php).
Schedule::command('app:sync-departamentos')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->when(fn () => config('app.sync_departamentos'));
