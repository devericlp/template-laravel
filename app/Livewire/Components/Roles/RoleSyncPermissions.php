<?php

namespace App\Livewire\Components\Roles;

use App\Models\{Permission, Role};
use App\Traits\Livewire\HasToast;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\{On, Validate};
use Livewire\Component;

class RoleSyncPermissions extends Component
{
    use HasToast;

    public Role $role;

    public array $groupPermissions = [];

    #[Validate(['array'])]
    public array $selectedPermissions = [];

    public function syncPermissions(): void
    {
        $this->validate();

        if (count($this->selectedPermissions) === 0) {
            $this->role->permissions()->detach();
        } else {
            $this->role->permissions()->sync($this->selectedPermissions);
        }

        $this->success(__('messages.permissions_synced_successfully'));

        Flux::modal('role-sync-permissions-modal')->close();

        $this->dispatch('permissions::synced');
    }

    #[On('role-sync-permissions-modal')]
    public function openModal(int $roleId): void
    {
        $this->resetErrorBag();
        $this->role = Role::findOrFail($roleId);
        $this->selectedPermissions = $this->role->permissions()->pluck('id')->toArray();
        Flux::modal('role-sync-permissions-modal')->show();
    }

    public function mount(): void
    {
        $this->groupPermissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        })->toArray();
    }

    public function render(): View
    {
        return view('livewire.components.roles.role-sync-permissions');
    }
}
