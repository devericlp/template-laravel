<?php

namespace App\Traits\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;

trait HasTable
{
    public ?string $search = null;

    public string $sortBy = 'id';

    public string $sortDirection = 'desc';

    public int $perPage = 5;

    public array $pageLengths = [
        5, 10, 25, 50
    ];

    abstract public function query(): Builder;

    abstract public function searchColumns(): array;

    abstract public function tableHeaders(): array;

    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy        = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function items(): LengthAwarePaginator
    {
        $query = $this->query();

        $query->search($this->search, $this->searchColumns());

        return $query
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }
}
