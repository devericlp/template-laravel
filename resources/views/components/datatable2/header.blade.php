@props([
    'filter' => null,
    'advancedFilters' => null,
])

<div x-data="{
 showfilters: false,
 toggleFilters() { this.showFilters = !this.showFilters },
}">
    <div x-show="showFilters" class="mb-2" x-collapse.duration.200ms>

        {{ $advancedFilters ?? '' }}

    </div>
    <div class="flex flex-col md:flex-row gap-2 justify-center items-center md:justify-between mb-3"
         :class="{ 'justify-center md:justify-end': showFilters }">
        <div :class="{ 'hidden': showFilters }">
            {{ $filter ?? '' }}
        </div>

        <div class="flex md:flex-row gap-4">
            <template x-if="!showFilters">
                <flux:input
                    wire:model.debounce.500ms="search"
                    placeholder="{{ __('messages.search') }}"
                    icon="magnifying-glass"
                    size="sm"
                    clearable
                />
            </template>
            <flux:button size="sm" icon="list-filter" class="cursor-pointer" x-on:click="toggleFilters"></flux:button>
        </div>
    </div>
</div>
