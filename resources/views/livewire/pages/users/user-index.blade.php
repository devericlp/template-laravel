<div>
    <x-page-header :title="__('messages.users')">
        <x-slot:actions>
            <flux:button href="{{ route('users.create') }}" size="sm" icon="plus" variant="primary" wire:navigate>
                {{ __('messages.new_user') }}
            </flux:button>
        </x-slot>
    </x-page-header>

    <x-datatable
        :headers="$this->headers"
        :items="$this->items"
        :page-lengths="$pageLengths"
        :per-page="$perPage"
        :search="$search"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
        selectable
    >
        <x-slot name="bulkActions">
            <flux:navmenu.item class="cursor-pointer"
                               @click="$dispatch('confirm-delete-selected-users', { selected: selected })"
                               icon="trash">{{ __('messages.delete_selected') }}</flux:navmenu.item>
            <flux:navmenu.item class="cursor-pointer"
                               @click="$dispatch('confirm-activate-selected-users', { selected: selected })"
                               icon="check">{{ __('messages.activate_selected') }}</flux:navmenu.item>
            <flux:navmenu.item class="cursor-pointer"
                               @click="$dispatch('confirm-inactivate-selected-users', { selected: selected })"
                               icon="x-mark">{{ __('messages.inactivate_selected') }}</flux:navmenu.item>

            @if(!is_null($this->filters['visibility']))
                <flux:navmenu.item class="cursor-pointer"
                                   @click="$dispatch('confirm-restore-selected-users', { selected: selected })"
                                   icon="arrow-path">{{ __('messages.restore_selected') }}</flux:navmenu.item>
            @endif
        </x-slot>

        <x-slot name="fields">
            <flux:select
                wire:model="filters.visibility"
                label="{{ __('messages.visibility') }}"
                placeholder="{{ __('messages.all') }}"
                variant="listbox"
                clearable
            >
                @foreach($visibilities as $visibility)
                    <flux:select.option value="{{ $visibility['id'] }}">{{ $visibility['name'] }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:select
                wire:model="filters.role_id"
                label="{{ __('messages.role') }}"
                placeholder="{{ __('messages.all') }}"
                variant="listbox"
                clearable
            >
                @foreach($roles as $role)
                    <flux:select.option value="{{ $role['id'] }}">{{ $role['name'] }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:select
                wire:model="filters.status"
                label="{{ __('messages.status') }}"
                placeholder="{{ __('messages.all') }}"
                variant="listbox"
                searchable
                clearable
            >
                @foreach($statuses as $status)
                    <flux:select.option value="{{ $status['id'] }}">{{ $status['name'] }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:date-picker wire:model="filters.start_date" label="{{ __('messages.start_date') }}"
                              selectable-header/>
            <flux:date-picker wire:model="filters.end_date" label="{{ __('messages.end_date') }}" selectable-header/>

            <flux:select
                wire:model="filters.tenant_id"
                label="{{ __('messages.tenant') }}"
                placeholder="{{ __('messages.all') }}"
                variant="listbox"
                searchable
                clearable
            >
                @foreach($tenants as $tenant)
                    <flux:select.option value="{{ $tenant['id'] }}">{{ $tenant['social_reason'] }}</flux:select.option>
                @endforeach
            </flux:select>
        </x-slot>

        @scope('cell_name', $user)
        <div class="flex space-x-2 items-center">
            <flux:avatar circle :name="$user->name" initials:single
                         :src="$user->avatar ? asset('storage/' . $user->avatar) : null"/>
            <span>{{ $user->name }}</span>
        </div>
        @endscope

        @scope('cell_tenant_social_reason', $user)
        {{ $user->tenant_social_reason ?? '-' }}
        @endscope

        @scope('cell_status', $user)
        <flux:badge size="sm" color="{{ $user->status->color() }}">{{ $user->status->label() }}</flux:badge>
        @endscope

        @scope('cell_created_at', $user)
        {{ $user->created_at->format('d/m/Y H:i') }}
        @endscope

        @scope('cell_actions', $user)
        <flux:button.group>
            <flux:button
                href="{{ route('users.show', ['user' => $user]) }}"
                class="cursor-pointer"
                tooltip="{{ __('messages.show') }}"
                icon:variant="outline"
                icon="eye"
                size="sm"
            />
            <flux:button
                href="{{ route('users.update', ['user' => $user]) }}"
                class="cursor-pointer"
                tooltip="{{ __('messages.edit') }}"
                icon:variant="outline"
                icon="pencil"
                size="sm"
            />

            <flux:button
                @click="$dispatch('confirm-impersonate-user', { userId: {{ $user->id }} })"
                class="cursor-pointer"
                tooltip="{{ __('messages.impersonate') }}"
                icon:variant="outline"
                icon="hat-glasses"
                size="sm"
            />

            @unless($user->trashed())
                <flux:button
                    @click="$dispatch('confirm-delete-user', { userId: {{ $user->id }} })"
                    class="cursor-pointer"
                    tooltip="{{ __('messages.delete') }}"
                    icon:variant="outline"
                    icon="trash"
                    size="sm"
                />
            @else
                <flux:button
                    @click="$dispatch('confirm-restore-user', { userId: {{ $user->id }} })"
                    class="cursor-pointer"
                    tooltip="{{ __('messages.restore') }}"
                    icon:variant="outline"
                    icon="arrow-path"
                    size="sm"
                />
            @endunless
        </flux:button.group>
        @endscope

    </x-datatable>

    @can('users.activate')
        <livewire:components.users.user-activate/>
    @endcan
    @can('users.inactivate')
        <livewire:components.users.user-inactivate/>
    @endcan
    @can('users.delete')
        <livewire:components.users.user-delete/>
    @endcan
    @can('users.restore')
        <livewire:components.users.user-restore/>
    @endcan
    @can('users.impersonate')
        <livewire:components.users.user-impersonate/>
    @endcan

</div>
