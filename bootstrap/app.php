<?php

use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('absensi:auto-mark-absent')
            ->dailyAt('00:01')
            ->timezone('Asia/Makassar');

        $schedule->command('user:auto-delete')
            ->dailyAt('00:05')
            ->timezone('Asia/Makassar');

        $schedule->command('user:morning-absen-reminder')
            ->everyTenMinutes()
            ->between('07:00', '08:00')
            ->timezone('Asia/Makassar');

        $schedule->command('notif:auto-cleanup')
            ->dailyAt('00:10')
            ->timezone('Asia/Makassar');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
