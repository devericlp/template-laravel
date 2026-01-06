<?php

use App\Http\Middleware\{HandleImpersonation, SetLocale};
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(callback: function (Middleware $middleware) {
        $middleware->appendToGroup('web', HandleImpersonation::class);

        $middleware->append([
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
