<?php

namespace App\Traits\Livewire;

use App\Support\Table\Header;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;

trait TableManager
{
    #[Computed]
    public function items(): LengthAwarePaginator
    {
        $query = $this->query;

        // Aplica pesquisa
        $this->applySearch($query);

        // Ordenação via banco ou validação
        $header = collect($this->headers)->firstWhere('key', $this->sortBy);
        $this->applySorting($query, $header);

        // Paginação
        $paginated = $query->paginate($this->perPage);

        // Ordenação pós-paginação se tiver closure sort
        if ($header && ($header->sort instanceof \Closure)) {
            $paginated = $this->sortPaginatedCollection($paginated, $header->sort, $this->sortDirection);
        }

        return $paginated;
    }

    #[Computed]
    public function headers(): array
    {

        return $this->headers;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingSortDirection()
    {
        $this->resetPage();
    }

    public function resetOnFilterChange()
    {
        $this->resetPage();
    }

    protected function applySearch(Builder $query): void
    {
        if (!$this->search) {
            return;
        }

        $query->where(function ($q) {
            foreach ($this->headers as $header) {
                if (!$header->searchable) {
                    continue;
                }

                // Filtro customizado via closure "filter"
                if ($header->filter instanceof \Closure) {
                    call_user_func($header->filter, $q, $this->search);
                    continue;
                }

                // Relacionamento simples relation.column
                if (strpos($header->key, '.') !== false) {
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

        $model        = $query->getModel();
        $tableColumns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());

        if (in_array($this->sortBy, $tableColumns, true)) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            throw new \Exception(
                "A coluna '{$this->sortBy}' não existe na tabela '{$model->getTable()}' e não pode ser usada para ordenação."
            );
        }
    }

    protected function sortPaginatedCollection(
        LengthAwarePaginator $paginatedData,
        callable $callback,
        string $direction = 'asc'
    ): LengthAwarePaginator {
        $sorted = $paginatedData->getCollection()->sortBy($callback);

        if (strtolower($direction) === 'desc') {
            $sorted = $sorted->reverse();
        }

        return new LengthAwarePaginator(
            $sorted->values(),
            $paginatedData->total(),
            $paginatedData->perPage(),
            $paginatedData->currentPage(),
            [
                'path'  => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->query(),
            ]
        );
    }
}
