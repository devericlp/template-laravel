<div>
    <div class="mb-5">
        <flux:heading>{{ __('messages.password') }}</flux:heading>
        <flux:subheading>{{ __('messages.ensure_your_account_is_using_a_long_random_password_to_stay_secure') }}</flux:subheading>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2">
        <form wire:submit="changePassword" class="col-span-1 space-y-4">
            <flux:field>
                <flux:label>{{ __('messages.current_password') }}</flux:label>
                <flux:input type="password" wire:model="current_password" viewable/>
                <flux:error name="current_password"/>
            </flux:field>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('messages.password') }}</flux:label>
                    <flux:input type="password" wire:model="password" viewable/>
                    <flux:error name="password"/>
                </flux:field>
                <flux:field>
                    <flux:label>{{ __('messages.confirm_password') }}</flux:label>
                    <flux:input type="password" wire:model="password_confirmation" viewable/>
                    <flux:error name="password_confirmation"/>
                </flux:field>
            </div>
            <div class="flex justify-end mt-10">
                <flux:button type="submit" class="w-full cursor-pointer" variant="primary">
                    {{ __('messages.save') }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
