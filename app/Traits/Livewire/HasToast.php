<?php

namespace App\Traits\Livewire;

use Flux\Flux;

trait HasToast
{
    public function toast(string $variant, string $heading, string $message)
    {
        Flux::toast(
            text: $message,
            heading: $heading,
            duration: 3000,
            variant: $variant
        );
    }

    public function success(string $message): void
    {
        $this->toast('success', __('messages.success'), $message);
    }

    public function warning(string $message): void
    {
        $this->toast('warning', __('messages.warning'), $message);
    }

    public function danger(string $message): void
    {
        $this->toast('danger', __('messages.danger'), $message);
    }
}
