<?php

use App\Livewire\Settings\Index;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Index::class)
        ->assertStatus(200);
});

it('should allow access when passing a valid tab', function ($tab) {
    Livewire::test(Index::class, ['tab' => $tab])
        ->assertStatus(200);

})->with([
    'profile',
    'preferences',
    'password',
]);

it('should redirect to default tab when not passing a tab', function () {
    Livewire::test(Index::class)
        ->assertSet('tab', 'preferences');
});

it('should block the access when passing an invalid tab', function () {
    Livewire::test(Index::class, ['tab' => 'invalid-tab'])
        ->assertStatus(404);
});

