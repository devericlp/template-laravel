<div class="p-5">
    <flux:modal name="role-sync-permissions-modal" class="w-full md:max-w-4xl">
        <div>
            <flux:heading size="lg"> {{ __('messages.sync_permissions') }}</flux:heading>
        </div>
        <x-form class="mt-10 space-y-5" wire:submit.prevent="syncPermissions">

            <flux:checkbox.group wire:model="selectedPermissions">

                <div class="flex mb-10">
                    <flux:checkbox.all label="{{ __('messages.select_all') }}" />
                </div>

                <flux:accordion>
                    @foreach ($groupPermissions as $group => $permissions)
                        <flux:accordion.item>
                            <flux:accordion.heading>{{ ucfirst($group) }}</flux:accordion.heading>
                            <flux:accordion.content>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach ($permissions as $permission)
                                        <flux:checkbox
                                            label="{{ __('messages.' . explode('.', $permission['name'])[1]) }}"
                                            value="{{ $permission['id'] }}" checked />
                                    @endforeach
                                </div>
                            </flux:accordion.content>
                        </flux:accordion.item>
                    @endforeach
                </flux:accordion>
            </flux:checkbox.group>


            <div class="flex mt-10">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ __('messages.update') }}
                </flux:button>
            </div>
        </x-form>
    </flux:modal>
</div>
