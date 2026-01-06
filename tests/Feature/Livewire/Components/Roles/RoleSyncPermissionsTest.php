<?php

use App\Livewire\Components\Roles\RoleSyncPermissions;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(RoleSyncPermissions::class)
        ->assertStatus(200);
});
