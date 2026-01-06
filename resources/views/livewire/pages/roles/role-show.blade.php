<div>
    <x-loading />

    <x-page-header :title="__('messages.view_role', ['role' => $role->name])" :back="Str::contains(url()->previous(), route('roles.index'))" back-title="{{ __('messages.go_back_list') }}" />

    <flux:card class="rounded-sm">
        <div class="flex flex-col md:flex-row items-center justify-between gap-3">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <flux:badge size="sm">
                    <flux:icon.identification class="size-12" variant="outline" />
                </flux:badge>
                <div class="flex flex-col items-center md:items-start">
                    <flux:heading size="xl">{{ $role->name }}</flux:heading>
                </div>
            </div>
            <div class="flex gap-2">
                @can('roles.update')
                    <flux:button @click="$dispatch('role-update-modal', { roleId: {{ $role->id }} })" size="sm"
                        class="cursor-pointer" icon="pencil">
                        {{ __('messages.edit') }}
                    </flux:button>
                @endcan

                @can('roles.delete')
                    <flux:button @click="$dispatch('confirm-delete-role', { roleId: {{ $role->id }} })" size="sm"
                        class="cursor-pointer" icon="trash" variant="danger">
                        {{ __('messages.delete') }}
                    </flux:button>
                @endcan
            </div>
        </div>
    </flux:card>

    <flux:card class="rounded-sm mt-4">
        <flux:tab.group wire:model="tab">
            <flux:tabs wire:model="tab">
                <flux:tab name="users">{{ __('messages.users') }}</flux:tab>
                <flux:tab name="permissions">{{ __('messages.permissions') }}</flux:tab>
            </flux:tabs>
            <flux:tab.panel name="users">
                <livewire:components.roles.role-users-list :role="$role" />
            </flux:tab.panel>
            <flux:tab.panel name="permissions">
                <div class="flex flex-col">

                    <div class="flex justify-end">
                        <flux:button @click="$dispatch('role-sync-permissions-modal', { roleId: {{ $role->id }} })"
                            icon="arrow-path" variant="primary" class="cursor-pointer">
                            {{ __('messages.sync_permissions') }}</flux:button>
                    </div>

                    <livewire:components.roles.role-permissions-list :role="$role" flat />
                </div>
            </flux:tab.panel>
        </flux:tab.group>
    </flux:card>

    @can('roles.update')
        <livewire:components.roles.role-update />
    @endcan
    @can('roles.delete')
        <livewire:components.roles.role-delete />
    @endcan
    @can('permissions.update')
        <livewire:components.roles.role-sync-permissions :role="$role" />
    @endcan
</div>
