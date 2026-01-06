<?php

use App\Livewire\Components\Roles\RolePermissionsList;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(RolePermissionsList::class)
        ->assertStatus(200);
});
