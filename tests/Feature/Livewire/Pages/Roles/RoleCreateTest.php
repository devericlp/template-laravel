<?php

use App\Livewire\Pages\Roles\RoleCreate;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(RoleCreate::class)
        ->assertStatus(200);
});
