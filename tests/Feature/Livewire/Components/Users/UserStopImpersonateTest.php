<?php

use App\Livewire\Components\Users\{UserImpersonate, UserStopImpersonate};
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\assertFalse;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('renders successfully', function () {
    Livewire::test(UserStopImpersonate::class)
        ->assertStatus(200);
});

it('should be able to stop the impersonation', function () {
    $impersonatedUser = User::factory()->create();

    expect(auth()->id())->toBe($this->user->id);

    Livewire::test(UserImpersonate::class)
       ->set('user', $impersonatedUser)
       ->call('impersonateUser');

    // Access the home page to trigger the middleware that handles impersonation
    get(route('home'));

    Livewire::test(UserStopImpersonate::class)
        ->call('stopImpersonate')
        ->assertRedirect(route('users.index'));

    get(route('home'))
        ->assertDontSee(__('messages.you_are_impersonating_the_user_click_to_stop_impersonation', ['user' => $impersonatedUser->name]));

    assertFalse(session()->has('impersonate'));
    assertFalse(session()->has('impersonator'));

    expect(auth()->id())->toBe($this->user->id);
});
