<div>

    <flux:heading class="text-center mb-3" size="xl">Welcome back</flux:heading>

    <div class="space-y-4">
        <flux:button class="w-full">
            <x-slot name="icon">
                <flux:icon.google/>
            </x-slot>
            {{ __('messages.continue_with_google') }}
        </flux:button>

        <flux:button class="w-full">
            <x-slot name="icon">
                <flux:icon.github/>
            </x-slot>
            {{ __('messages.continue_with_github') }}
        </flux:button>
    </div>

    <flux:separator class="my-5" text="or"/>

    <div class="flex flex-col gap-6">
        <form wire:submit="login" class="space-y-4">
            <flux:input
                label="{{ __('messages.email') }}"
                type="email"
                placeholder="email@example.com"
                wire:model="email"
            />

            <flux:field>
                <div class="mb-3 flex justify-between">
                    <flux:label>{{ __('messages.password') }}</flux:label>

                    <flux:link
                        href="{{ route('password.recovery') }}"
                        variant="subtle"
                        class="text-sm"
                    >
                        {{ __('messages.forgot_password') }}
                    </flux:link>
                </div>

                <flux:input type="password" wire:model="password"/>
            </flux:field>

            <flux:checkbox label="{{ __('messages.remember_me_for_30_days') }}"/>

            <flux:button type="submit" variant="primary" class="w-full">{{ __('messages.login') }}</flux:button>
        </form>
    </div>

    <flux:subheading class="text-center mt-4">
        {{ __('messages.first_time_around_here') }}
        <flux:link href="{{ route('register') }}">{{ __('messages.signup_for_free') }}</flux:link>
    </flux:subheading>
</div>
