<div>

{{--    <div class="flex justify-end mt-10">--}}
{{--       <div class="w-64">--}}
{{--            <flux:input wire:model="search" placeholder="Search..." icon="magnifying-glass" />--}}
{{--       </div>--}}
{{--    </div>--}}

{{--    <flux:table :paginate="$items">--}}
{{--        <flux:table.columns>--}}
{{--            @foreach ($headers as $header)--}}
{{--                <flux:table.column--}}
{{--                    :sortable="$header['sortable']"--}}
{{--                    :sorted="$sortBy === $header['key']"--}}
{{--                    :direction="$sortDirection"--}}
{{--                    :align="$header['align']"--}}
{{--                >--}}
{{--                    @if (isset($this->scopes['header_' . $header['key']]))--}}
{{--                        @scope('header_' . $header['key'], $header)--}}
{{--                    @else--}}
{{--                        {{ $header['label'] }}--}}
{{--                    @endif--}}
{{--                </flux:table.column>--}}
{{--            @endforeach--}}
{{--        </flux:table.columns>--}}
{{--        <flux:table.rows>--}}
{{--            @if(count($items) > 0)--}}
{{--                @foreach ($items as $item)--}}
{{--                    <flux:table.row :key="$item->id">--}}
{{--                        @foreach ($headers as $header)--}}
{{--                            <flux:table.cell :align="$header['align']">--}}
{{--                                @if (isset($this->scopes['cell_' . $header['key']]))--}}
{{--                                    @scope('cell_' . $header['key'], $item)--}}
{{--                                @else--}}
{{--                                    {{ data_get($item, $header['key']) }}--}}
{{--                                @endif--}}
{{--                            </flux:table.cell>--}}
{{--                        @endforeach--}}
{{--                    </flux:table.row>--}}
{{--                @endforeach--}}
{{--            @else--}}
{{--                <flux:table.row>--}}
{{--                    <flux:table.cell colspan="{{ count($headers) }}" align="center">--}}
{{--                        {{ __('messages.no_records_found') }}--}}
{{--                    </flux:table.cell>--}}
{{--                </flux:table.row>--}}
{{--            @endif--}}
{{--        </flux:table.rows>--}}
{{--    </flux:table>--}}
</div>
