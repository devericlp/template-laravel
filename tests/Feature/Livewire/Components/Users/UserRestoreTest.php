<?php

use App\Livewire\Components\Users\UserRestore;
use App\Models\User;
use App\Notifications\UserRestoredNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted};

beforeEach(function () {
    $this->authUser = User::factory()->create();
    actingAs($this->authUser);
});

it('renders successfully', function () {
    Livewire::test(UserRestore::class)
        ->assertStatus(200);
});

it('should load the user by parameter', function () {
    $restoreUser = User::factory()->deleted()->create();

    Livewire::test(UserRestore::class, ['userId' => $restoreUser->id])
        ->assertSet('user.id', $restoreUser->id);
});

it('should load the user by event', function () {
    $restoreUser = User::factory()->deleted()->create();

    Livewire::test(UserRestore::class)
        ->dispatch('confirm-restore-user', $restoreUser->id)
        ->assertSet('user.id', $restoreUser->id);
});

it('should be able to restore a deleted user', function () {
    $restoreUser = User::factory()->deleted()->create();

    Livewire::test(UserRestore::class)
        ->set('user', $restoreUser)
        ->call('restoreUser')
        ->assertDispatched('user::restored');

    assertNotSoftDeleted('users', [
        'id' => $restoreUser->id,
    ]);

    $restoreUser->refresh();

    expect($restoreUser)
        ->restored_at->not->toBeNull()
        ->restored_by->toBe($this->authUser->id)
        ->deleted_by->toBeNull();
});

it('should send a notification to the user telling him that he has access to the application again', function () {
    Notification::fake();

    $restoreUser = User::factory()->deleted()->create();

    Livewire::test(UserRestore::class)
        ->set('user', $restoreUser)
        ->call('restoreUser');

    Notification::assertSentTo($restoreUser, UserRestoredNotification::class);
});

it('should load a confirmation modal before restoration', function () {
    $restoreUser = User::factory()->deleted()->create();

    Livewire::test(UserRestore::class)
        ->call('confirmRestore', $restoreUser->id)
        ->assertSet('modalId', 'confirm-restore-user-modal')
        ->assertSet('titleConfirmation', __('messages.restore_user'))
        ->assertSet('messageConfirmation', __('messages.are_you_sure_you_want_to_restore_the_user', ['user' => $restoreUser->name]))
        ->assertSet('callbackConfirmation', 'restoreUser')
        ->assertSet('cancelTextConfirmation', null)
        ->assertSet('confirmTextConfirmation', null);
});

test('making sure that the traits its on in the class', function () {
    $reflection = new ReflectionClass(new UserRestore);

    $traits = $reflection->getTraitNames();

    expect(in_array('App\Traits\Livewire\HasConfirmation', $traits))->toBe(true)
        ->and(in_array('App\Traits\Livewire\HasToast', $traits))->toBe(true);
});

it('should load a confirmation modal before bulk restoration', function () {
    $deletedUsers = User::factory(10)->deleted()->create();

    $selected = $deletedUsers->pluck('id')->toArray();

    Livewire::test(UserRestore::class)
        ->call('confirmbulkRestoreUsers', $selected)
        ->assertSet('modalId', 'confirm-restore-user-modal')
        ->assertSet('titleConfirmation', __('messages.restore_selected'))
        ->assertSet('messageConfirmation', __('messages.are_you_sure_you_want_to_restore_the_selected_records'))
        ->assertSet('callbackConfirmation', 'bulkRestoreUsers')
        ->assertSet('paramsConfirmation', [$selected])
        ->assertSet('cancelTextConfirmation', null)
        ->assertSet('confirmTextConfirmation', null);
});

it('should be able to restore users in bulk', function () {
    $deletedUsers = User::factory(10)->create();

    $selected = $deletedUsers->pluck('id')->toArray();

    Livewire::test(UserRestore::class)
        ->call('bulkRestoreUsers', $selected)
        ->assertDispatched('bulk-action::completed');

    foreach ($deletedUsers as $deletedUser) {
        assertNotSoftDeleted('users', [
            'id' => $deletedUser->id,
        ]);
    }
});
