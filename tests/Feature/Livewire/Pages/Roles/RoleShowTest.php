<?php

use App\Livewire\Pages\Roles\RoleShow;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(RoleShow::class)
        ->assertStatus(200);
});
