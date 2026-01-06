<div class="p-5">
    <flux:modal name="permission-update-modal" class="w-full">
        <div>
            <flux:heading size="lg"> {{ __('messages.update_permission', ['permission' => $permission?->name]) }}
            </flux:heading>
        </div>
        <x-form class="mt-10 space-y-5" wire:submit.prevent="updatePermission">
            <flux:field>
                <flux:label badge="{{ __('messages.required') }}">{{ __('messages.name') }}</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>
            <div class="flex mt-10">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ __('messages.update') }}
                </flux:button>
            </div>
        </x-form>
    </flux:modal>
</div>
