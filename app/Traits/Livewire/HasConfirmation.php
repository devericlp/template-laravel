<?php

namespace App\Traits\Livewire;

use Flux\Flux;

trait HasConfirmation
{
    public string $modalId;

    public string $typeConfirmation;

    public ?string $titleConfirmation = null;

    public ?string $messageConfirmation = null;

    public ?string $callbackConfirmation = null;

    public ?string $cancelTextConfirmation = null;

    public ?string $confirmTextConfirmation = null;

    public $paramsConfirmation = [];

    public function executeConfirmation(): void
    {
        if ($this->callbackConfirmation && method_exists($this, $this->callbackConfirmation)) {
            $this->{$this->callbackConfirmation}(...$this->paramsConfirmation);
        }

        $this->closeConfirmation();
    }

    /**
     * Function responsible to open the confirmation dialog
     */
    public function openConfirmation(string $modalId, string $type, ?string $message = null, ?string $title = null, ?string $callback = null, array $params = [], ?string $cancelText = null, ?string $confirmText = null): void
    {
        $this->modalId = $modalId;
        $this->typeConfirmation = $type;
        $this->messageConfirmation = $message;
        $this->callbackConfirmation = $callback;
        $this->titleConfirmation = $title;
        $this->paramsConfirmation = $params;
        $this->cancelTextConfirmation = $cancelText;
        $this->confirmTextConfirmation = $confirmText;
        Flux::modal($modalId)->show();

    }

    public function closeConfirmation(): void
    {
        Flux::modal($this->modalId)->close();
        $this->reset([
            'messageConfirmation',
            'titleConfirmation',
            'typeConfirmation',
            'callbackConfirmation',
            'paramsConfirmation',
            'cancelTextConfirmation',
            'confirmTextConfirmation',
        ]);
    }
}
