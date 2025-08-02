<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use App\Traits\Livewire\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use HasTable;
    use WithPagination;

    public function query(): Builder
    {
        return Tenant::query();
    }

    public function render(): View
    {
        return view('livewire.tenants.index');
    }

    public function searchColumns(): array
    {
        return [
            'social_reason',
            'fantasy_name',
            'identification_number',
            'subdomain',
        ];
    }

    public function tableHeaders(): array
    {
        // TODO: Implement tableHeaders() method.
    }
}
