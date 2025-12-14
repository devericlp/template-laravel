<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{App, Crypt, Session};
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->hasCookie('locale')) {
            $decrypted_cookie = Crypt::decryptString($request->cookie('locale'));
            $locale = explode('|', $decrypted_cookie)[1];

            App::setLocale($locale);
            Session::put('locale', $locale);
        } elseif (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            App::setLocale('pt_BR');
        }

        return $next($request);
    }
}
