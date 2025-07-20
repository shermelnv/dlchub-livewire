<?php

use App\Http\Middleware\UserOnly;
use App\Http\Middleware\AdminOnly;
use Illuminate\Foundation\Application;

use App\Http\Middleware\SuperAdminOnly;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
         $middleware->alias([
        'admin.only' => AdminOnly::class,
        'superadmin.only' => SuperAdminOnly::class,
        'user.only' =>UserOnly::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
