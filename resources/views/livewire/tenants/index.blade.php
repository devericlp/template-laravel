<div>
    <x-page-header :title="__('messages.tenants')">
        <x-slot:actions>
            <flux:button href="{{ route('tenants.create') }}" size="sm" icon="plus" variant="primary" wire:navigate>
                {{ __('messages.new_tenant') }}</flux:button>
        </x-slot>
    </x-page-header>

    <livewire:components.datatable :headers="$this->tableHeaders" />

</div>
