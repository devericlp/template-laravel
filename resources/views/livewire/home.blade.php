<div>
    <x-page-header
        :title=" __('messages.welcome_home', ['project' => config('app.name')])"
        :subtitle="__('messages.welcome_user', ['name' => auth()->user()->name ?? ''])"
    />


    {{--CARDS--}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        @foreach(range(1, 4) as $i)
            <flux:card class="overflow-hidden min-w-[12rem]">
                <flux:text>Revenue</flux:text>
                <flux:heading size="xl" class="mt-2 tabular-nums">$12,345</flux:heading>
                <flux:chart class="-mx-8 -mb-8 h-[3rem]" :value="[10, 12, 11, 13, 15, 14, 16, 18, 17, 19, 21, 20]">
                    <flux:chart.svg gutter="0">
                        <flux:chart.line class="text-sky-200 dark:text-sky-400"/>
                        <flux:chart.area class="text-sky-100 dark:text-sky-400/30"/>
                    </flux:chart.svg>
                </flux:chart>
            </flux:card>

        @endforeach
    </div>


</div>
