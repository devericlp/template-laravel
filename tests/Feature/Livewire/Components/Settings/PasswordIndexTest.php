<?php

use App\Livewire\Components\Settings\PasswordIndex;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('renders successfully', function () {
    Livewire::test(PasswordIndex::class)
        ->assertStatus(200);
});

beforeEach(function () {
    $this->user = User::factory()->create(['password' => 'password']);
    actingAs($this->user);
});

it('should update password', function () {
    Livewire::test(PasswordIndex::class)
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('changePassword')
        ->assertHasNoErrors();
});

test('validate required fields', function ($field) {
    Livewire::test(PasswordIndex::class)
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
    Livewire::test(PasswordIndex::class)
        ->set('current_password', 'invalid-current-password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('changePassword')
        ->assertHasErrors([
            'current_password'
        ]);
});
