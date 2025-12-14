<?php

use App\Livewire\Components\Users\UserInactivate;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(UserInactivate::class)
        ->assertStatus(200);
});
