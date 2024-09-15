<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;


Route::get('/', Welcome::class);

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('auth.register');
Route::get('/logout', fn() => auth()->logout())->name('auth.logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {})->name('dashboard');
});

