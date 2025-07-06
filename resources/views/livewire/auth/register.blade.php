<div>

    <flux:heading class="text-center mb-3" size="xl">{{ __('messages.register') }}</flux:heading>

    <div class="flex flex-col gap-6">
        <form wire:submit="submit" class="space-y-4">
             <flux:input
                label="{{ __('messages.name') }}"
                wire:model="name"
            />

            <flux:input
                label="{{ __('messages.email') }}"
                type="email"
                placeholder="email@example.com"
                wire:model="email"
            />

            <flux:input
                label="{{ __('messages.password') }}"
                type="password"
                wire:model="password"/>

            <flux:input
                label="{{ __('messages.confirm_password') }}"
                type="password"
                wire:model="password_confirmation" />

            <flux:button type="submit" variant="primary" class="w-full">{{ __('messages.register') }}</flux:button>
        </form>
    </div>

    <flux:subheading class="text-center mt-4">
        {{ __('messages.already_have_an_account') }}
        <flux:link href="{{ route('login') }}">{{ __('messages.login') }}</flux:link>
    </flux:subheading>
</div>


