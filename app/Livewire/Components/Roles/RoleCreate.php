<?php

namespace App\Livewire\Components\Roles;

use App\Models\Role;
use App\Traits\Livewire\HasToast;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\{On, Validate};
use Livewire\Component;

class RoleCreate extends Component
{
    use HasToast;

    #[Validate(['required', 'string', 'min:3', 'max:255', 'unique:roles,name'])]
    public ?string $name = null;

    public function storeRole(): void
    {
        $this->validate();

        Role::create([
            'name' => $this->name,
        ]);

        $this->success(__('messages.role_created_successfully'));

        $this->reset('name');

        Flux::modal('role-create-modal')->close();

        $this->dispatch('role::created');
    }

    #[On('role-create-modal')]
    public function openModal(): void
    {
        $this->reset('name');
        $this->resetErrorBag();
        Flux::modal('role-create-modal')->show();
    }

    public function render(): View
    {
        return view('livewire.components.roles.role-create');
    }
}
