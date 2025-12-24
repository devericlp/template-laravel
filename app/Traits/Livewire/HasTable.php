<?php

namespace App\Traits\Livewire;

use App\Support\Table\{Filter, Header};
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\{Computed, On, Url};
use Livewire\WithPagination;

trait HasTable
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public array $filters = [];

    #[Url]
    public string $sortBy = 'id';

    public string $sortDirection = 'asc';

    public $selected = [];
    
    public int $currentPage = 1;

    #[Url]
    public int $perPage = 10;

    public array $pageLengths = [5, 10, 25, 50];

    abstract protected function tableHeaders(): array;

    abstract protected function tableQuery(): Builder;

    #[Computed]
    public function items(): LengthAwarePaginator
    {

        $this->initializeFilters();

        $query = $this->tableQuery();

        // Search
        $this->applySearch($query);

        $header = collect($this->tableHeaders())->firstWhere('key', $this->sortBy);
        $this->applySorting($query, $header);

        // Pagination
        $paginated = $query->paginate($this->perPage);

        // Ordenação pós - paginação se tiver closure sort
        //        if ($header && ($header->sort instanceof \Closure)) {
        //            $paginated = $this->sortPaginatedCollection($paginated, $header->sort, $this->sortDirection);
        //        }

        return $paginated;
    }

    #[Computed]
    public function headers(): array
    {
        return collect($this->tableHeaders())
            ->map(fn ($header) => [
                'key' => $header->key,
                'label' => $header->label,
                'sortable' => $header->sortable,
                'searchable' => $header->searchable,
                'disableLink' => $header->disableLink,
                'align' => $header->align,
            ])
            ->toArray();
    }

    #[Computed]
    public function activeFilterBadges(): array
    {
        $badges = [];

        // Search badge
        if (!empty($this->search)) {
            $badges[] = [
                'key' => 'search',
                'label' => __('messages.search'),
                'value' => $this->search,
                'action' => "resetSearch('search')",
            ];
        }

        // Get defined filters
        $definedFilters = method_exists($this, 'tableFilters') ? $this->tableFilters() : [];

        foreach ($definedFilters as $filter) {
            $key = $filter->key;

            // Ensure the filter key exists in the current filters
            if (!array_key_exists($key, $this->filters)) {
                $this->filters[$key] = null;
            }

            $value = $this->filters[$key];

            if ($value === null || $value === '') {
                continue;
            }

            $label = $filter->label ?? $key;

            $badges[] = [
                'key' => $key,
                'label' => __('messages.' . $label),
                'value' => $filter->resolver ? call_user_func($filter->resolver, $value) : $value,
                'action' => "resetFilter('{$key}')",
            ];
        }

        return $badges;
    }

    #[Computed]
    public function totalFilters(): int
    {
        return count($this->activeFilterBadges());
    }

    #[Computed]
    public function hasFilters(): bool
    {
        return method_exists($this, 'tableFilters') && count($this->tableFilters()) > 0;
    }

    #[On('bulk-action::completed')]
    public function resetSelected(): void
    {
        $this->reset('selected');
    }

    public function initializeFilters(): void
    {
        if (!method_exists($this, 'tableFilters')) {
            return;
        }

        foreach ($this->tableFilters() as $filter) {
            if (!array_key_exists($filter->key, $this->filters)) {
                $this->filters[$filter->key] = null;
            }
        }
    }

    protected function applySearch(Builder $query): void
    {
        if (!$this->search) {
            return;
        }

        $query->where(function ($q) {
            foreach ($this->tableHeaders() as $header) {
                if (!$header->searchable) {
                    continue;
                }

                // handler custom filter
                if ($header->filter instanceof Closure) {
                    call_user_func($header->filter, $q, $this->search);

                    continue;
                }

                // handle relation search
                if (str_contains($header->key, '.')) {
                    [$relation, $column] = explode('.', $header->key, 2);
                    $q->orWhereHas($relation, fn ($r) => $r->where($column, 'like', "%{$this->search}%"));
                } else {
                    $q->orWhere($header->key, 'like', "%{$this->search}%");
                }
            }
        });
    }

    protected function applySorting(Builder $query, ?Header $header): void
    {
        if (!$header || $header->sort instanceof \Closure) {
            return;
        }

        $model = $query->getModel();
        $tableColumns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());

        if (in_array($this->sortBy, $tableColumns, true)) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            throw new \Exception(
                "The column '{$this->sortBy}' does not exist in the table '{$model->getTable()}' and cannot be used for sorting"
            );
        }
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function resetSearch(): void
    {
        $this->reset('search');
    }

    public function resetFilters(): void
    {
        $this->reset('search');

        foreach ($this->filters as $filter => $value) {
            $this->resetFilter($filter);
        }
        $this->resetPage();
    }

    public function resetFilter(string $filter): void
    {
        $this->filters[$filter] = null;
    }

    public function filter(string $id): void
    {
        $this->modal($id)->close();
    }


    public function updatingPage($page)
    {
        $this->currentPage = $page;
    }
}
