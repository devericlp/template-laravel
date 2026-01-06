<div>
    <x-datatable :headers="$this->headers" :items="$this->items" :page-lengths="$pageLengths" :per-page="$perPage" :search="$search"
        :sort-by="$sortBy" :sort-direction="$sortDirection" flat>

        @scope('cell_name', $user)
            <div class="flex space-x-2 items-center">
                <flux:avatar circle :name="$user->name" initials:single
                    :src="$user->avatar ? asset('storage/' . $user->avatar) : null" />
                <span>{{ $user->name }}</span>
            </div>
        @endscope

        @scope('cell_created_at', $user)
            {{ $user->created_at->format(get_format_date() . ' H:i') }}
        @endscope

    </x-datatable>
</div>
