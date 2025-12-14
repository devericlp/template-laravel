<?php

use App\Livewire\Components\Users\UserImpersonate;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\{assertSame, assertTrue};

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('renders successfully', function () {
    Livewire::test(UserImpersonate::class)
        ->assertStatus(200);
});

it('should load the user by parameter', function () {
    $userToImpersonate = User::factory()->create();

    Livewire::test(UserImpersonate::class, ['userId' => $userToImpersonate->id])
        ->assertSet('user.id', $userToImpersonate->id);
});

it('should load the user by event', function () {
    $userToImpersonate = User::factory()->create();

    Livewire::test(UserImpersonate::class)
        ->dispatch('confirm-impersonate-user', $userToImpersonate->id)
        ->assertSet('user.id', $userToImpersonate->id);
});

it('should add a key impersonate to the session with the given user', function () {
    $userToImpersonate = User::factory()->create();

    Livewire::test(UserImpersonate::class)
        ->set('user', $userToImpersonate)
        ->call('impersonateUser')
        ->assertRedirect(route('home'));

    assertTrue(session()->has('impersonate'));
    assertTrue(session()->has('impersonator'));

    assertSame(session()->get('impersonate'), $userToImpersonate->id);
    assertSame(session()->get('impersonator'), $this->user->id);
});

it('should make sure that we are logged with the impersonated user', function () {
    $userToImpersonate = User::factory()->create();

    expect(auth()->id())->toBe($this->user->id);

    Livewire::test(UserImpersonate::class)
        ->set('user', $userToImpersonate)
        ->call('impersonateUser')
        ->assertRedirect(route('home'));

    // Access the home page to trigger the middleware that handles impersonation
    get(route('home'))
        ->assertSee(__('messages.you_are_impersonating_the_user_click_to_stop_impersonation', ['user' => $userToImpersonate->name]));

    expect(auth()->id())->toBe($userToImpersonate->id);
});

it('should load a confirmation modal before impersonation', function () {
    $userToImpersonate = User::factory()->create();

    Livewire::test(UserImpersonate::class)
        ->call('confirmImpersonate', $userToImpersonate->id)
        ->assertSet('modalId', 'confirm-impersonate-user-modal')
        ->assertSet('titleConfirmation', __('messages.are_you_sure_you_want_to_impersonate_the_user', ['user' => $userToImpersonate->name]))
        ->assertSet('messageConfirmation', null)
        ->assertSet('callbackConfirmation', 'impersonateUser')
        ->assertSet('cancelTextConfirmation', null)
        ->assertSet('confirmTextConfirmation', null);
});

it('should not be possible to impersonate a non-existent user', function () {
    Livewire::test(UserImpersonate::class)
        ->call('confirmImpersonate', 999)
        ->assertHasErrors(['user_not_found']);
});

it('should not be possible to impersonate the logged user', function () {
    Livewire::test(UserImpersonate::class)
        ->call('confirmImpersonate', $this->user->id)
        ->assertHasErrors(['you_cannot_impersonate_yourself']);
});

it('should not be possible to impersonate a deleted user', function () {
    $deletedUser = User::factory()->deleted()->create();

    Livewire::test(UserImpersonate::class)
        ->call('confirmImpersonate', $deletedUser->id)
        ->assertHasErrors(['you_cannot_impersonate_a_deleted_user']);
});

it('should not be possible to impersonate one user while impersonating another', function () {
    $impersonatedUser = User::factory()->create();

    $lw = Livewire::test(UserImpersonate::class)
        ->set('user', $impersonatedUser)
        ->call('impersonateUser');

    // Access the home page to trigger the middleware that handles impersonation
    get(route('home'));

    $userToImpersonate = User::factory()->create();

    $lw->call('confirmImpersonate', $userToImpersonate->id)
        ->assertHasErrors(['you_are_already_impersonating_a_user']);
});
