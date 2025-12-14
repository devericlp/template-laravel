<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UserShow extends Component
{
    public User $user;

    public string $tab = 'overview';

    public function mount(User $user): void
    {
        $this->user = $user;

        if (request()->has('tab')) {
            $this->tab = request()->query('tab');
        }
    }

    #[On('user::restored')]
    #[On('user::deleted')]
    public function render(): View
    {
        return view('livewire.pages.users.user-show');
    }
}
