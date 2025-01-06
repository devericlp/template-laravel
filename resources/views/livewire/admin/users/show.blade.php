<div>
    <x-drawer wire:model="modal" class="w-11/12 lg:w-1/3" right>
        <div>
            @if($user)
                <p>{{ $user->name }}</p>
                <p>{{ $user->email }}</p>
                <p>{{ $user->created_at->format('d/m/Y H:i') }}</p>
                <p>{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                @if($user->trashed())
                    <p>{{ $user->deleted_at->format('d/m/Y H:i') }}</p>
                    <p>{{ $user->deletedBy->name }}</p>
                @endif
            @endif
        </div>
        <x-button label="Close" @click="$wire.modal = false"/>
    </x-drawer>
</div>
