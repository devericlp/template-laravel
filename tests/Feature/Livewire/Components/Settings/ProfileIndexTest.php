<?php

use App\Livewire\Components\Settings\ProfileIndex;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('renders successfully', function () {
    Livewire::test(ProfileIndex::class)
        ->assertStatus(200);
});

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('should update profile fields', function () {

    Livewire::test(ProfileIndex::class)
        ->set('name', 'User test')
        ->set('email', 'user_test@example.com')
        ->call('updateProfile')
        ->assertHasNoErrors();

    $this->user->refresh();

    expect($this->user)->email->toBe('user_test@example.com')
        ->and($this->user)->name->toBe('User test');
});

test('validate required fields', function ($field) {
    Livewire::test(ProfileIndex::class)
        ->set($field, '')
        ->call('updateProfile')
        ->assertHasErrors([
            $field => 'required'
        ]);
})->with([
    'name',
    'email'
]);

test('validate unique email field', function () {
    $anotherUser = User::factory()->create(['email' => 'another-email@email.com']);

    Livewire::test(ProfileIndex::class)
        ->set('email', $anotherUser->email)
        ->call('updateProfile')
        ->assertHasErrors([
            'email' => 'unique'
        ]);
});
