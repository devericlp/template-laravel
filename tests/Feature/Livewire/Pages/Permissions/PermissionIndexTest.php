<?php

use App\Livewire\Pages\Permissions\PermissionIndex;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(PermissionIndex::class)
        ->assertStatus(200);
});
