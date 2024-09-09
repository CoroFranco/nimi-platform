<?php

use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            \App\Http\Middleware\PreventBackHistory::class,
        ]);

        $middleware->alias([
            '-guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            '-auth' => \App\Http\Middleware\Authenticate::class,
            '-authInstructor' => \App\Http\Middleware\InstructorAuth::class,
            '-authAdmin' => \App\Http\Middleware\AdminAuth::class,
            '-redirectInstructor' => \App\Http\Middleware\RedirectInstructor::class

        ]);

        // Registrar el middleware con un alias

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();