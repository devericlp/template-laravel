<?php

use App\Livewire\Pages\Roles\RoleIndex;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(RoleIndex::class)
        ->assertStatus(200);
});
