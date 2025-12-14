@props([
    'id',
    'type',
    'title' => __('messages.are_you_sure_you_want_to_confirm_this_action'),
    'message' => null,
    'cancelText' => __('messages.cancel'),
    'confirmText' => __('messages.confirm'),
])

@php
    $variant = match ($type) {
        'danger' => 'danger',
        'success' => 'primary',
        'warning' => 'primary',
        default => 'primary',
    };


@endphp

<div>
    <flux:modal :name="$id" class="min-w-[22rem]" :dismissible="false" :closable="false">
        <div class="flex flex-col justify-center items-center space-y-3 py-3 px-4">
            <div @class([
                    'flex justify-center items-center rounded-full p-4',
                    'bg-red-100 dark:bg-red-500/25' => $type == 'danger',
                    'bg-yellow-100 dark:bg-yellow-500/25' => $type == 'warning',
                    'bg-green-100 dark:bg-green-500/25' => $type == 'success',
                ])>
                @switch($type)
                    @case('danger')
                        <flux:icon.trash class="text-red-600 dark:text-red-400"/>
                        @break
                    @case('warning')
                        <flux:icon.exclamation-triangle class="text-yellow-600 dark:text-yellow-400"/>
                        @break
                    @case('success')
                        <flux:icon.check class="text-green-600 dark:text-green-400"/>
                        @break
                    @default
                        <flux:icon.bell class="text-blue-600 dark:text-blue-400"/>
                @endswitch
            </div>
            <flux:heading size="lg">{{ $title }}</flux:heading>
            <flux:text>{{ $message }}</flux:text>
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-2 mt-3">
                <flux:modal.close>
                    <flux:button class="w-full cursor-pointer">{{ $cancelText }}</flux:button>
                </flux:modal.close>
                <flux:button class="w-full cursor-pointer" variant="{{ $variant }}" wire:click="executeConfirmation">
                    {{ $confirmText }}
                </flux:button>
            </div>
        </div>


    </flux:modal>
</div>

