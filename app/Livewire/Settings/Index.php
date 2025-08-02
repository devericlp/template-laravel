<?php

namespace App\Livewire\Settings;

use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public string $tab = 'preferences';

    public function mount(string $tab = 'preferences')
    {
        if (! in_array($tab, ['preferences', 'password', 'profile'])) {
            abort(404);
        }

        $this->tab = $tab;
    }

    public function render(): View
    {
        return view('livewire.settings.index');
    }
}
