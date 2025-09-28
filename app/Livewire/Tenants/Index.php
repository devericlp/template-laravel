<?php

namespace App\Livewire\Tenants;

use App\Enums\Status;
use App\Models\Tenant;
use App\Support\Table\Header;
use App\Traits\Livewire\TableManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\{Attributes\Computed, Component, WithPagination};

class Index extends Component
{

    use WithPagination;
    use TableManager;

    public string $status_filter = 'all';

    #[Computed()]
    public function tableQuery(): Builder
    {
         return Tenant::query()
            ->when($this->status_filter !== 'all', fn (Builder $q) => $q->where('status', Status::fromName($this->status_filter)));
    }

    #[Computed()]
    public function tableHeaders(): array
    {
        return [
            Header::make('id', 'ID', true, true),
            Header::make('social_reason', 'Razão Social', true, true),
            Header::make('identification_number', 'CNPJ', true, true),
            Header::make('subdomain', 'Subdomínio', true, true),
            Header::make('status', 'Status', true, true),
            Header::make('created_at', 'Criado em', true, true),
            Header::make('actions', 'Ações', false, false),
        ];
    }

    public function render(): View
    {
        return view('livewire.tenants.index', [
            'statuses' => Status::cases(),
        ]);
    }
}
