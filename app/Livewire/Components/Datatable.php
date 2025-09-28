<?php

namespace App\Livewire\Components;

use App\Traits\Livewire\TableManager;
use Closure;
use Illuminate\View\View;
use Livewire\Component;

class Datatable extends Component
{
    use TableManager;

    public string $search = '';

    public string $sortBy = 'id';

    public string $sortDirection = 'asc';

    public int $perPage = 10;

    public array $pageLengths = [5, 10, 25, 50];

    public mixed $query;

    public $headers;

    public function sort(string $key): void
    {
        if ($this->sortBy === $key) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $key;
            $this->sortDirection = 'asc';
        }
    }

    public function mount($query, array $headers = []): void
    {
        $this->query = $query;
        $this->headers = $headers;

    }

    public function render(): View|Closure|string
    {
        return <<<'blade'

            @php
                 $items = $this->getItems();
            @endphp

            <div>
                <flux:table :paginate="$items">
                    <flux:table.columns>
                        @foreach ($headers as $header)

                            <flux:table.column
                             :sortable="$header->sortable"
                                :sorted="$sortBy === $header->key"
                                :direction="$sortDirection"
                                :align="$header->align"
                            >
                                @if (isset($this->scopes['header_' . $header->key]))
                                    @scope('header_' . $header->key, $header)
                                @else
                                    {{ $header->label }}
                                @endif
                            </flux:table.column>
                        @endforeach
                    </flux:table.columns>

                    <flux:table.rows>
                        @if($items->count() > 0)
                            @foreach ($items as $item)
                                <flux:table.row :key="$item->id">
                                    @foreach ($headers as $header)
                                        <flux:table.cell :align="$header->align">
                                            @if (isset($this->scopes['cell_' . $header->key]))
                                                @scope('cell_' . $header->key, $item)
                                            @else
                                                {{ data_get($item, $header->key) }}
                                            @endif
                                        </flux:table.cell>
                                    @endforeach
                                </flux:table.row>
                            @endforeach
                        @else
                            <flux:table.row>
                                <flux:table.cell colspan="{{ count($headers) }}" align="center">
                                    Nenhum registro encontrado
                                </flux:table.cell>
                            </flux:table.row>
                        @endif
                    </flux:table.rows>
                </flux:table>
            </div>
        blade;
    }
}
