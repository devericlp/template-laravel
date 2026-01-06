<?php

namespace App\Livewire\Components\Permissions;

use App\Models\Permission;
use App\Traits\Livewire\HasToast;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\{On, Validate};
use Livewire\Component;

class PermissionCreate extends Component
{
    use HasToast;

    #[Validate(['required', 'string', 'max:255', 'unique:permissions,name'])]
    public ?string $name = null;

    public bool $modal = false;

    public function storePermission(): void
    {
        $this->validate();

        Permission::create([
            'name' => $this->name,
        ]);

        $this->success(__('messages.permission_created_successfully'));

        $this->reset('name');

        Flux::modal('permission-create-modal')->close();

        $this->dispatch('permission::created');
    }

    #[On('permission-create-modal')]
    public function openModal(): void
    {
        $this->reset('name');
        $this->resetErrorBag();
        Flux::modal('permission-create-modal')->show();
    }

    public function render(): View
    {
        return view('livewire.components.permissions.permission-create');
    }
}
