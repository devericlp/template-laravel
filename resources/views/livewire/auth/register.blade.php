<div>
    <div class="text-center mb-10">
        <flux:heading size="xl" class="text-3xl font-extrabold" accent>
            {{ __('messages.create_account') }}
        </flux:heading>
    </div>

    <form wire:submit="submit" class="space-y-4">
        @csrf

        <flux:field>
            <flux:input type="text" icon="user" wire:model="name" placeholder="{{ __('messages.name') }}"/>
            <flux:error name="name"/>
        </flux:field>

        <flux:field>
            <flux:label>{{ __('messages.email') }}</flux:label>
            <flux:input type="email" icon="envelope" wire:model="email" placeholder="{{ __('messages.email') }}"/>
            <flux:error name="email"/>
        </flux:field>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:field>
                <flux:label>{{ __('messages.password') }}</flux:label>
                <flux:input type="password" icon="key" wire:model="password"
                            placeholder="{{ __('messages.password') }}" viewable/>
                <flux:error name="password"/>
            </flux:field>

            <flux:field>
                <flux:label>{{ __('messages.confirm_password') }}</flux:label>
                <flux:input type="password" icon="key" wire:model="password_confirmation"
                            placeholder="{{ __('messages.confirm_password') }}" viewable/>
                <flux:error name="password_confirmation"/>
            </flux:field>
        </div>

        <flux:button type="submit" variant="primary"
                     class="w-full mt-10 uppercase cursor-pointer">{{ __('messages.create') }}</flux:button>
    </form>

    <flux:subheading class="text-center mt-5">
        {{ __('messages.already_have_an_account') }}
        <flux:link variant="subtle" href="{{ route('login') }}">{{ __('messages.login') }}</flux:link>
    </flux:subheading>
</div>


