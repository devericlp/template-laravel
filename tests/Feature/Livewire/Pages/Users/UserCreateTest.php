<?php

use App\Enums\Roles;
use App\Livewire\Pages\Users\UserCreate;
use App\Models\{User};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{Storage};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};

beforeEach(function () {
    actingAs(User::factory()->admin()->create());
});

it('renders successfully', function () {
    Livewire::test(UserCreate::class)
        ->assertStatus(200);
});

it('should be able to register a new user', function ($role_id) {
    Livewire::test(UserCreate::class)
        ->set('name', 'John doe')
        ->set('email', 'john@doe.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('role_id', $role_id)
        ->call('store')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name' => 'John doe',
        'email' => 'john@doe.com',
        'avatar' => null
    ]);

    assertDatabaseCount('users', 2);

    $user = User::where('email', 'john@doe.com')->first();
    expect($user->hasRole($role_id))->toBeTrue();
})->with([
    'admin' => [Roles::ADMIN->value],
    'user' => [Roles::USER->value],
]);

it('should be able to check if the email is valid', function () {
    Livewire::test(UserCreate::class)
        ->set('name', 'John doe')
        ->set('email', 'invalid-email')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('role_id', Roles::USER->value)
        ->call('store')
        ->assertHasErrors([
            'email' => 'email',
        ]);
});

it('should be able to check if the email already exists', function () {
    User::factory()->create(['email' => 'johndoe@example.com']);

    Livewire::test(UserCreate::class)
        ->set('name', 'John doe')
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('role_id', Roles::USER->value)
        ->call('store')
        ->assertHasErrors([
            'email' => 'unique',
        ]);
});

it('should be able to check if the password fields are different', function () {
    Livewire::test(UserCreate::class)
        ->set('name', 'John doe')
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'another_password')
        ->set('role_id', Roles::USER->value)
        ->call('store')
        ->assertHasErrors(['password' => 'confirmed']);
});

test('strength password', function () {
    Livewire::test(UserCreate::class)
        ->set('name', 'John doe')
        ->set('email', 'johndoe@example.com')
        ->set('password', '123456')
        ->set('password_confirmation', '123456')
        ->set('role_id', Roles::USER->value)
        ->call('store')
        ->assertHasErrors(['password' => 'min:8']);
});

test('required fields', function ($field) {
    Livewire::test(UserCreate::class)
        ->set($field, '')
        ->call('store')
        ->assertHasErrors([$field => 'required']);
})->with(['name', 'email', 'role_id', 'password', 'password_confirmation']);

it('should allow saving an image as an avatar when registering an user', function () {
    Livewire::test(UserCreate::class)
        ->set('name', 'John doe')
        ->set('email', 'john@doe.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('role_id', Roles::USER->value)
        ->set('avatar', UploadedFile::fake()->image('avatar.jpg'))
        ->call('store')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name' => 'John doe',
        'email' => 'john@doe.com',
    ]);

    $user = User::where('email', 'john@doe.com')->first();
    expect($user->avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar);
});

it('should mark the user as having a email verified', function () {
    Livewire::test(UserCreate::class)
        ->set('name', 'John doe')
        ->set('email', 'john@doe.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('role_id', Roles::USER->value)
        ->call('store')
        ->assertHasNoErrors();

    $user = User::where('email', 'john@doe.com')->first();
    expect($user->email_verified_at)->not->toBeNull();
});
