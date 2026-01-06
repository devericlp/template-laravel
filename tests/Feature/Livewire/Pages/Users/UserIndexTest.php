<?php

use App\Livewire\Pages\Users\UserIndex;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(UserIndex::class)
        ->assertStatus(200);
});

it('should load all users', function () {
    User::factory()->count(10)->create();

    $lw = Livewire::test(UserIndex::class)
        ->set('perPage', 10)
        ->assertSet('items', function ($items) {
            expect($items)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(10);

            return true;
        });

});

it('should filter the users by keyword', function () {
    //expect()->
});
