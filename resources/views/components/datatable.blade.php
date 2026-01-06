<div wire:key="datatable-page-{{ $items->currentPage() }}" x-data="{
    id: @js($id),
    selectable: @js($selectable),
    selected: @entangle('selected'),
    pageIds: {{ json_encode($getAllIds()) }},
    currentPage: @entangle('currentPage'),
    search: @entangle('search'),
    filters: @entangle('filters'),
    init() {
        this.$watch('search', value => {
            this.selected = [];
        });
        this.$watch('filters', value => {
            this.selected = [];
        });
    },
    openModal: function() {
        $flux.modal(this.id).show();
    },
    filter: function() {
        $wire.filter(this.id);
    },
    toggleAll(checked) {
        if (this.hasAllPageItemsChecked()) {
            this.removeIds();
        } else {
            this.pushIds();
        }
    },
    hasAllPageItemsChecked() {
        if (!this.pageIds.length) return false;
        const pageIds = this.pageIds.map(String);
        return pageIds.every(id => this.selected.includes(id));
    },
    pushIds() {
        const idsToAdd = this.pageIds
            .map(String)
            .filter(i => !this.selected.includes(i));
        this.selected.push(...idsToAdd);
    },
    removeIds() {
        this.selected = this.selected.filter(
            i => !this.pageIds.map(String).includes(i)
        );
    },
}">
    <flux:card class="mt-5 px-4 {{ $flat ? 'border-0 bg-transparent' : '' }}">
        <div class="flex flex-col md:flex-row justify-between items-center mb-1 pb-2">
            {{-- PAGE LENGTH AND SEARCH BAR --}}
            <div class="flex flex-col items-center md:flex-row gap-2">
                <flux:select wire:model.live="perPage" size="sm">
                    @foreach ($pageLengths as $length)
                        <flux:select.option>{{ $length }}</flux:select.option>
                    @endforeach
                </flux:select>

                <div x-cloak x-show="selectable && selected.length > 0"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    <flux:dropdown>
                        <flux:button icon="ellipsis-vertical">
                            {{ __('messages.bulk_actions') }}
                        </flux:button>
                        <flux:menu>
                            {{ $bulkActions ?? '' }}
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div class="w-64">
                    <flux:input wire:model.live.debounce="search"
                        x-on:keydown.enter="$wire.set('search', $event.target.value)"
                        placeholder="{{ __('messages.search') }}.." size="sm">
                        @if (!empty($search))
                            <x-slot name="iconTrailing">
                                <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1"
                                    wire:click="resetSearch" />
                            </x-slot>
                        @endif
                    </flux:input>
                </div>
                <div>
                    @if ($this->hasFilters())
                        <flux:avatar as="button" icon="funnel" badge="{{ $this->totalFilters() }}"
                            badge:position="top right" x-on:click="openModal()"
                            class="bg-transparent after:content-none hover:bg-zinc-800/5 dark:hover:bg-white/15
                                 text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white" />
                    @endif
                </div>
            </div>
        </div>
        {{-- FILTERS BAR --}}
        @if ($this->totalFilters() > 0)
            <div class="flex justify-between items-center gap-2 border-b border-zinc-800/10 dark:border-white/20 py-2">
                <div>
                    <span>{{ __('messages.active_filters') }}:</span>
                    @foreach ($this->activeFilterBadges() as $badge)
                        <flux:badge color="lime" size="sm">
                            {{ $badge['label'] }}: {{ $badge['value'] }}
                            <flux:badge.close wire:click="{{ $badge['action'] }}" />
                        </flux:badge>
                    @endforeach
                </div>
                <flux:button class="cursor-pointer" icon="x-mark" variant="subtle" wire:click="resetFilters" />
            </div>
        @endif

        {{-- SELECTED COUNT BAR --}}
        <div x-cloak x-show="selected.length > 0" x-transition.opacity.duration.200ms
            class="flex justify-end items-center gap-2 border-b border-zinc-800/10 dark:border-white/20 py-2">
            <flux:text>
                <span x-text="selected.length"></span>
                {{ __('messages.selected') }}
            </flux:text>
        </div>

        {{-- TABLE --}}
        <flux:table :paginate="$items">

            <flux:checkbox.group wire:model="selected">
                <flux:table.columns>

                    <template x-if="selectable">
                        <flux:table.column align="center">
                            <flux:checkbox.all x-bind:checked="hasAllPageItemsChecked" @click.prevent="toggleAll()" />
                        </flux:table.column>
                    </template>

                    @foreach ($headers as $header)
                        <flux:table.column sortable="{{ $header['sortable'] }}" align="{{ $header['align'] }}"
                            :sorted="$header['sortable'] && $sortBy === $header['key']" :direction="$sortDirection"
                            wire:click="sort('{{ $header['key'] }}')"
                            class="{{ $header['sortable'] ? 'cursor-pointer' : 'pointer-events-none' }} ">
                            @php
                                $temp_key = str_replace('.', '___', $header['key']);
                            @endphp

                            @if (isset(${'header_' . $temp_key}))
                                {{ ${'header_' . $temp_key}($header) }}
                            @else
                                {{ $header['label'] }}
                            @endif
                        </flux:table.column>
                    @endforeach
                </flux:table.columns>
                <flux:table.rows>
                    @forelse ($items as $item)
                        <flux:table.row :key="$item->id" wire:loading.remove @class([
                            $rowClasses($item),
                            'hover:bg-zinc-200 dark:hover:bg-zinc-900' => !$noHover,
                        ])>

                            <template x-if="selectable">
                                <flux:table.cell align="center">
                                    <flux:checkbox wire:model="selected" value="{{ $item->$recordKey }}" />
                                </flux:table.cell>
                            </template>

                            @foreach ($headers as $header)
                                @php
                                    $temp_key = str_replace('.', '___', $header['key']);
                                @endphp
                                <flux:table.cell align="{{ $header['align'] }}">
                                    @if ($hasLink($header))
                                        <a href="{{ $redirectLink($item) }}" wire:navigate>
                                    @endif
                                    @if (isset(${'cell_' . $temp_key}))
                                        {{ ${'cell_' . $temp_key}($item) }}
                                    @else
                                        {{ data_get($item, $header['key']) }}
                                    @endif
                                    @if ($hasLink($header))
                                        </a>
                                    @endif
                                </flux:table.cell>
                            @endforeach
                        </flux:table.row>
                    @empty
                        {{-- EMPTY ROW --}}
                        <flux:table.row key="none">
                            <flux:table.cell colspan="99" align="center">
                                {{ __('messages.no_records_found') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse


                    {{-- LOADING ROW --}}
                    <flux:table.row key="loading" class="hidden" wire:loading.remove.class="hidden">
                        <flux:table.cell colspan="100%" align="center">
                            {{ __('messages.loading') }}...
                        </flux:table.cell>
                    </flux:table.row>
                </flux:table.rows>
            </flux:checkbox.group>
        </flux:table>
    </flux:card>

    {{-- MODAL --}}
    <flux:modal name="{{ $id }}" variant="flyout" class="w-full md:w-1/3" :dismissible="false">
        <form>
            <div class="space-y-3">
                <div>
                    <flux:heading size="lg">
                        {{ __('messages.filters') }}
                    </flux:heading>
                </div>

                @if (isset($fields))
                    {{ $fields }}
                    <div class="flex">
                        <flux:spacer />
                        <flux:button type="button" variant="primary" x-on:click="filter">
                            {{ __('messages.filter') }}
                        </flux:button>
                    </div>
                @endif
            </div>
        </form>
    </flux:modal>
</div>
