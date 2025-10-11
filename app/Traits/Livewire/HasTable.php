<?php

namespace App\Traits\Livewire;

use App\Support\Table\Header;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

trait HasTable
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'id';

    public string $sortDirection = 'asc';

    public int $perPage = 10;

    public array $pageLengths = [5, 10, 25, 50];

    public array $items = [];

    public array $headers = [];

    abstract protected function tableHeaders(): array;

    abstract protected function tableQuery(): Builder;

    #[Computed]
    public function items(): LengthAwarePaginator
    {
        $query = $this->tableQuery();

        // search
//        $this->applySearch($query);

//        $header = collect($this->headers)->firstWhere('key', $this->sortBy);
//        $this->applySorting($query, $header);

        // Paginação
        $paginated = $query->paginate($this->perPage);

        //        // Ordenação pós-paginação se tiver closure sort
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
                'key'        => $header->key,
                'label'      => $header->label,
                'sortable'   => $header->sortable,
                'searchable' => $header->searchable,
                'align'      => $header->align,
            ])
            ->toArray();
    }

    protected function applySearch(Builder $query): void
    {
        if (! $this->search) {
            return;
        }

        $query->where(function ($q) {
            foreach ($this->tableHeaders() as $header) {
                if (! $header->searchable) {
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
        if (! $header || $header->sort instanceof \Closure) {
            return;
        }

        $model        = $query->getModel();
        $tableColumns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());

        if (in_array($this->sortBy, $tableColumns, true)) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            throw new \Exception(
                "The column '{$this->sortBy}' does not exist in the table '{$model->getTable()}' and cannot be used for sorting"
            );
        }
    }

}
