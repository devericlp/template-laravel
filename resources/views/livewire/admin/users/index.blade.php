<div>
    <x-header title="Users" separator/>

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
