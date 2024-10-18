<div>
    <x-header title="Users" separator/>

    <div class="flex flex-column flex-md-row justify-between mb-4">
        <div class="w-1/3">
            <x-input
                placeholder="Search by name and email"
                class="input-sm"
                icon="o-magnifying-glass"
                wire:model.live.debounce="search"
            />
        </div>
        <div></div>
    </div>

    <x-table :headers="$this->headers" :rows="$this->users" with-pagination>
        @scope('cell_permissions', $user)
        @foreach($user->permissions as $permission)
            <x-badge :value="\Illuminate\Support\Str::ucfirst($permission->key)" class="badge-info"/>
        @endforeach
        @endscope

        @scope('actions', $user)
        <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm"/>
        @endscope
    </x-table>
</div>
