<?php

namespace App\Livewire\Components\Roles;

use App\Models\{Role, User};
use App\Support\Table\Header;
use App\Traits\Livewire\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;

class RoleUsersList extends Component
{
    use HasTable;

    public Role $role;

    protected function tableHeaders(): array
    {
        return [
            Header::make('name', __('messages.user'), true, true),
            Header::make('email', __('messages.email'), true, true),
            Header::make('created_at', __('messages.created_at'), true, true),
        ];
    }

    protected function tableQuery(): Builder
    {
        return User::query()->role($this->role->name);
    }

    public function render(): View
    {
        return view('livewire.components.roles.role-users-list');
    }
}
