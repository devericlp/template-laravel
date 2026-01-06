<?php

use App\Livewire\Components\Permissions\PermissionDelete;
use App\Models\{Permission, Role};
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing};

it('renders successfully', function () {
    Livewire::test(PermissionDelete::class)
        ->assertStatus(200);
});

it('should load the permission by parameter', function () {
    $deletePermission = Permission::factory()->create();

    Livewire::test(PermissionDelete::class, ['permissionId' => $deletePermission->id])
        ->assertSet('permission.id', $deletePermission->id);
});

it('should load the permission by event', function () {
    $deletePermission = Permission::factory()->create();
    Livewire::test(PermissionDelete::class)
        ->dispatch('confirm-delete-permission', $deletePermission->id)
        ->assertSet('permission.id', $deletePermission->id);
});

it('should be able to delete a permission', function () {
    $deletePermission = Permission::factory()->create();
    Livewire::test(PermissionDelete::class)
        ->set('permission', $deletePermission)
        ->call('deletePermission')
        ->assertDispatched('permission::deleted');
    assertDatabaseMissing('permissions', [
        'id' => $deletePermission->id,
    ]);
});

it('should load a confirmation modal before deletion', function () {
    $deletePermission = Permission::factory()->create();

    Livewire::test(PermissionDelete::class)
        ->call('confirmDelete', $deletePermission->id)
        ->assertSet('modalId', 'confirm-delete-permission-modal')
        ->assertSet('titleConfirmation', __('messages.delete_permission'))
        ->assertSet('messageConfirmation', __('messages.are_you_sure_you_want_to_delete_the_permission', ['permission' => $deletePermission->name]))
        ->assertSet('callbackConfirmation', 'deletePermission')
        ->assertSet('cancelTextConfirmation', null)
        ->assertSet('confirmTextConfirmation', null);
});

it('should not delete a permission with roles associated', function () {
    $deletePermission = Permission::factory()->create();

    $role = Role::factory()->create();
    $role->permissions()->attach($deletePermission->id);

    Livewire::test(PermissionDelete::class)
        ->set('permission', $deletePermission)
        ->call('deletePermission')
        ->assertHasErrors(['permission_belongs_to_roles']);

    assertDatabaseHas('permissions', [
        'id' => $role->id,
    ]);
});

test('making sure that the traits its on in the class', function () {
    $reflection = new ReflectionClass(new PermissionDelete);

    $traits = $reflection->getTraitNames();

    expect(in_array('App\Traits\Livewire\HasConfirmation', $traits))->toBe(true)
        ->and(in_array('App\Traits\Livewire\HasToast', $traits))->toBe(true);
});
