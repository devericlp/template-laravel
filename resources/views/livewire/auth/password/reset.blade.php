<div>
    <div class="text-center mb-10">
        <flux:heading size="xl" class="text-3xl font-extrabold" accent>
            {{ __('messages.reset_password') }}
        </flux:heading>
    </div>

    <form wire:submit="changePassword" class="space-y-4">
        @csrf

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

        <flux:button type="submit" variant="primary"
                     class="w-full mt-10 uppercase cursor-pointer">{{ __('messages.reset') }}</flux:button>
    </form>

    <flux:subheading class="text-center mt-5">
        {{ __('messages.back_to') }}
        <flux:link variant="subtle" href="{{ route('login') }}">{{ __('messages.login') }}</flux:link>
    </flux:subheading>
</div>
