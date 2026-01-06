<div>
    <x-page-header :title="__('messages.permissions')">
        <x-slot:actions>
            <flux:button @click="$dispatch('permission-create-modal')" size="sm" class="cursor-pointer" icon="plus"
                variant="primary" wire:navigate>
                {{ __('messages.new_permission') }}
            </flux:button>
        </x-slot>
    </x-page-header>

    <x-datatable :headers="$this->headers" :items="$this->items" :page-lengths="$pageLengths" :per-page="$perPage" :search="$search"
        :sort-by="$sortBy" :sort-direction="$sortDirection">

        @scope('cell_name', $permission)
            <flux:badge color="indigo">{{ $permission->name }}</flux:badge>
        @endscope

        @scope('cell_title', $permission)
            @php
                $parts = explode('.', $permission->name, 2);
            @endphp

            {{ isset($parts[1]) ? __('messages.' . $parts[1]) : '' }}
            {{ isset($parts[0]) ? strtolower(__('messages.' . $parts[0])) : '' }}
        @endscope

        @scope('cell_created_at', $role)
            {{ $role->created_at->format(get_format_date() . ' H:i') }}
        @endscope

        @scope('cell_actions', $permission)
            <flux:button.group>
                @can('permissions.update')
                    <flux:button @click="$dispatch('permission-update-modal', { permissionId: {{ $permission->id }} })"
                        class="cursor-pointer" tooltip="{{ __('messages.edit') }}" icon:variant="outline" icon="pencil"
                        size="sm" />
                @endcan

                @can('permissions.delete')
                    <flux:button @click="$dispatch('confirm-delete-permission', { permissionId: {{ $permission->id }} })"
                        class="cursor-pointer" tooltip="{{ __('messages.delete') }}" icon:variant="outline" icon="trash"
                        size="sm" />
                @endcan
            </flux:button.group>
        @endscope

    </x-datatable>

    @can('permissions.create')
        <livewire:components.permissions.permission-create />
    @endcan
    @can('permissions.update')
        <livewire:components.permissions.permission-update />
    @endcan
    @can('permissions.delete')
        <livewire:components.permissions.permission-delete />
    @endcan
</div>
