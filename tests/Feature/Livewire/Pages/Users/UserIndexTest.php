<?php

use App\Livewire\Pages\Users\UserIndex;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(UserIndex::class)
        ->assertStatus(200);
});

test('check if all actions components  is in the page', function () {
    Livewire::test(UserIndex::class)
        ->assertContainsLivewireComponent('components.users.user-create')
        ->assertContainsLivewireComponent('components.users.user-update')
        ->assertContainsLivewireComponent('components.users.user-restore')
        ->assertContainsLivewireComponent('components.users.user-impersonate');
});
