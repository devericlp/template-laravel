<?php

use App\Livewire\Auth\Password\Reset;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Reset::class)
        ->assertStatus(200);
});
