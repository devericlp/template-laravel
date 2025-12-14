<?php

namespace App\Livewire\Dev;

use Illuminate\Support\Facades\Process;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read string $branch
 * @property-read string $env
 */
class BranchEnv extends Component
{
    #[Computed]
    public function env(): string
    {
        return config('app.env');
    }

    #[Computed]
    public function branch(): string
    {
        $process = Process::run('git branch --show-current');

        return trim($process->output());
    }

    public function render(): string
    {
        return <<<'blade'
        <div class="flex items-center space-x-2">
            <flux:badge color="lime">{{ $this->branch }}</flux:badge>
            <flux:badge>{{ $this->env }}</flux:badge>
        </div>
        blade;
    }
}
