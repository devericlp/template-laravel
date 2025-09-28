<?php

namespace App\Traits\Livewire;

use App\Support\Table\Header;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;

trait HasTable
{
    public ?string $search = null;

    public string $sortBy = 'id';

    public string $sortDirection = 'desc';

    public int $perPage = 5;

    public array $pageLengths = [
        5, 10, 25, 50,
    ];

    abstract public function tableQuery(): Builder;

    /**
     * @return Header[]
     */
    abstract public function tableHeaders(): array;

    #[Computed]
    public function items(): LengthAwarePaginator
    {
        $headers = collect($this->tableHeaders());
        $query   = $this->tableQuery();

        $searchableColumns = $headers->where('searchable', true)->pluck('key')->toArray();
        $query->search($this->search, $searchableColumns);

        $sortColumn = $headers->firstWhere('key', $this->sortBy);

        if (! is_null($sortColumn->sort)) {
            $paginated = $query->paginate($this->perPage);
            $query     = sort_paginated_collection($paginated, $sortColumn->sort, $this->sortDirection);
        } else {
            $query = $query
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);
        }

        return $query;
    }

    #[Computed]
    public function headers(): array
    {
        return $this->tableHeaders();
    }
}
