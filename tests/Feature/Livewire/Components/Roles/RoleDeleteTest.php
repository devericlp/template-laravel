<?php

use App\Livewire\Components\Roles\RoleDelete;
use App\Models\{Role, User};
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing};

it('renders successfully', function () {
    Livewire::test(RoleDelete::class)
        ->assertStatus(200);
});

it('should load the role by parameter', function () {
    $deleteRole = Role::factory()->create();

    Livewire::test(RoleDelete::class, ['roleId' => $deleteRole->id])
        ->assertSet('role.id', $deleteRole->id);
});

it('should load the role by event', function () {
    $deleteRole = Role::factory()->create();

    Livewire::test(RoleDelete::class)
        ->dispatch('confirm-delete-role', $deleteRole->id)
        ->assertSet('role.id', $deleteRole->id);
});

it('should be able to delete a role', function () {
    $deleteRole = Role::factory()->create();

    Livewire::test(RoleDelete::class)
        ->set('role', $deleteRole)
        ->call('deleteRole')
        ->assertDispatched('role::deleted');

    assertDatabaseMissing('roles', [
        'id' => $deleteRole->id,
    ]);
});

it('should load a confirmation modal before deletion', function () {
    $deleteRole = Role::factory()->create();

    Livewire::test(RoleDelete::class)
        ->call('confirmDelete', $deleteRole->id)
        ->assertSet('modalId', 'confirm-delete-role-modal')
        ->assertSet('titleConfirmation', __('messages.delete_role'))
        ->assertSet('messageConfirmation', __('messages.are_you_sure_you_want_to_delete_the_role', ['role' => $deleteRole->name]))
        ->assertSet('callbackConfirmation', 'deleteRole')
        ->assertSet('cancelTextConfirmation', null)
        ->assertSet('confirmTextConfirmation', null);
});

it('should not delete a role with users associated', function () {
    $deleteRole = Role::factory()->create();

    $user = User::factory()->create();
    $user->roles()->attach($deleteRole->id);

    Livewire::test(RoleDelete::class)
        ->set('role', $deleteRole)
        ->call('deleteRole')
        ->assertHasErrors(['role_has_users']);

    assertDatabaseHas('roles', [
        'id' => $deleteRole->id,
    ]);
});

test('making sure that the traits its on in the class', function () {
    $reflection = new ReflectionClass(new RoleDelete);

    $traits = $reflection->getTraitNames();

    expect(in_array('App\Traits\Livewire\HasConfirmation', $traits))->toBe(true)
        ->and(in_array('App\Traits\Livewire\HasToast', $traits))->toBe(true);
});
