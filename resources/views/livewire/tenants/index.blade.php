<div>
    <x-page-header :title="__('messages.tenants')">
        <x-slot:actions>
            <flux:button href="{{ route('tenants.create') }}" size="sm" icon="plus" variant="primary" wire:navigate>
                {{ __('messages.new_tenant') }}
            </flux:button>
        </x-slot>
    </x-page-header>


    <flux:card x-data="{ showAdvancedFilters: false }" class="mt-10" x-show="showAdvancedFilters">
        <div class="grid gap-3 grid-cols-3">
            <flux:input label="filter1"/>
            <flux:input label="filter2"/>
            <flux:input label="filter3"/>
        </div>

    </flux:card>

    {{--    <flux:card class="mt-5">--}}
    <div class="flex justify-end items-center mb-1 pb-2 mt-5">

        <div class="flex items-center gap-2">
            <div class="w-64">
                <flux:input
                    wire:model.live.debounce="search"
                    x-on:keydown.enter="$wire.set('search', $event.target.value)"
                    placeholder="{{ __('messages.search') }}.."
                    size="sm"
                >
                    @if(!empty($search))
                        <x-slot name="iconTrailing">
                            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1"
                                         wire:click="resetSearch"/>
                        </x-slot>
                    @endif
                </flux:input>
            </div>
            <div>
                <flux:dropdown position="left">
                    <flux:avatar
                        as="button"
                        icon="ellipsis-vertical"
                        badge="{{ $this->totalFilters }}"
                        badge:position="top right"
                        class="bg-transparent after:content-none hover:bg-zinc-800/5 dark:hover:bg-white/15
                             text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white"
                    />

                    <flux:menu>
                        <flux:menu.submenu heading="{{ __('messages.per_page') }}">
                            <flux:menu.radio.group wire:model.live="perPage">
                                @foreach($pageLengths as $length)
                                     @if($length === $perPage)
                                        <flux:menu.radio checked>{{ $length }}</flux:menu.radio>
                                    @else
                                    <flux:menu.radio>{{ $length }}</flux:menu.radio>
                                    @endif
                              @endforeach
                            </flux:menu.radio.group>
                        </flux:menu.submenu>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>
    </div>
    @if($this->totalFilters > 0)
        <div class="flex justify-between items-center gap-2 border-b border-zinc-800/10 dark:border-white/20 py-2">
            <div>
                <span>{{ __('messages.active_filters') }}:</span>
                @if(!empty($search))
                    <flux:badge color="lime" size="sm">
                        {{ strtolower(__('messages.search')) . ': ' . $search }}
                        <flux:badge.close wire:click="resetSearch"/>
                    </flux:badge>
                @endif
                @foreach($filters as $filter)
                    <flux:badge color="lime" size="sm">
                        {{ strtolower(__('messages.search')) . ': ' . $search }}
                        <flux:badge.close/>
                    </flux:badge>
                @endforeach
            </div>
            <flux:button class="cursor-pointer" icon="x-mark" variant="subtle" wire:click="resetFilters"/>
        </div>
    @endif


    <div class="px-4">
        <flux:table :paginate="$this->items" container:class="max-h-96">
            <flux:table.columns sticky class="bg-white dark:bg-zinc-900">
                <flux:table.column sortable :sorted="$sortBy === 'social_reason'" :direction="$sortDirection"
                                   wire:click="sort('social_reason')">
                    Social Reason
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'identification_number'"
                                   :direction="$sortDirection" wire:click="sort('identification_number')">
                    Identification Number
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection">
                    Status
                </flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse ($this->items as $item)
                    <flux:table.row :key="$item->id" class="hover:bg-zinc-200 dark:hover:bg-zinc-900">
                        <flux:table.cell>
                            {{ $item->social_reason }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $item->identification_number }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $item->status->label() }}
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row key="none">
                        <flux:table.cell colspan="3" align="center">
                            {{ __('messages.no_records_found') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    {{--    </flux:card>--}}

</div>
