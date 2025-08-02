<div>

    <x-page-header
        :title=" __('messages.settings')"
        :subtitle="__('messages.manage_your_profile_and_account_settings')"
    />

    <flux:navbar>
        <flux:navbar.item :href="route('settings', ['tab' => 'preferences'])"
                          wire:navigate>{{ __('messages.preferences') }}</flux:navbar.item>
        <flux:navbar.item :href="route('settings', ['tab' => 'profile'])"
                          wire:navigate>{{ __('messages.profile') }}</flux:navbar.item>
        <flux:navbar.item :href="route('settings', ['tab' => 'password'])"
                          wire:navigate>{{ __('messages.password') }}</flux:navbar.item>
    </flux:navbar>

    <flux:separator variant="subtle"/>

    <div class="mt-10">
        @switch($tab)
            @case('password')
                <livewire:settings.password/>
                @break
            @case('preferences')
                <livewire:settings.preferences/>
                @break
            @case('profile')
                <livewire:settings.profile/>
                @break
        @endswitch
    </div>

</div>
