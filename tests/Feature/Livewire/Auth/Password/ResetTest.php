<?php

use App\Livewire\Auth\Password\{Recovery, Reset};
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\{Notification};
use Livewire\Livewire;

use function Pest\Laravel\get;

test('needs to have a route that will receive a token and the email needs to be reset it', function () {
    get(route('password.reset'))
        ->assertOk();
});

it('renders successfully', function () {
    Livewire::test(Reset::class)
        ->assertStatus(200);
});

test('need to receive a valid token with a combination with the email', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword');

    Notification::assertSentTo([$user], ResetPassword::class, function ($notification) {

        get(route('password.reset') . '?token=' . $notification->token)
            ->assertOk();

        get(route('password.reset') . '?token=' . 'any-token')
            ->assertRedirect(route('login'));

        return true;
    });
});
