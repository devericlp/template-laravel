<?php

use App\Enums\Status;
use App\Livewire\Components\Users\UserActivate;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('renders successfully', function () {
    Livewire::test(UserActivate::class)
        ->assertStatus(200);
});

it('should display a confirmation dialog', function () {
    $users = User::factory()->count(3)->create(['status' => Status::INACTIVE->value]);
    $selected = $users->pluck('id')->toArray();

    Livewire::test(UserActivate::class)
        ->dispatch('confirm-activate-selected-users', $selected)
        ->assertSet('modalId', 'confirm-activate-user-modal')
        ->assertSet('typeConfirmation', 'warning')
        ->assertSet('titleConfirmation', __('messages.activate_selected'))
        ->assertSet('messageConfirmation', __('messages.are_you_sure_you_want_to_activate_the_selected_records'))
        ->assertSet('cancelTextConfirmation', null)
        ->assertSet('confirmTextConfirmation', null)
        ->assertSet('callbackConfirmation', 'bulkActivateUsers')
        ->assertSet('paramsConfirmation', [$selected]);
});

it('should activate selected users', function () {
    $users = User::factory()->count(3)->create(['status' => Status::INACTIVE->value]);
    $selected = $users->pluck('id')->toArray();

    Livewire::test(UserActivate::class)
        ->call('bulkActivateUsers', $selected)
        ->assertHasNoErrors()
        ->assertDispatched('bulk-action::completed');

    foreach ($users as $user) {
        assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => Status::ACTIVE->value,
        ]);
    }
});
