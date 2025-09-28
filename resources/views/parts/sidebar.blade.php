<flux:sidebar sticky collapsible class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.header>
        <flux:sidebar.brand href="{{ route('home') }}" name="{{ config('app.name') }}">
            <x-slot name="logo" class="size-6 rounded-full bg-accent text-white text-xs font-bold">
                <flux:icon name="code-bracket" variant="micro" />
            </x-slot>
        </flux:sidebar.brand>

        <flux:sidebar.collapse
            class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:navlist.item icon="home" href="{{ route('home') }}" :current="Route::currentRouteName() === 'home'">
            {{ __('messages.home') }}</flux:navlist.item>

        <flux:navlist.group expandable heading="{{ __('messages.tenants') }}">
            <flux:navlist.item icon="list-bullet" href="{{ route('tenants.index') }}"
                :current="Route::currentRouteName() === 'tenants.index'">
                {{ __('messages.list') }}
            </flux:navlist.item>
            <flux:navlist.item icon="plus" href="{{ route('tenants.create') }}"
                :current="Route::currentRouteName() === 'tenants.create'">
                {{ __('messages.new') }}
            </flux:navlist.item>
        </flux:navlist.group>

    </flux:sidebar.nav>

    <flux:sidebar.spacer />

    <flux:sidebar.nav>
        <flux:navlist.item icon="cog-6-tooth" href="{{ route('settings') }}"
            :current="Route::currentRouteName() === 'settings'">
            {{ __('messages.settings') }}
        </flux:navlist.item>

        <flux:navlist.item icon="document" target="_blank" href="{{ url('/telescope') }}">
            Telescope
        </flux:navlist.item>
    </flux:sidebar.nav>

    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:sidebar.profile icon:trailing="chevron-up-down" :initials="auth()->user()->initials()"
            :name="auth()->user()->name" />

        <flux:menu>
            <flux:menu.separator />

            <flux:menu.item @click="$dispatch('logout')" icon="arrow-right-start-on-rectangle">
                {{ __('messages.logout') }}
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>
