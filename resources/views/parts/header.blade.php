<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>
    <flux:spacer/>

    {{--Mobile dropdown--}}
    <flux:dropdown position="top" alignt="start">
        <flux:profile
            :initials="auth()->user()->initials()"
            icon:trailing="chevron-down"
        />

        <flux:menu>
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                            >
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>

                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                </div>
            </flux:menu.radio.group>
            <flux:menu.separator/>
            <flux:menu.radio.group>
                <flux:menu.item
                    href="{{ route('settings') }}"
                    icon="cog"
                    wire:navigate>
                    {{ __('messages.settings') }}
                </flux:menu.item>
            </flux:menu.radio.group>
            <flux:menu.separator/>
            <flux:menu.item @click="$dispatch('logout')"
                            icon="arrow-right-start-on-rectangle">{{ __('messages.logout') }}</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:header>
