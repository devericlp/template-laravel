<div>
    <x-loading />
    <x-page-header :title="__('messages.create_tenant')" :back="Str::contains(url()->previous(), route('tenants.index'))" back-title="{{ __('messages.go_back_list') }}" />

    <form class="mt-5" wire:submit="store">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12 md:col-span-9">
                <flux:card class="w-full">

                    <flux:accordion variant="reverse" transition exclusive>

                        <flux:accordion.item expanded>
                            <flux:accordion.heading>
                                {{ __('messages.general_info') }}

                                @if (
                                    $errors->has('social_reason') ||
                                        $errors->has('identification_number') ||
                                        $errors->has('email') ||
                                        $errors->has('cellphone') ||
                                        $errors->has('subdomain'))
                                    <flux:badge class="ml-1" size="sm" color="orange">
                                        {{ __('messages.Incomplete') }}</flux:badge>
                                @endif
                            </flux:accordion.heading>
                            <flux:accordion.content>
                                <flux:fieldset class="pt-5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <flux:field>
                                            <flux:label badge="{{ __('messages.required') }}">
                                                {{ __('messages.identification_number') }}</flux:label>
                                            <flux:input wire:model="identification_number" size="sm"
                                                icon="identification"
                                                placeholder="{{ __('messages.identification_number') }}" x-cnpj />
                                            <flux:error name="identification_number" />
                                        </flux:field>

                                        <flux:field>
                                            <flux:label badge="{{ __('messages.required') }}">
                                                {{ __('messages.social_reason') }}</flux:label>
                                            <flux:input wire:model="social_reason"
                                                placeholder="{{ __('messages.social_reason') }}" size="sm"
                                                icon="user" />
                                            <flux:error name="social_reason" />
                                        </flux:field>

                                        <flux:field>
                                            <flux:label badge="{{ __('messages.required') }}">
                                                {{ __('messages.email') }}</flux:label>
                                            <flux:input wire:model="email" size="sm" icon="envelope"
                                                placeholder="{{ __('messages.email') }}" />
                                            <flux:error name="email" />
                                        </flux:field>

                                        <flux:field>
                                            <flux:label badge="{{ __('messages.required') }}">
                                                {{ __('messages.cellphone') }}</flux:label>
                                            <flux:input wire:model="cellphone" size="sm"
                                                x-mask:dynamic="$input.replace(/[^\d]+/g, '').length > 10 ? '(99) 9 9999-9999' : '(99) 9999-9999'"
                                                icon="phone" placeholder="{{ __('messages.cellphone') }}"
                                                x-phone-validate />
                                            <flux:error name="cellphone" />
                                        </flux:field>
                                    </div>

                                    <flux:field class="w-full mt-5">
                                        <flux:label badge="{{ __('messages.required') }}">
                                            {{ __('messages.subdomain') }}</flux:label>

                                        <flux:input.group>
                                            <flux:input.group.prefix>
                                                {{ parse_url(config('app.url'))['scheme'] }}://
                                            </flux:input.group.prefix>
                                            <flux:input wire:model="subdomain" size="sm"
                                                placeholder="{{ __('messages.subdomain') }}" x-subdomain />
                                            <flux:input.group.suffix>
                                                @php
                                                    $parts_url = parse_url(config('app.url'));
                                                    $host =
                                                        $parts_url['host'] .
                                                        (isset($parts_url['port']) ? ':' . $parts_url['port'] : '');
                                                @endphp
                                                .{{ $host }}
                                            </flux:input.group.suffix>
                                        </flux:input.group>
                                        <flux:error name="subdomain" />
                                    </flux:field>
                                </flux:fieldset>
                            </flux:accordion.content>
                        </flux:accordion.item>

                        <flux:accordion.item>
                            <flux:accordion.heading>
                                {{ __('messages.address_info') }}

                                @if (
                                    $errors->has('zipcode') ||
                                        $errors->has('street') ||
                                        $errors->has('neighborhood') ||
                                        $errors->has('state') ||
                                        $errors->has('country') ||
                                        $errors->has('subdomain') ||
                                        $errors->has('number'))
                                    <flux:badge class="ml-1" size="sm" color="orange">
                                        {{ __('messages.Incomplete') }}</flux:badge>
                                @endif

                            </flux:accordion.heading>
                            <flux:accordion.content>
                                <flux:fieldset class="pt-5">

                                    <div class="flex flex-col md:flex-row gap-4">
                                        <flux:field class="w-full md:w-56">
                                            <flux:label badge="{{ __('messages.required') }}">
                                                {{ __('messages.zipcode') }}</flux:label>
                                            <flux:input.group>
                                                <flux:input wire:model="zipcode" size="sm" mask="99999-999"
                                                    x-ref="zipcode" placeholder="{{ __('messages.zipcode') }}" />
                                                <flux:input.group.suffix
                                                    class="cursor-pointer !bg-accent hover:!bg-accent/80"
                                                    x-on:click="only_digits($refs.zipcode.value).length === 8 ? $wire.searchAddress() : ''">
                                                    <flux:icon.magnifying-glass class="text-white" />
                                                </flux:input.group.suffix>
                                            </flux:input.group>
                                            <flux:error name="zipcode" />
                                        </flux:field>
                                        <flux:field class="w-full md:flex-1">
                                            <flux:label badge="{{ __('messages.required') }}">
                                                {{ __('messages.street') }}</flux:label>
                                            <flux:input wire:model="street" size="sm" icon="map-pin"
                                                placeholder="{{ __('messages.street') }}" />
                                            <flux:error name="street" />
                                        </flux:field>
                                        <flux:field class="">
                                            <flux:label>{{ __('messages.neighborhood') }}</flux:label>
                                            <flux:input wire:model="neighborhood" size="sm"
                                                icon="building-office-2"
                                                placeholder="{{ __('messages.neighborhood') }}" />
                                            <flux:error name="neighborhood" />
                                        </flux:field>
                                    </div>

                                    <div class="flex flex-col md:flex-row gap-4 mt-5">
                                        <flux:select wire:model="state" class="w-full lg:w-56"
                                            label="{{ __('messages.state') }}" size="sm"
                                            badge="{{ __('messages.required') }}"
                                            x-on:change="$event.target.value !== '' ? $wire.searchCities() : ''">
                                            <flux:select.option value="">
                                                {{ __('messages.select_state') }}
                                            </flux:select.option>
                                            @foreach ($states as $state)
                                                <flux:select.option value="{{ $state['code'] }}">
                                                    {{ $state['code'] . ' - ' . $state['name'] }}
                                                </flux:select.option>
                                            @endforeach
                                        </flux:select>

                                        <div class="w-full lg:flex-1">
                                            <flux:autocomplete wire:model="city" label="{{ __('messages.city') }}"
                                                size="sm" badge="{{ __('messages.required') }}" icon="landmark"
                                                placeholder="{{ __('messages.city') }}">
                                                @foreach ($cities as $city)
                                                    <flux:autocomplete.item>{{ $city['name'] }}
                                                    </flux:autocomplete.item>
                                                @endforeach
                                            </flux:autocomplete>
                                        </div>

                                        <flux:field class="w-full lg:w-44">
                                            <flux:label>{{ __('messages.number') }}</flux:label>
                                            <flux:input wire:model="number" size="sm" icon="hashtag"
                                                placeholder="{{ __('messages.number') }}" />
                                            <flux:error name="number" />
                                        </flux:field>
                                    </div>

                                    <div class="flex flex-col md:flex-row gap-4 mt-5">
                                        <flux:field class="sm:w-full md:w-56">

                                            <flux:autocomplete wire:model="country"
                                                label="{{ __('messages.country') }}" size="sm"
                                                badge="{{ __('messages.required') }}" icon="flag"
                                                placeholder="{{ __('messages.country') }}">
                                                @foreach ($countries as $country)
                                                    <flux:autocomplete.item>{{ $country['name'] }}
                                                    </flux:autocomplete.item>
                                                @endforeach
                                            </flux:autocomplete>
                                        </flux:field>
                                        <flux:field class="w-full md:flex-1">
                                            <flux:label>{{ __('messages.complement') }}</flux:label>
                                            <flux:input wire:model="complement" size="sm"
                                                icon="information-circle"
                                                placeholder="{{ __('messages.apartment_block') }}" />
                                            <flux:error name="complement" />
                                        </flux:field>
                                    </div>
                                </flux:fieldset>
                            </flux:accordion.content>
                        </flux:accordion.item>
                    </flux:accordion>

                    <div class="flex justify-center md:justify-end mt-5">
                        <flux:button type="submit" class="w-full sm:w-1/5 cursor-pointer" variant="primary">
                            {{ __('messages.create') }}
                        </flux:button>
                    </div>
                </flux:card>
            </div>

            <div class="col-span-12 md:col-span-3">
                <flux:card class="w-full flex flex-col justify-center space-y-3 items-center">
                    @if ($logo)
                        <div class="relative inline-block">
                            <img src="{{ $logo->temporaryUrl() }}" class="rounded-full size-32" alt="preview">
                            <span class="absolute bottom-0 right-0 block size-4 mr-4" wire:click="removeLogo">
                                <flux:tooltip content="Remove" position="bottom">
                                    <flux:icon.x-circle variant="solid" class="cursor-pointer hover:text-red-500" />
                                </flux:tooltip>
                            </span>
                        </div>
                    @else
                        <img src="{{ Vite::asset('resources/img/placeholder-tenant.png') }}"
                            class="rounded-full size-28" alt="logo">
                    @endif
                    <flux:input type="file" wire:model="logo" size="sm" accept="image/*" />
                </flux:card>
            </div>
        </div>

    </form>
</div>
