<?php

use App\Livewire\Components\Permissions\{PermissionUpdate};
use App\Models\Permission;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->permission = Permission::factory()->create(['name' => 'Test Permission']);
});

it('renders successfully', function () {
    Livewire::test(PermissionUpdate::class, ['permission' => $this->permission])
        ->assertStatus(200);
});

it('should be able to update a permission', function () {
    Livewire::test(PermissionUpdate::class, ['permission' => $this->permission])
       ->set('name', 'Permission updated')
       ->call('updatePermission')
       ->assertHasNoErrors()
       ->assertDispatched('permission::updated');

    assertDatabaseHas('permissions', [
        'id' => $this->permission->id,
        'name' => 'Permission updated',
    ]);
});

it('should open the modal and reset form fields when the event is dispatched', function () {
    Livewire::test(PermissionUpdate::class, ['permission' => $this->permission])
        ->dispatch('permission-update-modal', $this->permission->id)
        ->assertSet('name', $this->permission->name)
        ->assertSee(__('messages.update_permission', ['permission' => $this->permission->name]));
});

test('validate required fields', function () {
    Livewire::test(PermissionUpdate::class, ['permission' => $this->permission])
       ->set('name', '')
       ->call('updatePermission')
       ->assertHasErrors(['name' => 'required']);
});

test('validate unique fields', function () {
    Permission::factory()->create([
        'name' => 'Permission updated',
    ]);

    Livewire::test(PermissionUpdate::class, ['permission' => $this->permission])
      ->set('name', 'Permission updated')
      ->call('updatePermission')
      ->assertHasErrors(['name' => 'unique']);
});
