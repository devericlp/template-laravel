<div>
    <x-loading />

    <x-page-header :title="__('messages.view_user', ['user' => '#' . $user->id])" :back="Str::contains(url()->previous(), route('users.index'))" back-title="{{ __('messages.go_back_list') }}" />

    <flux:card class="rounded-sm">
        <div class="flex flex-col md:flex-row items-center justify-between gap-3">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <flux:avatar class="size-24" name="{{ $user->name }}"
                    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : null }}" />
                <div class="flex flex-col items-center md:items-start">
                    <flux:heading size="xl">{{ $user->name }}</flux:heading>
                    <div class="flex flex-col md:flex-row items-center gap-2 mt-2">
                        <flux:badge icon="{{ $user->email_verified_at ? 'check' : 'x-mark' }}"
                            color="{{ $user->email_verified_at ? 'green' : 'red' }}" size="sm">
                            {{ $user->email_verified_at ? __('messages.email_verified') : __('messages.email_not_verified') }}
                        </flux:badge>
                        <flux:badge size="sm" color="{{ $user->status->color() }}">
                            {{ __('messages.status') . ': ' . $user->status->label() }}
                        </flux:badge>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <flux:button href="{{ route('users.update', ['user' => $user]) }}" size="sm" class="cursor-pointer"
                    icon="pencil">
                    {{ __('messages.edit') }}
                </flux:button>

                @if ($user->trashed())
                    <flux:button @click="$dispatch('confirm-restore-user', { userId: {{ $user->id }} })"
                        size="sm" class="cursor-pointer" icon="arrow-path" variant="outline">
                        {{ __('messages.restore') }}
                    </flux:button>
                @else
                    <flux:button @click="$dispatch('confirm-delete-user', { userId: {{ $user->id }} })"
                        size="sm" class="cursor-pointer" icon="trash" variant="danger">
                        {{ __('messages.delete') }}
                    </flux:button>
                @endif
            </div>
        </div>
    </flux:card>

    <flux:card class="rounded-sm mt-4">
        <flux:tab.group wire:model="tab">
            <flux:tabs wire:model="tab">
                <flux:tab name="overview">{{ __('messages.overview') }}</flux:tab>
            </flux:tabs>
            <flux:tab.panel name="overview">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <flux:heading>{{ __('messages.email') }}</flux:heading>
                        <flux:text>{{ $user->email }}</flux:text>
                    </div>
                    <div class="space-y-2">
                        <flux:heading>{{ __('messages.created_at') }}</flux:heading>
                        <flux:text>{{ $user->created_at->format(get_format_date()) }}</flux:text>
                    </div>
                    <div class="space-y-2">
                        <flux:heading>{{ __('messages.updated_at') }}</flux:heading>
                        <flux:text>{{ $user->updated_at->format(get_format_date()) }}</flux:text>
                    </div>
                    @if ($user->trashed())
                        <div class="space-y-2">
                            <flux:heading>{{ __('messages.deleted_at') }}</flux:heading>
                            <flux:text>{{ $user->deleted_at->format(get_format_date()) }}</flux:text>
                        </div>
                        <div class="space-y-2">
                            <flux:heading>{{ __('messages.deleted_by') }}</flux:heading>
                            <flux:text>{{ $user->deletedBy->name }}</flux:text>
                        </div>
                    @endif

                    @if (!is_null($user->restored_by))
                        <div class="space-y-2">
                            <flux:heading>{{ __('messages.restored_at') }}</flux:heading>
                            <flux:text>{{ $user->restored_at->format(get_format_date()) }}</flux:text>
                        </div>
                        <div class="space-y-2">
                            <flux:heading>{{ __('messages.restored_by') }}</flux:heading>
                            <flux:text>{{ $user->restoredBy->name }}</flux:text>
                        </div>
                    @endif
                </div>
            </flux:tab.panel>
        </flux:tab.group>
    </flux:card>


    @if ($user->trashed())
        <livewire:components.users.user-restore />
    @else
        <livewire:components.users.user-delete />
    @endif

</div>
