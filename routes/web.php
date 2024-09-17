<?php

use App\Livewire\Auth\{Login, Logout, PasswordRecovery, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('auth.register');
Route::get('/logout', Logout::class)->name('auth.logout');
Route::get('/password-recovery', PasswordRecovery::class)->name('auth.password-recovery');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Welcome::class)->name('dashboard');
});
