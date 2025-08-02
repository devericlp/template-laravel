<div class="py-24">

    <div class="text-center mb-10">
        <flux:heading size="xl" class="text-3xl font-extrabold" accent>
            {{ __('messages.forgot_password') }}
        </flux:heading>
        <flux:subheading>{{ __('messages.password_recovery_instruction') }}</flux:subheading>
    </div>

    <form wire:submit="submit" class="space-y-4">
        @csrf

        <flux:field>
            <flux:label>{{ __('messages.email') }}</flux:label>
            <flux:input type="email" icon="envelope" wire:model="email" placeholder="{{ __('messages.email') }}"/>
            <flux:error name="email"/>
        </flux:field>

        <flux:button type="submit" variant="primary"
                     class="w-full uppercase cursor-pointer">{{ __('messages.recover_password') }}</flux:button>
    </form>

    <flux:subheading class="text-center mt-5">
        {{ __('messages.back_to') }}
        <flux:link variant="subtle" href="{{ route('login') }}">{{ __('messages.login') }}</flux:link>
    </flux:subheading>
</div>

