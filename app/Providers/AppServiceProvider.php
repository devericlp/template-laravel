<?php

namespace App\Providers;

use App\Enums\Roles;
use Illuminate\Support\Facades\{Auth, Gate};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user) {
            return Auth::check() && ($user->hasRole(Roles::ADMIN->label())) ? true : null;
        });
    }
}
