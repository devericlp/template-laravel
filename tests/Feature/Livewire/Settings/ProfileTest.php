<?php

use App\Livewire\Settings\Profile;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

it('renders successfully', function () {
    Livewire::test(Profile::class)
        ->assertStatus(200);
});

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('should update profile fields', function () {

    Livewire::test(Profile::class)
        ->set('name', 'User test')
        ->set('name', 'user_test@example.com')
        ->call('update')
        ->assertHasNoErrors();

    $this->user->refresh();


    expect($this->user->email)->email->toBe('user_test@example.com')
        ->and($this->user->name)->name->toBe('User test');
});

test('validate required fields', function ($field) {
    Livewire::test(Profile::class)
        ->set($field, '')
        ->call('update')
        ->assertHasErrors([
            $field => 'required'
        ]);
})->with([
    'name',
    'email'
]);

test('validate unique email field', function () {
    Livewire::test(Profile::class)
        ->set('email', '')
        ->call('update')
        ->assertHasErrors([
            $field => 'required'
        ]);
});

