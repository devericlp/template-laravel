<?php

use App\Livewire\Pages\Users\UserShow;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(UserShow::class)
        ->assertStatus(200);
});
