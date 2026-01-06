<?php

use App\Http\Middleware\ShouldBeVerified;
use App\Livewire\{Dashboard,
    Home,
    Pages\Auth\EmailValidation,
    Pages\Auth\Login,
    Pages\Auth\Password\Recovery,
    Pages\Auth\Password\Reset,
    Pages\Auth\Register,
    Pages\Users\UserCreate,
    Pages\Users\UserIndex,
    Pages\Users\UserShow,
    Pages\Users\UserUpdate
};
use App\Livewire\Pages\Permissions\PermissionIndex;
use App\Livewire\Pages\Roles\{RoleIndex, RoleShow};
use App\Livewire\Pages\Settings\SettingsIndex;
use Illuminate\Support\Facades\{Auth, Route};

/*
|--------------------------------------------------------------------------
| Guest routes
|--------------------------------------------------------------------------
|
|
*/

Route::middleware(['guest'])->group(function () {
    Route::redirect('/', '/login');
    Route::get('/login', Login::class)->name('login')->middleware('guest');
    Route::get('/register', Register::class)->name('register')->middleware('guest');
    Route::get('/email-validation', EmailValidation::class)->name('email-validation')->middleware('auth');
    Route::get('/logout', fn () => Auth::logout())->name('logout');
    Route::get('/password/recovery', Recovery::class)->name('password.recovery');
    Route::get('/password/reset', Reset::class)->name('password.reset');
});

/*
|--------------------------------------------------------------------------
| Application routes
|--------------------------------------------------------------------------
|
|
*/

Route::middleware(['auth', ShouldBeVerified::class, 'web'])->group(function () {

    Route::get('/home', Home::class)->name('home');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('/settings')->group(function () {
        Route::redirect('/', '/settings/preferences');
        Route::get('/{tab?}', SettingsIndex::class)->name('settings');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', UserIndex::class)->name('users.index');
        Route::get('/create', UserCreate::class)->name('users.create');
        Route::get('{user}/show', UserShow::class)->name('users.show');
        Route::get('{user}', UserUpdate::class)->name('users.update');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', RoleIndex::class)->name('roles.index');
        Route::get('/{role}', RoleShow::class)->name('roles.show');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', PermissionIndex::class)->name('permissions.index');
    });
});
