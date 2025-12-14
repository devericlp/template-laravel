<div>
    <x-loading/>
    <x-page-header
        :title="__('messages.create_user')"
        :back="Str::contains(url()->previous(), route('users.index'))"
        back-title="{{ __('messages.go_back_list') }}"
    />

    <x-form class="mt-5" wire:submit="store">
        <div class="grid grid-cols-12 gap-5">

            <div class="col-span-12 md:col-span-9 order-2 md:order-1">
                <flux:card class="w-full">

                    <flux:fieldset class="pt-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <flux:input
                                wire:model="name"
                                label="{{ __('messages.name') }}"
                                badge="{{ __('messages.required') }}"
                            />
                            <flux:input
                                type="email"
                                wire:model="email"
                                label="{{ __('messages.email') }}"
                                badge="{{ __('messages.required') }}"
                            />

                            <flux:select
                                wire:model="role_id"
                                variant="listbox"
                                label="{{ __('messages.role') }}"
                                placeholder="{{ __('messages.choose_role') }}"
                                badge="{{ __('messages.required') }}"
                            >
                                @foreach($roles as $role)
                                    <flux:select.option
                                        value="{{ $role['id'] }}">{{ $role['name'] }}</flux:select.option>
                                @endforeach
                            </flux:select>

                            <flux:select
                                wire:model="tenant_id"
                                variant="listbox"
                                label="{{ __('messages.tenant') }}"
                                placeholder="{{ __('messages.choose_tenant') }}"
                                badge="{{ __('messages.required') }}"
                            >
                                @foreach($tenants as $tenant)
                                    <flux:select.option
                                        value="{{ $tenant['id'] }}">{{ $tenant['identification_number'] . ' - ' . $tenant['social_reason'] }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:input type="password" wire:model="password"
                                        label="{{ __('messages.password') }}" viewable/>
                            <flux:input type="password" wire:model="password_confirmation"
                                        label="{{ __('messages.password_confirmation') }}" viewable/>
                        </div>
                    </flux:fieldset>

                    <div class="flex justify-center md:justify-end mt-5">
                        <flux:button type="submit" class="w-full sm:w-1/5 cursor-pointer" variant="primary">
                            {{ __('messages.create') }}
                        </flux:button>
                    </div>
                </flux:card>
            </div>

            <div class="col-span-12 md:col-span-3 order-1 md:order-2">
                <x-image-upload
                    model="avatar"
                    removeAction="removeAvatar"
                />
            </div>
        </div>
    </x-form>
</div>

