<div>
    <x-page-header
        :title="__('messages.tenants')"
    >
        <x-slot:actions>
            <flux:button href="{{ route('tenants.create') }}" size="sm" icon="plus" variant="primary"
                         wire:navigate>{{ __('messages.new_tenant') }}</flux:button>
        </x-slot>
    </x-page-header>

    <flux:card class="mt-4">
        <div class="flex flex-col md:flex-row gap-2 justify-between mb-3">
            <div>
                <flux:tabs variant="segmented" wire:model.live="status_filter" size="sm">
                    <flux:tab name="all" accent="{{ empty($status_filter) }}">{{ __('messages.all') }}</flux:tab>
                    @foreach($statuses as $status)
                        <flux:tab name="{{ $status->name }}"
                                  accent="{{ $status->value === $status_filter }}">{{ $status->label() }}</flux:tab>
                    @endforeach
                </flux:tabs>
            </div>
            <div class="flex items-center space-x-2">
                <flux:input wire:model.live.debounce="search" icon="magnifying-glass" size="sm"
                            placeholder="{{ __('messages.search') }}..." clearable/>
                <flux:dropdown>
                    <flux:button size="sm" icon="funnel">{{ __('messages.filters') }}</flux:button>
                    <flux:menu>
                        <flux:menu.item>View</flux:menu.item>
                        <flux:menu.item>Transfer</flux:menu.item>
                        <flux:menu.separator/>
                        <flux:menu.submenu heading="{{ __('messages.per_page') }}">
                            <flux:menu.radio.group wire:model.live="perPage">
                                @foreach($pageLengths as $length)
                                    <flux:menu.radio checked>{{ $length }}</flux:menu.radio>
                                @endforeach
                            </flux:menu.radio.group>
                        </flux:menu.submenu>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>

        <flux:table :paginate="$this->items">
            <flux:table.columns>
                <flux:table.column
                    sortable
                    :sorted="$sortBy === 'identification_number'"
                    :direction="$sortDirection"
                    wire:click="sort('identification_number')"
                >
                    {{ __('messages.identification_number') }}
                </flux:table.column>
                <flux:table.column
                    sortable
                    :sorted="$sortBy === 'social_reason'"
                    :direction="$sortDirection"
                    wire:click="sort('social_reason')"
                >
                    {{ __('messages.social_reason') }}
                </flux:table.column>
                <flux:table.column
                    sortable
                    :sorted="$sortBy === 'subdomain'"
                    :direction="$sortDirection"
                    wire:click="sort('subdomain')"
                >
                    {{ __('messages.subdomain') }}
                </flux:table.column>
                <flux:table.column
                    sortable
                    :sorted="$sortBy === 'status'"
                    :direction="$sortDirection"
                    wire:click="sort('status')"
                >
                    {{ __('messages.status') }}
                </flux:table.column>
                <flux:table.column>{{ __('messages.actions') }}</flux:table.column>
            </flux:table.columns>

            @forelse($this->items as $item)
                <flux:table.row :key="$item->id">
                    <flux:table.cell>{{ $item->identification_number }}</flux:table.cell>
                    <flux:table.cell>{{ $item->social_reason }}</flux:table.cell>
                    <flux:table.cell>{{ $item->subdomain }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $item->status->color() }}">
                            {{ $item->status->label() }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button icon:trailing="ellipsis-vertical" variant="subtle"></flux:button>
                            <flux:menu>
                                <flux:menu.item icon="eye">{{ __('messages.show') }}</flux:menu.item>
                                <flux:menu.item icon="pencil-square">{{ __('messages.edit') }}</flux:menu.item>
                                <flux:menu.item
                                    icon="arrows-right-left">{{ __('messages.change_status') }}</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row key="no_items">
                    <flux:table.cell colspan="99" align="center">
                        <div class="flex justify-center items-center gap-2">
                            <flux:icon.funnel/>
                            {{ __('messages.no_records_found') }}
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table>
    </flux:card>

</div>

