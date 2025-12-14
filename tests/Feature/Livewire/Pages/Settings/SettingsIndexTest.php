<?php

use App\Livewire\Pages\Settings\SettingsIndex;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('renders successfully', function () {
    Livewire::test(SettingsIndex::class)
        ->assertStatus(200);
});

it('should allow access when passing a valid tab', function ($tab) {

    if ($tab === 'profile') {
        actingAs(User::factory()->admin()->create());
    }

    Livewire::test(SettingsIndex::class, ['tab' => $tab])
        ->assertStatus(200);

})->with([
    'preferences',
    'profile',
    'password',
]);

it('should redirect to default tab when not passing a tab', function () {
    Livewire::test(SettingsIndex::class)
        ->assertSet('tab', 'preferences');
});

it('should block the access when passing an invalid tab', function () {
    Livewire::test(SettingsIndex::class, ['tab' => 'invalid-tab'])
        ->assertStatus(404);
});
