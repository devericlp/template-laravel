<?php

use App\Livewire\Components\Roles\RoleCreate;
use App\Models\Role;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('renders successfully', function () {
    Livewire::test(RoleCreate::class)
        ->assertStatus(200);
});

it('should open the modal and reset form fields when the event is dispatched', function () {
    Livewire::test(RoleCreate::class)
        ->dispatch('role-create-modal')
        ->assertSet('name', null)
        ->assertSee(__('messages.new_role'));
});

it('should create a role', function () {
    Livewire::test(RoleCreate::class)
        ->set('name', 'role test')
        ->call('storeRole')
        ->asserthasNoErrors()
        ->assertDispatched('role::created');

    assertDatabaseHas('roles', ['name' => 'role test']);
});

test('validate required fields', function () {
    Livewire::test(RoleCreate::class)
       ->set('name', '')
       ->call('storeRole')
       ->assertHasErrors(['name' => 'required']);
});

test('validate unique fields', function () {
    $existingRole = Role::factory()->create(['name' => 'Existing Role']);
    Livewire::test(RoleCreate::class)
       ->set('name', 'Existing Role')
       ->call('storeRole')
       ->assertHasErrors(['name' => 'unique']);
});

test('validate min length fields', function () {
    Livewire::test(RoleCreate::class)
       ->set('name', 'ab')
       ->call('storeRole')
       ->assertHasErrors(['name' => 'min']);
});
