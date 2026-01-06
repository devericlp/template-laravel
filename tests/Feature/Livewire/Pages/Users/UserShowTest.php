<?php

use App\Livewire\Pages\Users\UserShow;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {

    $user = User::factory()->create();

    Livewire::test(UserShow::class, ['user' => $user])
        ->assertStatus(200);
});
