<div x-data="{ showFilters: false, toggleFilters() { this.showFilters = !this.showFilters } }">
    <x-header title="Users" separator/>

    <div x-show="showFilters" class="flex mb-14 items-center space-x-4">
        <x-choices
            Label="Search by permissions"
            wire:model.live="search_permissions"
            :options="$permissions_to_search"
            option-label="key"
            search-function="filterPermissions"
            searchable
            multiple
            no-result-text="Nothing here"
        />
        <x-select label="Show deleted users" :options="$this->filterShowDeletedUsers" wire:model.live="search_trash"/>
    </div>

    <div class="mb-4 flex justify-between">
        <x-select :options="$this->filterPerPage" wire:model.live="perPage"/>
        <div class="flex space-x-4">
            <x-input
                icon="o-magnifying-glass"
                placeholder="Search.."
                wire:model.live.debounce="search"
                clearable
            />
            <x-button @click="toggleFilters()" label="Filters" icon="o-funnel" class="btn-primary"/>
        </div>
    </div>

    <x-table :headers="$this->headers" :rows="$this->users" :sort-by="$sortBy" with-pagination>
        @scope('cell_permissions', $user)
        @foreach($user->permissions as $permission)
            <x-badge :value="\Illuminate\Support\Str::ucfirst($permission->key)" class="badge-info"/>
        @endforeach
        @endscope

        @scope('cell_created_at', $user)
        {{ $user->created_at->format('d/m/Y') }}
        @endscope

        @php
            /** @var \App\Models\User $user */
        @endphp
        @scope('actions', $user)

        @unless($user->trashed())
            <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm btn-ghost"/>
        @else
            <x-button icon="o-arrow-path" wire:click="restore({{ $user->id }})" spinner class="btn-sm btn-ghost"/>
        @endunless
        @endscope
    </x-table>
</div>
