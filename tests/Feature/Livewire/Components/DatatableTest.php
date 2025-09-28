<?php

use App\Livewire\Components\Datatable;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Datatable::class)
        ->assertStatus(200);
});
