<?php

use App\Livewire\Settings\Preferences;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Preferences::class)
        ->assertStatus(200);
});

it('should change to a valid locale', function ($locale) {
    Livewire::test(Preferences::class)
        ->set('locale', $locale)
        ->assertHasErrors();

    expect(app()->getLocale())->toBe($locale)
        ->and(Session::get('locale'))->toBe($locale);
})->with([
    'en',
    'pt_BR',
]);

it('should block an invalid locale', function () {
    Livewire::test(Preferences::class)
        ->set('locale', 'fr')
        ->assertHasErrors(['locale']);
});
