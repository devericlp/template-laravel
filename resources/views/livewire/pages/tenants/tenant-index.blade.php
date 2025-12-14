<div>
    <x-page-header :title="__('messages.tenants')">
        <x-slot:actions>
            <flux:button href="{{ route('tenants.create') }}" size="sm" icon="plus" variant="primary" wire:navigate>
                {{ __('messages.new_tenant') }}
            </flux:button>
        </x-slot>
    </x-page-header>

    <x-datatable :headers="$this->headers" :items="$this->items" :page-lengths="$pageLengths" :per-page="$perPage" :search="$search"
        :sort-by="$sortBy" :sort-direction="$sortDirection" :link="'/tenants/{id}'">

        <x-slot name="fields">
            <flux:select wire:model="filters.status" label="{{ __('messages.status') }}">
                <flux:select.option value="">{{ __('messages.all') }}</flux:select.option>
                @foreach ($statuses as $status)
                    <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:date-picker wire:model="filters.start_date" label="{{ __('messages.start_date') }}"
                selectable-header />
            <flux:date-picker wire:model="filters.end_date" label="{{ __('messages.end_date') }}" selectable-header />
        </x-slot>

        @scope('cell_status', $tenant)
            {{ $tenant->status->label() }}
        @endscope

        @scope('cell_created_at', $tenant)
            {{ $tenant->created_at->format('d/m/Y H:i') }}
        @endscope

        @scope('cell_actions', $tenant)
            <div class="flex items-center space-x-2">
                <flux:button size="sm">Edit</flux:button>
                <flux:button size="sm" variant="danger">Delete</flux:button>
            </div>
        @endscope

    </x-datatable>

</div>
