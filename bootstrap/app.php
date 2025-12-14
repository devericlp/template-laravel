<?php

use App\Http\Middleware\{CheckTenantSubdomain, HandleImpersonation, MainDomainAccess, SetLocale};
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

        $middleware->alias([
            'main_domain' => MainDomainAccess::class,
            'check_tenant_subdomain' => CheckTenantSubdomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
