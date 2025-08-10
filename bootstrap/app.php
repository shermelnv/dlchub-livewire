<?php

use App\Http\Middleware\OrgOnly;
use App\Http\Middleware\UserOnly;
use App\Http\Middleware\AdminOnly;

use App\Http\Middleware\AdminOrOrg;
use Illuminate\Foundation\Application;
use App\Http\Middleware\SuperAdminOnly;
use App\Schedule\UpdateVotingRoomStatus;
use App\Http\Middleware\EnsureUserIsApproved;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
         $middleware->alias([
        'admin.only' => AdminOnly::class,
        'superadmin.only' => SuperAdminOnly::class,
        'sharedRole' => AdminOrOrg::class,
        'user.only' =>UserOnly::class,
        'org.only' =>OrgOnly::class,
        'approved' =>EnsureUserIsApproved::class,
    ]);

    })
    ->withSchedule(function ($schedule) {
    $schedule->call(UpdateVotingRoomStatus::class)->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
