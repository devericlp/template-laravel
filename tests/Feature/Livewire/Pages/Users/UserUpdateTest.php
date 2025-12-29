<?php

use App\Enums\Roles;
use App\Livewire\Pages\Users\UserUpdate;
use App\Models\{Tenant, User};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{Hash, Storage};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs};

beforeEach(function () {
    actingAs(User::factory()->admin()->create());
    $this->tenant = Tenant::factory()->create();
});

it('renders successfully', function () {
    $user = User::factory()->create();
    Livewire::test(UserUpdate::class, ['user' => $user])
        ->assertStatus(200);
});

it('should be able to update an user', function ($role_id) {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create();

    $oldPassword = $user->password;

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('name', 'John doe')
        ->set('email', 'john@doe.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('tenant_id', $tenant->id)
        ->set('role_id', $role_id)
        ->call('update')
        ->assertHasNoErrors();

    $user->refresh();
    expect($oldPassword)->not->toBe($user->password);
})->with([
    'admin' => [Roles::ADMIN->value],
    'user' => [Roles::USER->value],
]);

it('should not update the password when the password fields are not filled in', function () {
    $user = User::factory()->create();

    $oldPassword = $user->password;

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->call('update')
        ->assertHasNoErrors();

    $user->refresh();
    expect($oldPassword)->toBe($user->password);
});

it('should update the password when the password fields are filled in', function () {
    $user = User::factory()->create();

    $oldPassword = $user->password;

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('update')
        ->assertHasNoErrors();

    $user->refresh();
    expect($oldPassword)->not->toBe($user->password);
    expect(Hash::check('new-password', $user->password))->toBeTrue();
});

it('should be able to check if the email is valid', function () {
    $user = User::factory()->create();

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('email', 'invalid-email')
        ->call('update')
        ->assertHasErrors([
            'email' => 'email',
        ]);
});

it('should be able to check if the email already exists', function () {
    User::factory()->create(['email' => 'johndoe@example.com']);

    $user = User::factory()->create();

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('email', 'johndoe@example.com')
        ->call('update')
        ->assertHasErrors([
            'email' => 'unique',
        ]);
});

it('should be able to update the email with the same value', function () {
    $user = User::factory()->create(['email' => 'joedoe@email.com']);

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('email', 'joedoe@email.com')
        ->call('update')
        ->assertHasNoErrors();
});

it('should be able to check if the password fields are different', function () {
    $user = User::factory()->create();

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('password', 'password123')
        ->set('password_confirmation', 'another_password')
        ->call('update')
        ->assertHasErrors(['password' => 'confirmed']);
});

test('strength password', function () {
    $user = User::factory()->create();

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('password', 'pass')
        ->set('password_confirmation', 'pass')
        ->call('update')
        ->assertHasErrors(['password' => 'min:8']);
});

test('required fields', function ($field) {
    $user = User::factory()->create();

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set($field, '')
        ->call('update')
        ->assertHasErrors([$field => 'required']);
})->with(['name', 'email', 'role_id', 'tenant_id']);

it('should save the avatar image', function () {
    $user = User::factory()->create();

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('avatar', UploadedFile::fake()->image('avatar.jpg'))
        ->call('update')
        ->assertHasNoErrors();

    $user->refresh();
    expect($user->avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar);
});

it('should delete the avatar file when the user remove the avatar image', function () {
    Storage::fake('public');

    $oldAvatarPath = 'users/old_avatar.jpg';
    Storage::disk('public')->put($oldAvatarPath, 'fake-old-content');

    $user = User::factory()->create([
        'avatar' => $oldAvatarPath,
    ]);

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('avatar', null)
        ->call('update')
        ->assertHasNoErrors();

    $user->refresh();

    Storage::disk('public')->assertMissing($oldAvatarPath);
    expect($user->avatar)->toBeNull();
});

it('should ensure that not duplicate profiles or have more than one', function () {
    $user = User::factory()->create();

    Livewire::test(UserUpdate::class, ['user' => $user])
        ->set('role_id', Roles::ADMIN->value)
        ->call('update')
        ->assertHasNoErrors();

    $user->refresh();
    expect($user->roles()->count())->toBe(1);
});
