<div>
    <x-page-header
        :title="__('messages.tenants')"
    >
        <x-slot:actions>
            <flux:button size="xs" icon="plus" variant="primary">{{ __('messages.new_tenant') }}</flux:button>
        </x-slot>
    </x-page-header>

    <flux:card class="mt-4">
        <div class="flex flex-col md:flex-row gap-2 justify-between mb-3">
            <div>
                <flux:tabs variant="segmented">
                    <flux:tab>All</flux:tab>
                    <flux:tab>Active</flux:tab>
                    <flux:tab>Inactive</flux:tab>
                </flux:tabs>
            </div>
            <div class="flex items-center space-x-2">
                <flux:input wire:model.live.debounce="search" icon="magnifying-glass" size="sm"
                            placeholder="{{ __('messages.search') }}..."/>
                <flux:dropdown>
                    <flux:button size="sm" icon="funnel">{{ __('messages.filters') }}</flux:button>
                    <flux:menu>
                        <flux:menu.item>View</flux:menu.item>
                        <flux:menu.item>Transfer</flux:menu.item>
                        <flux:menu.separator/>
                        <flux:menu.item>Publish</flux:menu.item>
                        <flux:menu.item>Share</flux:menu.item>
                        <flux:menu.separator/>
                        <flux:menu.item variant="danger">Delete</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>

        <flux:table :paginate="$this->items">
            <flux:table.columns>
                <flux:table.column sortable>{{ __('messages.identification_number') }}</flux:table.column>
                <flux:table.column sortable>{{ __('messages.social_reason') }}</flux:table.column>
                <flux:table.column sortable>{{ __('messages.subdomain') }}</flux:table.column>
                <flux:table.column sortable>{{ __('messages.status') }}</flux:table.column>
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
                                <flux:menu.item icon="envelope">{{ __('messages.send_welcome') }}</flux:menu.item>
                                <flux:menu.item
                                    icon="arrows-right-left">{{ __('messages.change_status') }}</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row class="text-center">
                    <div class="flex flex-nowrap gap-4">
                        <flux:icon.funnel/>
                        {{ __('messages.no_records_found') }}
                    </div>
                </flux:table.row>
            @endforelse
        </flux:table>
    </flux:card>

</div>

