<?php

namespace App\Livewire\Tenants;

use App\Enums\{RecordVisibility, Status};
use App\Models\Tenant;
use App\Support\Table\Header;
use App\Traits\Livewire\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\{Attributes\Computed, Attributes\Url, Component, WithPagination};

class Index extends Component
{
    //    use HasTable;

    use WithPagination;

    #[Url]
    public string $search = '';

    public array $pageLengths = [5, 10, 25, 50];

    public array $filters = [];

    #[Url]
    public string $sortBy = 'id';

    #[Url]
    public int $perPage = 10;

    public string $sortDirection = 'asc';

    public string $recordVisibility = RecordVisibility::WithoutDeleted->value;

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed()]
    public function items()
    {
         $searchableColumns = ['id', 'social_reason', 'identification_number', 'subdomain', 'status', 'created_at'];

        return Tenant::query()
            ->when($this->search, fn (Builder $q) => $q->whereAny($searchableColumns, 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function totalFilters(): int
    {
        $totalFilters = 0;

        if ($this->search) {
            $totalFilters++;
        }

        return $totalFilters;
    }

    #[Computed]
    protected function tableQuery(): Builder
    {
        $searchableColumns = ['id', 'social_reason', 'identification_number', 'subdomain', 'status', 'created_at'];

        return Tenant::query()
            ->when($this->search, fn (Builder $q) => $q->whereAny($searchableColumns, 'like', "%{$this->search}%"));

    }

    protected function tableHeaders(): array
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

    public function resetSearch(): void
    {
        $this->reset('search');
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'filters');
    }

    public function render(): View
    {
        return view('livewire.tenants.index', [
            'statuses' => Status::cases(),
        ]);
    }
}
