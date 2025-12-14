<?php

use App\Livewire\Pages\Tenants\TenantShow;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(TenantShow::class)
        ->assertStatus(200);
});
