<?php

use App\Livewire\Admin\Users\Show;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('should be able to show a details of an active user in a component', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    Livewire::test(Show::class)
        ->call('loadUser', $user->id)
        ->set('user', $user)
        ->set('modal', true)
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee($user->created_at->format('d/m/Y H:i'))
        ->assertSee($user->updated_at->format('d/m/Y H:i'));
});

it('should be able to show a details of a deleted user in a component', function () {
    $admin       = User::factory()->admin()->create();
    $userDeleted = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Show::class)
        ->call('loadUser', $userDeleted->id)
        ->set('user', $userDeleted)
        ->set('modal', true)
        ->assertSee($userDeleted->name)
        ->assertSee($userDeleted->email)
        ->assertSee($userDeleted->created_at->format('d/m/Y H:i'))
        ->assertSee($userDeleted->updated_at->format('d/m/Y H:i'))
        ->assertSee($userDeleted->deleted_at->format('d/m/Y H:i'))
        ->assertSee($userDeleted->deletedBy->name);
});
