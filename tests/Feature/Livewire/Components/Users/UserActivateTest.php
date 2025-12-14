<?php

use App\Livewire\Components\Users\UserActivate;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(UserActivate::class)
        ->assertStatus(200);
});
