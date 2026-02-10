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
    ->withMiddleware(function (Middleware $middleware) {
        // Trust proxy headers (required on Render / reverse-proxy deployments)
        // so asset() / url() generate HTTPS links correctly.
        $middleware->trustProxies(at: '*');

        // Keep Laravel default middleware stack and append custom ones.
        $middleware->append([
            RedirectIfNotAuthenticated::class,
            RedirectIfNotEmployee::class,
            CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();