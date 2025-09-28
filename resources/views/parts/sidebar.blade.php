<flux:sidebar
    class="bg-zinc-50 dark:bg-zinc-900 border-r rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700"
    sticky
    stashable
>
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>
    <flux:brand href="{{ route('home') }}" name="{{ config('app.name') }}">
        <x-slot name="logo" class="size-6 rounded-full bg-accent text-white text-xs font-bold">
            <flux:icon name="code-bracket" variant="micro"/>
        </x-slot>
    </flux:brand>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="home" href="{{ route('home') }}"
                           :current="Route::currentRouteName() === 'home'">{{ __('messages.home') }}</flux:navlist.item>

        <flux:navlist.group expandable heading="{{ __('messages.tenants') }}">
            <flux:navlist.item
                icon="list-bullet"
                href="{{ route('tenants.index') }}"
                :current="Route::currentRouteName() === 'tenants.index'"
            >
                {{ __('messages.list') }}
            </flux:navlist.item>
            <flux:navlist.item
                icon="plus"
                href="{{ route('tenants.create') }}"
                :current="Route::currentRouteName() === 'tenants.create'"
            >
                {{ __('messages.new') }}
            </flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.item icon="" href=""
        ></flux:navlist.item>
    </flux:navlist>
    <flux:spacer/>
    <flux:navlist variant="outline">
        <flux:navlist.item icon="cog-6-tooth" href="{{ route('settings') }}"
                           :current="Route::currentRouteName() === 'settings'">
            {{ __('messages.settings') }}
        </flux:navlist.item>

        <flux:navlist.item icon="document" target="_blank" href="{{ url('/telescope') }}">
            Telescope
        </flux:navlist.item>
    </flux:navlist>


    {{--Desktop dropdown--}}
    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:profile
            :name="auth()->user()->name"
            :initials="auth()->user()->initials()"
            icon:trailing="chevron-up-down"
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
</flux:sidebar>
