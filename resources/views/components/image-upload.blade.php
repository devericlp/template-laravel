@php
    $value = $this->{$model};
    $is_temporary_file = $value instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
    $has_upload = $is_temporary_file;
    $upload_src = $has_upload ? $value->temporaryUrl() : null;
    $upload_name = $has_upload ? $value->getClientOriginalName() : null;
    $show_placeholder = !$has_upload && $placeholder !== null;
@endphp

<div
    x-data="{ showInitial: {{ $initialImage ? 'true' : 'false' }} }"
    class="w-full"
>
    <div
        class="group relative {{ $heigthClass }} overflow-hidden rounded-lg border border-dashed border-zinc-300 dark:border-white/10">

        @if ($has_upload)
            <img
                src="{{ $upload_src }}"
                alt="uploaded"
                class="h-full w-full object-contain"
            />
            <div
                class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 dark:bg-black/50 text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                <span class="max-w-full truncate text-sm font-semibold">{{ $upload_name }}</span>
            </div>
            <flux:button
                wire:click="{{ $removeAction }}"
                class="!absolute !right-2 !top-2 opacity-0 cursor-pointer transition-opacity duration-300 group-hover:opacity-100"
                size="sm"
                variant="filled"
            >
                {{ __('messages.remove') }}
            </flux:button>

        @endif

        <template x-if="showInitial">
            <div class="relative h-full w-full">
                <img
                    src="{{ $initialImage }}"
                    alt="Initial Image"
                    class="h-full w-full object-contain"
                />
                <div
                    class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 dark:bg-black/50 text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    <span class="max-w-full truncate text-sm font-semibold">{{ basename($initialImage) }}</span>
                </div>
                <flux:button
                    @click="showInitial = false"
                    class="!absolute !right-2 !top-2 opacity-0 cursor-pointer transition-opacity duration-300 group-hover:opacity-100"
                    size="sm"
                    variant="filled"
                >
                    {{ __('messages.remove') }}
                </flux:button>
            </div>
        </template>

        <template x-if="!showInitial && {{ $show_placeholder ? 'true' : 'false' }}">
            <label
                for="{{ $model }}"
                class="relative flex h-full w-full cursor-pointer flex-col items-center justify-center transition-colors hover:bg-zinc-100 dark:bg-zinc-700 dark:hover:bg-white/15"
            >
                <img
                    src="{{ $placeholder }}"
                    alt="placeholder"
                    class="h-full w-full object-contain"
                />
                <div
                    class="absolute inset-0 flex items-center justify-center bg-black/40 dark:bg-black/50 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    <span class="pointer-events-none text-sm text-gray-200">
                        {{ __('messages.click_here_to_add_an_image') }}
                    </span>
                </div>
                <input
                    type="file"
                    id="{{ $model }}"
                    wire:model="{{ $model }}"
                    class="hidden"
                    accept="{{ $accept }}"
                />
            </label>
        </template>

        <template
            x-if="!showInitial && !{{ $show_placeholder ? 'true' : 'false' }} && !{{ $has_upload ? 'true' : 'false' }}"
        >
            <label
                for="{{ $model }}"
                class="flex h-full w-full cursor-pointer flex-col items-center justify-center transition-colors hover:bg-zinc-100 dark:bg-zinc-700 dark:hover:bg-white/15"
            >
                <flux:icon.cloud-arrow-down class="size-8 text-gray-400"/>
                <span class="text-sm text-center text-gray-400">{{ __('messages.click_here_to_add_an_image') }}</span>
                <input
                    type="file"
                    id="{{ $model }}"
                    wire:model="{{ $model }}"
                    class="hidden"
                    accept="{{ $accept }}"
                />
            </label>
        </template>

    </div>

    @if ($errors->has($model))
        @foreach ($errors->get($model) as $error)
            <small class="text-sm text-red-500">{{ $error }}</small>
        @endforeach
    @endif
</div>
