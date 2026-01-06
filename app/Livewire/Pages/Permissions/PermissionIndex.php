<?php

namespace App\Livewire\Pages\Permissions;

use App\Models\Permission;
use App\Support\Table\Header;
use App\Traits\Livewire\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class PermissionIndex extends Component
{
    use HasTable;

    protected function tableHeaders(): array
    {
        return [
            Header::make('id', 'ID', true, true),
            Header::make('name', __('messages.name'), true, true),
            Header::make('title', __('messages.title'), false, false),
            Header::make('created_at', __('messages.created_at'), true, true),
            Header::make('actions', __('messages.actions'), false, false),
        ];
    }

    protected function tableQuery(): Builder
    {
        return Permission::query();
    }

    #[On('permission::created')]
    #[On('permission::updated')]
    #[On('permission::deleted')]
    public function render(): View
    {
        return view('livewire.pages.permissions.permission-index');
    }
}
