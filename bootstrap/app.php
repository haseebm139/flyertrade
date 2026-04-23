<?php

use App\Models\ScheduleRunLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Mail\MailServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->call(function () {
            // try {
            //     ScheduleRunLog::query()->create([
            //         'ran_at' => now(),
            //         'source' => 'schedule:run',
            //     ]);
            // } catch (\Throwable $e) {
            //     Log::warning('schedule_run_logs insert failed', [
            //         'message' => $e->getMessage(),
            //     ]);
            // }
        })->everyMinute()->name('log-schedule-run');

        // $schedule->command('bookings:auto-reject')->everyFiveMinutes();
        // Reconciles missed reminders if queue workers were down (primary path: delayed jobs).
        $schedule->command('bookings:send-reminders')
            ->hourly()
            ->withoutOverlapping(10);
        $schedule->command('bookings:notify-customer-provider-not-started')->everyFiveMinutes();
        $schedule->command('bookings:cancel-unpaid-past')->hourly();
    })
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->withProviders([
        MailServiceProvider::class,
    ])->create();
