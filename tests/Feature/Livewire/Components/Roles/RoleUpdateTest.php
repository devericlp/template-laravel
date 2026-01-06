<?php

use App\Livewire\Components\Roles\RoleUpdate;
use App\Models\Role;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->role = Role::factory()->create(['name' => 'Test Role']);
});

it('renders successfully', function () {
    Livewire::test(RoleUpdate::class)
        ->assertStatus(200);
});

it('should open the modal and reset form fields when the event is dispatched', function () {
    Livewire::test(RoleUpdate::class)
        ->dispatch('role-update-modal', $this->role->id)
        ->assertSet('name', $this->role->name)
        ->assertSee(__('messages.update_role', ['role' => $this->role->name]));
});

it('should update a role', function () {
    Livewire::test(RoleUpdate::class)
        ->set('role', $this->role)
        ->set('name', 'Test role updated')
        ->call('roleUpdate')
        ->assertHasNoErrors()
        ->assertDispatched('role::updated');

    assertDatabaseHas('roles', [
        'id' => $this->role->id,
        'name' => 'Test role updated'
    ]);
});

test('validate required fields', function () {
    Livewire::test(RoleUpdate::class)
        ->set('role', $this->role)
       ->set('name', '')
       ->call('roleUpdate')
       ->assertHasErrors(['name' => 'required']);
});

test('validate unique fields', function () {
    $existingRole = Role::factory()->create(['name' => 'Existing Role']);
    Livewire::test(RoleUpdate::class)
       ->set('role', $this->role)
       ->set('name', 'Existing Role')
       ->call('roleUpdate')
       ->assertHasErrors(['name' => 'unique']);
});

test('validate min length fields', function () {
    Livewire::test(RoleUpdate::class)
        ->set('role', $this->role)
       ->set('name', 'ab')
       ->call('roleUpdate')
       ->assertHasErrors(['name' => 'min']);
});
