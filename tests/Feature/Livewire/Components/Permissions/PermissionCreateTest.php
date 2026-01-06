<?php

use App\Livewire\Components\Permissions\PermissionCreate;
use App\Models\Permission;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('renders successfully', function () {
    Livewire::test(PermissionCreate::class)
        ->assertStatus(200);
});

it('should be able to create a permission', function () {
    Livewire::test(PermissionCreate::class)
       ->set('name', 'Permission test')
       ->call('storePermission')
       ->assertHasNoErrors()
       ->assertDispatched('permission::created');

    assertDatabaseHas('permissions', [
        'name' => 'Permission test',
    ]);
});

it('should open the modal and reset form fields when the event is dispatched', function () {
    Livewire::test(PermissionCreate::class)
        ->dispatch('permission-create-modal')
        ->assertSet('name', null)
        ->assertSee(__('messages.new_permission'));
});

test('validate required fields', function () {
    Livewire::test(PermissionCreate::class)
       ->set('name', '')
       ->call('storePermission')
       ->assertHasErrors(['name' => 'required']);
});

test('validate unique fields', function () {
    Permission::factory()->create([
        'name' => 'Permission test',
    ]);

    Livewire::test(PermissionCreate::class)
      ->set('name', 'Permission test')
      ->call('storePermission')
      ->assertHasErrors(['name' => 'unique']);
});
