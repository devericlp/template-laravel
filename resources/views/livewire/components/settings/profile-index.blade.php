<div>
    <div class="mb-5">
        <flux:heading>{{ __('messages.profile') }}</flux:heading>
        <flux:subheading>{{ __('messages.update_your_name_and_email_address') }}</flux:subheading>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2">
        <form wire:submit="updateProfile" class="col-span-1 space-y-4">
            <flux:field>
                <flux:label>{{ __('messages.name') }}</flux:label>
                <flux:input wire:model="name"/>
                <flux:error name="name"/>
            </flux:field>
            <flux:field>
                <flux:label>{{ __('messages.email') }}</flux:label>
                <flux:input wire:model="email" type="email"/>
                <flux:error name="email"/>
                 <flux:description>{{ __('messages.email_changes_must_be_verified_again') }}</flux:description>
            </flux:field>
            <div class="flex justify-end mt-10">
                <flux:button type="submit" class="w-full cursor-pointer" variant="primary">
                    {{ __('messages.save') }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
