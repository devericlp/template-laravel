<div>
    <x-page-header :title="__('messages.roles')">
        <x-slot:actions>
            <flux:button @click="$dispatch('role-create-modal')" size="sm" class="cursor-pointer" icon="plus"
                variant="primary" wire:navigate>
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
                @can('roles.view')
                    <flux:button href="{{ route('roles.show', ['role' => $role]) }}" class="cursor-pointer"
                        tooltip="{{ __('messages.show') }}" icon:variant="outline" icon="eye" size="sm" />
                @endcan

                @can('roles.update')
                    <flux:button @click="$dispatch('role-update-modal', { roleId: {{ $role->id }} })" class="cursor-pointer"
                        tooltip="{{ __('messages.edit') }}" icon:variant="outline" icon="pencil" size="sm" />
                @endcan

                @can('roles.delete')
                    <flux:button @click="$dispatch('confirm-delete-role', { roleId: {{ $role->id }} })"
                        class="cursor-pointer" tooltip="{{ __('messages.delete') }}" icon:variant="outline" icon="trash"
                        size="sm" />
                @endcan
            </flux:button.group>
        @endscope

    </x-datatable>

    @can('roles.create')
        <livewire:components.roles.role-create />
    @endcan
    @can('roles.update')
        <livewire:components.roles.role-update />
    @endcan
    @can('roles.delete')
        <livewire:components.roles.role-delete />
    @endcan
</div>
