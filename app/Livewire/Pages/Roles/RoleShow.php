<?php

namespace App\Livewire\Pages\Roles;

use App\Models\Role;
use Illuminate\View\View;
use Livewire\Component;

class RoleShow extends Component
{
    public Role $role;

    public string $tab = 'users';

    public function mount(Role $role): void
    {
        $this->role = $role;
    }

    public function render(): View
    {
        return view('livewire.pages.roles.role-show');
    }
}
