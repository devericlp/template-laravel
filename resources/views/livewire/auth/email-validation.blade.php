<div class="py-24">

    <div class="text-center mb-10">
        <flux:heading size="xl" class="text-3xl font-extrabold" accent>
            {{ __('messages.verify_your_email') }}
        </flux:heading>
        <flux:subheading>{{ __('messages.email_verification_instruction') }}</flux:subheading>
    </div>

    <form wire:submit="handle" class="space-y-4">
        @csrf

        <div class="max-w-64 mx-auto">
            <flux:field>
                <flux:otp wire:model="code" length="6" />
                <flux:error name="code" />
            </flux:field>
        </div>


        <flux:button type="submit" variant="primary" class="w-full uppercase cursor-pointer">
            {{ __('messages.verify') }}
        </flux:button>
    </form>

    <div class="text-center mt-10">
        <x-timer minutes="3" />

        <flux:subheading class="text-center mt-5 cursor-pointer hover:text-accent">
            <p @click="$dispatch('logout')">{{ __('messages.logout') }}</p>
        </flux:subheading>
    </div>
</div>
