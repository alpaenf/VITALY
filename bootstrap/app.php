<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
        $middleware->alias([
            'admin'          => \App\Http\Middleware\AdminMiddleware::class,
            'kader'          => \App\Http\Middleware\KaderMiddleware::class,
            'patient.session'=> \App\Http\Middleware\PatientSessionMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function () {
            if (auth()->check()) {
                if (auth()->user()->isAdmin()) {
                    return '/admin/dashboard';
                }
                return '/kader/dashboard';
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
