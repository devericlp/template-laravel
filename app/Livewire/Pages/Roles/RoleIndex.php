<?php

namespace App\Livewire\Pages\Roles;

use App\Models\Role;
use App\Support\Table\Header;
use App\Traits\Livewire\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class RoleIndex extends Component
{
    use HasTable;

    public function tableQuery(): Builder
    {
        return Role::query();
    }

    public function tableHeaders(): array
    {
        return [
            Header::make('id', 'ID', true, true),
            Header::make('name', __('messages.name'), true, true),
            Header::make('created_at', __('messages.created_at'), true, true),
            Header::make('actions', __('messages.actions'), false, false),
        ];
    }

    #[On('role::created')]
    #[On('role::updated')]
    #[On('role::deleted')]
    public function render(): View
    {
        return view('livewire.pages.roles.role-index');
    }
}
