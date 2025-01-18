<x-card title="Code" class="w-[450px]">
    <x-form class="space-y-6 px-2" wire:submit="handle" no-separator>
        <div class="flex flex-col">
            <x-pin wire:model="code" size="6" numeric/>
            @if($message = session()->get('status'))
                <p class="text-xs text-red-500">
                    {{ $message }}
                </p>
            @endif
        </div>
        <x-slot:actions>
            <x-button label="Validate" class="btn-primary w-full" type="submit" spinner="handle"/>
        </x-slot:actions>
    </x-form>

    <p class="mt-5 text-center text-sm text-gray-400">
        <a wire:click="sendNewCode" class="font-semibold text-indigo-400 hover:text-indigo-300">
            Send a new code
        </a>
    </p>

    <p @click="$dispatch('logout')" class="mt-10 text-center text-sm text-gray-400 cursor-pointer">
        Logout
    </p>
</x-card>
