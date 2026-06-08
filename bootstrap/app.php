<?php

use App\Http\Middleware\RedirectIfNotInstalled;
use App\Http\Middleware\ValidateEaApiKey;
use Illuminate\Foundation\Application;
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
            'validate.ea.key'      => ValidateEaApiKey::class,
            'check.installed'      => RedirectIfNotInstalled::class,
        ]);
        $middleware->validateCsrfTokens(except: ['mcp']);
        $middleware->web(append: [RedirectIfNotInstalled::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
