<?php

use App\Livewire\Components\Roles\RoleUsersList;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(RoleUsersList::class)
        ->assertStatus(200);
});
