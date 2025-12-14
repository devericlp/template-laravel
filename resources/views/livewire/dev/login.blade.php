<div class="fixed bottom-0 md:top-0 md:bottom-auto left-0 right-0 bg-zinc-900 py-2 px-4 shadow-sm">
    <div class="flex gap-3 md:flex-row items-center justify-between">

        <div class="flex items-center gap-3">
            <flux:select size="sm" wire:model="selectedUser">
                <flux:select.option value="">{{ __('messages.select_an_user') }}</flux:select.option>
                @foreach ($this->users as $user)
                    <flux:select.option value="{{ $user->id }}">
                        {{ $user->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:button size="sm" wire:click="login">
                {{ __('messages.login') }}
            </flux:button>
        </div>

        <div class="flex items-center gap-3">

            <div class="flex items-center gap-2">
                <livewire:dev.branch-env />
            </div>
        </div>

    </div>
</div>
