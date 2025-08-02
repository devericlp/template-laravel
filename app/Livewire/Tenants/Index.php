<?php

namespace App\Livewire\Tenants;

use App\Enums\Status;
use App\Models\Tenant;
use App\Traits\Livewire\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use HasTable;
    use WithPagination;

    public string $status_filter = 'all';

    public function query(): Builder
    {
        return Tenant::query()
            ->when($this->status_filter !== 'all', fn(Builder $q) => $q->where('status', Status::fromName($this->status_filter)));
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

    public function render(): View
    {
        return view('livewire.tenants.index', [
            'statuses' => Status::cases()
        ]);
    }
}
