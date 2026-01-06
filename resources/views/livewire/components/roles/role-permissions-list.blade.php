<div>
    <x-datatable :headers="$this->headers" :items="$this->items" :page-lengths="$pageLengths" :per-page="$perPage" :search="$search"
        :sort-by="$sortBy" :sort-direction="$sortDirection" flat />
</div>
