<div>
    <div class="text-center mb-10">
        <flux:heading size="xl" class="text-3xl font-extrabold" accent>
            {{ __('messages.login_into_your_account') }}
        </flux:heading>
    </div>

    <form wire:submit="login" class="space-y-4">
        @csrf

        <flux:field>
            <flux:label>{{ __('messages.email') }}</flux:label>
            <flux:input type="email" icon="envelope" wire:model="email" placeholder="{{ __('messages.enter_your_email') }}" />
            <flux:error name="email"/>
        </flux:field>

        <flux:field>
            <div class="mb-3 flex justify-between">
                <flux:label>{{ __('messages.password') }}</flux:label>
                <flux:link href="{{ route('password.recovery') }}" variant="subtle" class="text-sm">
                    {{ __('messages.forgot_password') }}
                </flux:link>
            </div>
            <flux:input type="password" icon="key" wire:model="password" placeholder="{{ __('messages.enter_your_password') }}" viewable/>
            <flux:error name="password"/>
        </flux:field>
        <flux:checkbox label="{{ __('messages.remember_me') }}"/>
        <flux:button type="submit" class="w-full mt-10 uppercase cursor-pointer" variant="primary">
            {{ __('messages.login') }}
        </flux:button>
    </form>

    <flux:subheading class="text-center mt-5">
        {{ __('messages.first_time_around_here') }}
        <flux:link variant="subtle" href="{{ route('register') }}">{{ __('messages.sign_up') }}</flux:link>
    </flux:subheading>
</div>
