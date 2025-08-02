<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckTenantSubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = get_subdomain($request->getHost());

        if ($subdomain) {

            $tenant = cache()->rememberForever("tenant:subdomain:{$subdomain}", function () use ($subdomain) {
                return Tenant::query()->where('subdomain', $subdomain)->first();
            });

            if (! $tenant) {
                throw new NotFoundHttpException;
            }

            $request->attributes->set('tenant', $tenant);
        }

        return $next($request);
    }
}
