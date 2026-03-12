<?php
// app/Console/Kernel.php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CheckBirthVerification::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Check birth verification daily at 9 AM
        $schedule->command('births:check-verification')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->onOneServer();

        // ✅ NEW: Check subscription expiration daily at 8 AM
        $schedule->command('subscriptions:check-expiration')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->onOneServer();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
