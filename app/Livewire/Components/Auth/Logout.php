<?php

namespace App\Livewire\Components\Auth;

use Livewire\Attributes\On;
use Livewire\Component;

class Logout extends Component
{
    public function render(): string
    {
        return <<<BLADE
            <div></div>
        BLADE;
    }

    #[On('logout')]
    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'));
    }
}
