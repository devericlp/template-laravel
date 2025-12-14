<?php

use App\Livewire\Components\Users\UserDelete;
use App\Models\User;
use App\Notifications\UserDeletedNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertSoftDeleted};

beforeEach(function () {
    $this->authUser = User::factory()->create();
    actingAs($this->authUser);
});

it('renders successfully', function () {
    Livewire::test(UserDelete::class)
        ->assertStatus(200);
});

it('should load the user by parameter', function () {
    $deleteUser = User::factory()->create();

    Livewire::test(UserDelete::class, ['userId' => $deleteUser->id])
        ->assertSet('user.id', $deleteUser->id);
});

it('should load the user by event', function () {
    $deleteUser = User::factory()->create();

    Livewire::test(UserDelete::class)
        ->dispatch('confirm-delete-user', $deleteUser->id)
        ->assertSet('user.id', $deleteUser->id);
});

it('should be able to delete an user', function () {
    $deleteUser = User::factory()->create();

    Livewire::test(UserDelete::class)
        ->set('user', $deleteUser)
        ->call('deleteUser')
        ->assertDispatched('user::deleted');

    assertSoftDeleted('users', [
        'id' => $deleteUser->id,
    ]);

    $deleteUser->refresh();

    expect($deleteUser)
        ->restored_at->toBeNull()
        ->restored_by->toBeNull();
});

it('should send a notification to the user telling him that he has no long access to the application', function () {
    Notification::fake();

    $deleteUser = User::factory()->create();

    Livewire::test(UserDelete::class)
        ->set('user', $deleteUser)
        ->call('deleteUser');

    Notification::assertSentTo($deleteUser, UserDeletedNotification::class);
});

it('should load a confirmation modal before deletion', function () {
    $deleteUser = User::factory()->create();

    Livewire::test(UserDelete::class)
        ->call('confirmDelete', $deleteUser->id)
        ->assertSet('modalId', 'confirm-delete-user-modal')
        ->assertSet('titleConfirmation', __('messages.delete_user'))
        ->assertSet('messageConfirmation', __('messages.are_you_sure_you_want_to_delete_the_user', ['user' => $deleteUser->name]))
        ->assertSet('callbackConfirmation', 'deleteUser')
        ->assertSet('cancelTextConfirmation', null)
        ->assertSet('confirmTextConfirmation', null);
});

test('making sure that the traits its on in the class', function () {
    $reflection = new ReflectionClass(new UserDelete);

    $traits = $reflection->getTraitNames();

    expect(in_array('App\Traits\Livewire\HasConfirmation', $traits))->toBe(true)
        ->and(in_array('App\Traits\Livewire\HasToast', $traits))->toBe(true);
});

it('should load a confirmation modal before bulk deletion', function () {
    $deletedUsers = User::factory(10)->create();

    $selected = $deletedUsers->pluck('id')->toArray();

    Livewire::test(UserDelete::class)
        ->call('confirmbulkDeleteUsers', $selected)
        ->assertSet('modalId', 'confirm-delete-user-modal')
        ->assertSet('titleConfirmation', __('messages.delete_selected'))
        ->assertSet('messageConfirmation', __('messages.are_you_sure_you_want_to_delete_the_selected_records'))
        ->assertSet('callbackConfirmation', 'bulkDeleteUsers')
        ->assertSet('paramsConfirmation', [$selected])
        ->assertSet('cancelTextConfirmation', null)
        ->assertSet('confirmTextConfirmation', null);
});

it('should be able to delete users in bulk', function () {
    $deletedUsers = User::factory(10)->create();

    $selected = $deletedUsers->pluck('id')->toArray();

    Livewire::test(UserDelete::class)
        ->call('bulkDeleteUsers', $selected)
        ->assertDispatched('bulk-action::completed');

    foreach ($deletedUsers as $deletedUser) {
        assertSoftDeleted('users', [
            'id' => $deletedUser->id,
        ]);
    }
});
