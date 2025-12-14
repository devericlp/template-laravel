<?php

use App\Livewire\Components\Settings\PreferencesIndex;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(PreferencesIndex::class)
        ->assertStatus(200);
});

it('should change to a valid locale', function ($locale) {
    Livewire::test(PreferencesIndex::class)
        ->set('locale', $locale)
        ->assertHasNoErrors();

    expect(app()->getLocale())->toBe($locale)
        ->and(Session::get('locale'))->toBe($locale);
})->with([
    'en',
    'pt_BR',
]);

it('should block an invalid locale', function () {
    Livewire::test(PreferencesIndex::class)
        ->set('locale', 'fr')
        ->assertHasErrors(['locale']);
});
