<?php

use App\Enums\Status;
use App\Livewire\Components\Users\UserInactivate;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('renders successfully', function () {
    Livewire::test(UserInactivate::class)
        ->assertStatus(200);
});

it('should display a confirmation dialog', function () {
    $users = User::factory()->count(3)->create(['status' => Status::ACTIVE->value]);
    $selected = $users->pluck('id')->toArray();

    Livewire::test(UserInactivate::class)
        ->dispatch('confirm-inactivate-selected-users', $selected)
        ->assertSet('modalId', 'confirm-inactivate-user-modal')
        ->assertSet('typeConfirmation', 'warning')
        ->assertSet('titleConfirmation', __('messages.inactivate_selected'))
        ->assertSet('messageConfirmation', __('messages.are_you_sure_you_want_to_inactivate_the_selected_records'))
        ->assertSet('cancelTextConfirmation', null)
        ->assertSet('confirmTextConfirmation', null)
        ->assertSet('callbackConfirmation', 'bulkInactivateUsers')
        ->assertSet('paramsConfirmation', [$selected]);
});

it('should inactivate selected users', function () {
    $users = User::factory()->count(3)->create(['status' => Status::ACTIVE->value]);
    $selected = $users->pluck('id')->toArray();

    Livewire::test(UserInactivate::class)
        ->call('bulkInactivateUsers', $selected)
        ->assertHasNoErrors()
        ->assertDispatched('bulk-action::completed');

    foreach ($users as $user) {
        assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => Status::INACTIVE->value,
        ]);
    }
});
