<div class="py-24">

    <div class="text-center mb-10">
        <flux:heading size="xl" class="text-3xl font-extrabold" accent>
            {{ __('messages.verify_your_email') }}
        </flux:heading>
        <flux:subheading>{{ __('messages.email_verification_instruction') }}</flux:subheading>
    </div>

    <form wire:submit="handle" class="space-y-4">
        @csrf

        <flux:field>
            <flux:label>{{ __('messages.code') }}</flux:label>
            <flux:input wire:model="code"
                        placeholder="{{ __('messages.code') }}"/>
            <flux:error name="code"/>
        </flux:field>

        <flux:button type="submit" variant="primary"
                     class="w-full uppercase cursor-pointer">{{ __('messages.verify') }}</flux:button>
    </form>

    <div class="text-center mt-10">
       <x-timer minutes="3" />

        <flux:subheading class="text-center mt-5 cursor-pointer hover:text-accent">
            <p @click="$dispatch('logout')">{{ __('messages.logout') }}</p>
        </flux:subheading>
    </div>
</div>
