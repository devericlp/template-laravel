<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\View\View;
use Livewire\Component;

class Reset extends Component
{
    public ?string $token = null;

    public function mount(): void
    {
        $this->token = request('token');

        if ($this->tokenIsNotValid()) {
            session()->flash("error", "Token invalid");
            $this->redirectRoute('login');
        }
    }

    public function render(): view
    {
        return view('livewire.auth.password.reset');
    }

    private function tokenIsNotValid(): bool
    {
        $tokens = DB::table('password_reset_tokens')->get();

        foreach ($tokens as $t) {
            if (Hash::check($this->token, $t->token)) {
                return false;
            }
        }

        return true;
    }
}
