<?php

namespace App\Livewire\Components\Roles;

use App\Models\{Permission, Role};
use App\Support\Table\Header;
use App\Traits\Livewire\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class RolePermissionsList extends Component
{
    use HasTable;

    public Role $role;

    protected function tableHeaders(): array
    {
        return [
            Header::make('name', __('messages.permission'), true, true),
        ];
    }

    protected function tableQuery(): Builder
    {
        return Permission::query()->role($this->role->name);
    }

    #[On('permissions::synced')]
    public function render(): View
    {
        return view('livewire.components.roles.role-permissions-list');
    }
}
