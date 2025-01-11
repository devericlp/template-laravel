<div>
    <x-modal wire:model="modal" :title="'Details ' . $user?->name" separator persistent>
        <div class="grid grid-cols-2 gap-4">
            @if($user)
                <x-input label="Name" :value="$user->name" readonly/>
                <x-input label="Email" :value="$user->email" readonly/>
                <x-input label="Created at" :value="$user->created_at->format('d/m/Y H:i')" readonly/>
                <x-input label="Updated at" :value="$user->updated_at->format('d/m/Y H:i')" readonly/>
                @if($user->trashed())
                    <x-input label="Deleted at" :value="$user->deleted_at->format('d/m/Y H:i')" readonly/>
                    <x-input label="Deleted by" :value="$user->deletedBy->name" readonly/>
                @endif
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Close" @click="$wire.modal = false"/>
        </x-slot:actions>
    </x-modal>
</div>
