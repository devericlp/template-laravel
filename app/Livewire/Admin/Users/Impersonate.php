<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;

class Impersonate extends Component
{
    public function render()
    {
        return view('livewire.admin.users.impersonate');
    }

    public function impersonate(int $user_id): void
    {
        session()->put('impersonate', $user_id);
    }
}
