<?php

use App\Livewire\Pages\Tenants\TenantIndex;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(TenantIndex::class)
        ->assertStatus(200);
});
