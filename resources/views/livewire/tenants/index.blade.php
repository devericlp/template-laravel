<div>
    <x-page-header :title="__('messages.tenants')">
        <x-slot:actions>
            <flux:button href="{{ route('tenants.create') }}" size="sm" icon="plus" variant="primary" wire:navigate>
                {{ __('messages.new_tenant') }}
            </flux:button>
        </x-slot>
    </x-page-header>


    <flux:card class="mt-10">
        <div class="flex justify-end items-center mb-1 pb-2">

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
                    <flux:dropdown>
                        <flux:avatar as="button" icon="funnel" badge="{{ $this->totalFilters }}"
                                     badge:position="top right"
                                     class="bg-transparent after:content-none hover:bg-zinc-800/5 dark:hover:bg-white/15
                             text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white"
                        />

                        <flux:menu>
                            <flux:menu.group heading=" {{ __('messages.filters') }}">
                                <flux:menu.submenu heading="{{ __('messages.deleted_records') }}">
                                    <flux:menu.radio.group wire:model="recordVisibility">
                                        <flux:menu.radio
                                            value="{{ \App\Enums\RecordVisibility::WithoutDeleted->value }}">
                                            {{ __('messages.without_deleted_records') }}
                                        </flux:menu.radio>
                                        <flux:menu.radio value="{{ \App\Enums\RecordVisibility::WithDeleted->value }}">
                                            {{ __('messages.with_deleted_records') }}
                                        </flux:menu.radio>
                                        <flux:menu.radio value="{{ \App\Enums\RecordVisibility::OnlyDeleted->value }}">
                                            {{ __('messages.only_deleted_records') }}
                                        </flux:menu.radio>
                                    </flux:menu.radio.group>
                                </flux:menu.submenu>
                            </flux:menu.group>
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


        <flux:table :paginate="$this->items">
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortBy === 'social_reason'" :direction="$sortDirection">
                    Social Reason
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'identification_number'"
                                   :direction="$sortDirection">
                    Identification Number
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection">
                    Status
                </flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($this->items as $item)
                    <flux:table.row :key="$item->id" class="hover:bg-zinc-800">
                        <flux:table.cell>
                            {{ $item->social_reason }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $item->identification_number }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $item->status }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

    </flux:card>

</div>
