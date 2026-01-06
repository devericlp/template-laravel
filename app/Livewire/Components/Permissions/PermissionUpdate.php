<?php

namespace App\Livewire\Components\Permissions;

use App\Models\Permission;
use App\Traits\Livewire\HasToast;
use Flux\Flux;
use Livewire\Attributes\{On};
use Livewire\Component;

class PermissionUpdate extends Component
{
    use HasToast;

    public ?Permission $permission = null;

    public ?string $name = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $this->permission->id],
        ];
    }

    public function updatePermission(): void
    {
        $this->validate();

        $this->permission->update([
            'name' => $this->name,
        ]);

        $this->success(__('messages.permission_updated_successfully'));

        $this->reset('name');

        Flux::modal('permission-update-modal')->close();

        $this->dispatch('permission::updated');
    }

    #[On('permission-update-modal')]
    public function openModal(int $permissionId): void
    {
        $this->resetErrorBag();
        $this->permission = Permission::findOrFail($permissionId);
        $this->name = $this->permission->name;
        Flux::modal('permission-update-modal')->show();
    }

    public function render()
    {
        return view('livewire.components.permissions.permission-update');
    }
}
