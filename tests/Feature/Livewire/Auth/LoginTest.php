<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Models\User;
use Faker\Factory;
use Livewire\Livewire;

it('should render the component', function () {
    Livewire::test(Login::class)
        ->assertStatus(200);
});

it('should be able to login', function () {
    $user = User::factory()->create(['email' => 'johndoe@example.com', 'password' => 'password']);

    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);
});

it('should to inform the user an error when the credentials are invalid', function () {
    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('login')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee(trans('auth.failed'));

});
