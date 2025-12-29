<?php

use App\Livewire\Pages\Roles\RoleUpdate;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(RoleUpdate::class)
        ->assertStatus(200);
});
