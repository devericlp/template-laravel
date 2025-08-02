<?php

use App\Livewire\Settings\Password;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

it('renders successfully', function () {
    Livewire::test(Password::class)
        ->assertStatus(200);
});

beforeEach(function () {
    $this->user = User::factory()->create(['password' => 'password']);
    actingAs($this->user);
});

it('should update password', function () {
    Livewire::test(Password::class)
        ->set('password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('changePassword')
        ->assertHasNoErrors();

    $this->user->refresh();

    //todo - expect password
});

test('validate required fields', function ($field) {
    Livewire::test(Password::class)
        ->set($field, '')
        ->call('changePassword')
        ->assertHasErrors([
            $field => 'required'
        ]);
})->with([
    'current_password',
    'password',
    'password_confirmation',
]);

test('validate current password', function () {
    Livewire::test(Password::class)
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('changePassword')
        ->assertHasErrors([
           'invalid_current_password'
        ]);
});

