<?php

namespace App\Livewire\Components\Users;

use Closure;
use Illuminate\View\View;
use Livewire\Component;

class UserStopImpersonate extends Component
{
    public function stopImpersonate(): void
    {
        if (session()->has('impersonator')) {
            auth()->loginUsingId(session('impersonator'));
        }
        session()->forget(['impersonate', 'impersonator']);
        $this->redirect(route('users.index'));
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <div class="mx-2 mt-5">
               <flux:callout icon="hat-glasses" variant="secondary" inline>
                    <flux:callout.heading>{{ __("messages.access_mode") }}</flux:callout.heading>
                    <flux:callout.text>
                        {{ __("messages.you_are_impersonating_the_user_click_to_stop_impersonation", ['user' => auth()->user()->name]) }}
                    </flux:callout.text>
                    <x-slot name="actions" class="@md:h-full m-0!">
                        <flux:button class="cursor-pointer" wire:click="stopImpersonate">{{ __('messages.stop') }}</flux:button>
                    </x-slot>
                </flux:callout>
            </div>
        HTML;
    }
}
