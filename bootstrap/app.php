<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\RedirectIfNotEmployee;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function () {
        // Thêm middleware vào trong cấu hình
        return [
            \App\Http\Middleware\RedirectIfNotAuthenticated::class,
            \App\Http\Middleware\RedirectIfNotEmployee::class,  // Add the RedirectIfNotEmployee middleware
            \App\Http\Middleware\CheckRole::class,  // Add the CheckRole middleware
        ];
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();