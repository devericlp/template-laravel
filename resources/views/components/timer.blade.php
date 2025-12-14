@props(['minutes'])


<div x-data="timer(@js($minutes))" x-init="init()" @resend-start="restart()">
    <template x-if="totalSeconds > 0">
        <flux:text size="sm">
            {{ __('messages.resend_code_in') }} <span x-text="formatTime()"></span>
        </flux:text>
    </template>

    <template x-if="totalSeconds === 0">
        <flux:text size="sm" class="cursor-pointer hover:text-accent" wire:click="sendNewCode"
            @click="$dispatch('resend-start')">
            {{ __('messages.send_new_code') }}
        </flux:text>
    </template>
</div>
