<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class Datatable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public LengthAwarePaginator $items,
        public array $headers = [],
        public bool $paginate = false,
        public ?string $sortBy = 'id',
        public ?string $sortDirection = 'asc',
        public string $emptyText = 'no_records_found',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
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
