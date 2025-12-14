<div x-data="handleTheme($flux.appearance)">

    <div class="mb-5">
        <flux:heading>{{ __('messages.language') }}</flux:heading>
        <flux:subheading>{{ __('messages.select_your_preferred_language_for_the_interface') }}</flux:subheading>
    </div>

    <div class="grid grid-cols-3">
        <div class="col-span-1">
            <flux:select variant="listbox" wire:model.live="locale">
                @foreach($supported_locales as $language)
                    <flux:select.option value="{{ $language }}">
                        @switch($language)
                            @case('en')
                                {{ __('messages.english') }}
                                @break
                            @case('pt_BR')
                                {{ __('messages.portuguese') }}
                                @break
                            @default
                                {{ $language }}
                        @endswitch
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <div class="my-5">
        <flux:heading>{{ __('messages.appearance') }}</flux:heading>
        <flux:subheading>{{ __('messages.update_the_appearance_settings_for_your_account') }}</flux:subheading>
    </div>

    <div class="w-full">
        <flux:switch label="{{ __('messages.sync_with_system') }}" x-on:click="setTheme" x-model="isThemeSystem"/>
    </div>
    <flux:radio.group
        variant="cards"
        class="max-sm:flex-col mt-10 w-full"
        x-model="$flux.appearance"
    >
        <flux:radio value="light" x-bind:disabled="isThemeSystem">
            <div class="flex flex-col w-full px-1">
                <div class="flex justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <flux:icon.sun class="mr-1"/>
                        <flux:heading class="leading-4">{{ __('messages.light_theme') }}</flux:heading>
                    </div>
                    <flux:radio.indicator/>
                </div>
                <flux:subheading size="sm">
                    {{ __('messages.switch_to_light_mode_for_a_brighter_and_more_vibrant_display') }}
                </flux:subheading>
                <img class="w-auto h-48 rounded-lg mt-4" src="{{ Vite::asset('resources/img/light-mode.png') }}"
                     alt="light_mode">
            </div>
        </flux:radio>

        <flux:radio value="dark" x-bind:disabled="isThemeSystem">
            <div class="flex flex-col w-full px-1">
                <div class="flex justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <flux:icon.moon class="mr-1"/>
                        <flux:heading class="leading-4">{{ __('messages.dark_theme') }}</flux:heading>
                    </div>
                    <flux:radio.indicator/>
                </div>
                <flux:subheading size="sm">
                    {{ __('messages.enable_dark_mode_for_eye_friendly_interface_in_low_light_conditions') }}
                </flux:subheading>
                <img class="w-auto h-48 rounded-lg mt-4" src="{{ Vite::asset('resources/img/dark-mode.png') }}"
                     alt="dark_mode">
            </div>
        </flux:radio>
    </flux:radio.group>
</div>
