@props([
    'title',
    'subtitle' => null,
    'actions' => null,
    'back' => false,
    'backTitle' => null
])

<div
    class="flex md:flex-row justify-between items-center sm:items-end"
    x-data="{ isMobile: window.innerWidth < 640 }"
    x-init="window.addEventListener('resize', () => isMobile = window.innerWidth < 640)"
    x-cloak
>
    <div class="flex flex-col">
        @if($back)
            <flux:link href="javascript:history.back()" variant="subtle">
                <div class="flex flex-nowrap flex-row text-xs gap-2">
                    <flux:icon.arrow-left class="size-4"/>
                    {{ $backTitle ?? strtolower(__('messages.back')) }}
                </div>
            </flux:link>
        @endif
        <flux:heading size="xl" level="1" class="mt-1">{{ $title }}</flux:heading>

        @if($subtitle)
            <flux:subheading size="lg" class="mb-6">{{ $subtitle }}</flux:subheading>
        @endif

    </div>
    <div class="flex flex-col sm:flex-row gap-2">
        @if($actions)
            <div x-show="isMobile" class="relative">
                <flux:dropdown>
                    <flux:button icon:trailing="ellipsis-vertical" variant="subtle"></flux:button>
                    <flux:menu>
                        <div class="flex flex-col space-y-2">
                            {{ $actions }}
                        </div>
                    </flux:menu>
                </flux:dropdown>
            </div>
            <div class="hidden sm:flex flex-row gap-2">{{ $actions }}</div>
        @endif
    </div>
</div>
