<div>
    <x-page-header :title="__('messages.roles')">
        <x-slot:actions>
            <flux:button href="{{ route('roles.create') }}" size="sm" icon="plus" variant="primary" wire:navigate>
                {{ __('messages.new_role') }}
            </flux:button>
        </x-slot>
    </x-page-header>

    <x-datatable :headers="$this->headers" :items="$this->items" :page-lengths="$pageLengths" :per-page="$perPage" :search="$search"
        :sort-by="$sortBy" :sort-direction="$sortDirection">

        @scope('cell_created_at', $role)
            {{ $role->created_at->format(get_format_date() . ' H:i') }}
        @endscope

        @scope('cell_actions', $role)
            <flux:button.group>
                <flux:button href="{{ route('roles.update', ['role' => $role]) }}" class="cursor-pointer"
                    tooltip="{{ __('messages.edit') }}" icon:variant="outline" icon="pencil" size="sm" />
            </flux:button.group>
        @endscope

    </x-datatable>

</div>
