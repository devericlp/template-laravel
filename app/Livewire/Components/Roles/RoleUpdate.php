<?php

namespace App\Livewire\Components\Roles;

use App\Models\Role;
use App\Traits\Livewire\HasToast;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\{On};
use Livewire\Component;

class RoleUpdate extends Component
{
    use HasToast;

    public ?Role $role = null;

    public ?string $name = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:roles,name,' . $this->role->id],
        ];
    }

    public function roleUpdate(): void
    {
        $this->validate();

        $this->role->update([
            'name' => $this->name,
        ]);

        $this->success(__('messages.role_updated_successfully'));

        $this->reset('name');

        Flux::modal('role-update-modal')->close();

        $this->dispatch('role::updated');
    }

    #[On('role-update-modal')]
    public function openModal(int $roleId): void
    {
        $this->resetErrorBag();
        $this->role = Role::findOrFail($roleId);
        $this->name = $this->role->name;
        Flux::modal('role-update-modal')->show();
    }

    public function render(): View
    {
        return view('livewire.components.roles.role-update');
    }
}
