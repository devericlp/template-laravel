<?php

use App\Http\Middleware\ShouldBeVerified;
use App\Livewire\Auth\{EmailValidation, Login, Password, Register};
use App\Livewire\{Dashboard, Home};
use App\Livewire\Settings\Index;
use App\Livewire\Tenants;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Subdomain routes
|--------------------------------------------------------------------------
|
| Routes accessed with and without subdomain
|
*/

Route::middleware(['check_tenant_subdomain'])->group(function () {

    Route::redirect('/', '/login');
    Route::get('/login', Login::class)->name('login')->middleware('guest');
    Route::get('/register', Register::class)->name('register')->middleware('guest');
    Route::get('/email-validation', EmailValidation::class)->name('email-validation')->middleware('auth');
    Route::get('/logout', fn () => auth()->logout())->name('logout');
    Route::get('/password/recovery', Password\Recovery::class)->name('password.recovery');
    Route::get('/password/reset', Password\Reset::class)->name('password.reset');

    Route::middleware(['auth', ShouldBeVerified::class])->group(function () {

        Route::get('/home', Home::class)->name('home');

        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        Route::prefix('/settings')->group(function () {
            Route::redirect('/', '/settings/preferences');
            Route::get('/{tab?}', Index::class)->name('settings');
        });
    });

});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
|
| Routes accessed only in the main domain with a master user
|
*/

Route::middleware(['main_domain'])->group(function () {

    Route::prefix('tenants')->group(function () {
        Route::get('/', Tenants\Index::class)->name('tenants.index');
        Route::get('/create', Tenants\Create::class)->name('tenants.create');
        Route::get('{tenant}', Tenants\Show::class)->name('tenants.show');
    });

});
