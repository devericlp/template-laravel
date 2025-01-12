<?php

use App\Livewire\Admin\Users\Impersonate;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\{assertSame, assertTrue};

it('should add a key impersonate to the session with the given user', function () {
    $user = User::factory()->create();

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id);

    assertTrue(session()->has('impersonate'));
    assertSame(session()->get('impersonate'), $user->id);
});

it('should make sure that we are logged with the impersonated user', function () {
    $admin            = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'joe@doe.com']);
    $userImpersonated = User::factory()->create();

    actingAs($admin);

    expect(auth()->id())->toBe($admin->id);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $userImpersonated->id);

    get(route('dashboard'))
        ->assertSee(__("You're impersonating :name, click here to stop the impersonation", ['name' => $userImpersonated->name]));

    expect(auth()->id())->toBe($userImpersonated->id);
});
