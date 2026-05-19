@php
    $departmentPrograms = collect($departments)->map(fn ($programs, $department) => [
        'id' => $department,
        'programs' => $programs,
    ])->values();
@endphp

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('marketplaceFilters', { open: false });
        });
    </script>
@endonce

<x-app-layout>
    <x-slot name="header">
        <div class="marketplace-hero">
            <div class="marketplace-hero-rings hidden lg:block"></div>
            <div class="relative z-10 space-y-8">
                <div class="space-y-3">
                    <p class="text-xs font-bold uppercase tracking-[0.34em] text-[#eedcbb94]">Marketplace</p>
                    <h1 class="max-w-4xl text-5xl font-black tracking-[-0.04em] text-[#fff3f1] sm:text-6xl lg:text-7xl">
                        Search first.
                        <br>
                        Filter only when <span class="bg-gradient-to-r from-[#ffb5a6] to-[#ff2b1c] bg-clip-text text-transparent">needed.</span>
                    </h1>
                    <p class="max-w-3xl text-lg leading-8 text-[#eedcbbc7]">
                        Find books, gadgets, uniforms, and other university items from student listings.
                    </p>
                </div>

                <section class="search-panel max-w-6xl">
                    <form method="GET" action="{{ route('marketplace.index') }}" class="space-y-5">
                        <input type="hidden" name="department" value="{{ request('department') }}">
                        <input type="hidden" name="program" value="{{ request('program') }}">
                        <input type="hidden" name="condition" value="{{ request('condition') }}">
                        <input type="hidden" name="category" value="{{ request('category', 'All') }}">
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">

                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center">
                            <div class="search-input-shell flex-1">
                                <span class="text-[#eedcbb80]">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 3a6 6 0 104.472 10.001l3.763 3.764 1.414-1.414-3.764-3.763A6 6 0 009 3zm-4 6a4 4 0 118 0 4 4 0 01-8 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ request('q') }}"
                                    placeholder='Search items, e.g. "Calculators"'
                                />
                            </div>

                            <div class="market-toggle-group">
                                <button type="submit" name="type" value="" class="market-toggle-btn {{ request('type', '') === '' ? 'market-toggle-btn-active' : '' }}">
                                    All
                                </button>
                                <button type="submit" name="type" value="sell" class="market-toggle-btn {{ request('type') === 'sell' ? 'market-toggle-btn-active' : '' }}">
                                    For sale
                                </button>
                                <button type="submit" name="type" value="rent" class="market-toggle-btn {{ request('type') === 'rent' ? 'market-toggle-btn-active' : '' }}">
                                    For rent
                                </button>
                            </div>

                            <button type="button" class="nav-auth-soft h-[58px] rounded-[1.2rem] px-7" @click="$store.marketplaceFilters.open = true">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h12M7 10h6M5 14h10"></path>
                                </svg>
                                Filters
                            </button>
                        </div>

                        <div class="-mx-1 overflow-x-auto">
                            <div class="chip-row px-1">
                                <a href="{{ route('marketplace.index', array_merge(request()->except('page', 'category'), ['category' => 'All'])) }}" class="category-chip {{ request('category', 'All') === 'All' ? 'category-chip-active' : '' }}">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                                        <rect x="4" y="4" width="5" height="5" rx="1"></rect>
                                        <rect x="11" y="4" width="5" height="5" rx="1"></rect>
                                        <rect x="4" y="11" width="5" height="5" rx="1"></rect>
                                        <rect x="11" y="11" width="5" height="5" rx="1"></rect>
                                    </svg>
                                    All
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('marketplace.index', array_merge(request()->except('page', 'category'), ['category' => $category])) }}" class="category-chip {{ request('category') === $category ? 'category-chip-active' : '' }}">
                                        @switch($category)
                                            @case('Books')
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h8a2 2 0 0 1 2 2v9H7a2 2 0 0 0-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5v11a2 2 0 0 1 2-2h8"></path></svg>
                                                @break
                                            @case('Uniforms')
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 5.5 8.5 4h3L14 5.5l1.5 3-2 1V16H6.5V9.5l-2-1 1.5-3z"></path></svg>
                                                @break
                                            @case('Gadgets')
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="6.5" y="3.5" width="7" height="13" rx="1.8"></rect><path stroke-linecap="round" d="M8.5 6h3"></path></svg>
                                                @break
                                            @case('Calculators')
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="5.5" y="3.5" width="9" height="13" rx="1.8"></rect><path stroke-linecap="round" d="M7.5 7h5"></path><path stroke-linecap="round" d="M8 10.5h.01M10 10.5h.01M12 10.5h.01M8 13h.01M10 13h.01M12 13h.01"></path></svg>
                                                @break
                                            @case('Supplies')
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M5 14 14 5l1 1-9 9-2.5.5L4 13z"></path></svg>
                                                @break
                                            @default
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M8 4.5h4l3 5.5-5 5-5-5 3-5.5z"></path></svg>
                                        @endswitch
                                        {{ $category }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </x-slot>

    <div
        class="page-wrap space-y-6"
        x-data="{
            departmentPrograms: @js($departmentPrograms),
            selectedDepartment: @js(request('department')),
            selectedType: @js(request('type', '')),
            get currentPrograms() {
                const match = this.departmentPrograms.find((group) => group.id === this.selectedDepartment);
                return match ? match.programs : [];
            }
        }"
    >
        <x-flash-messages />

        <div x-cloak x-show="$store.marketplaceFilters.open" x-transition.opacity class="fixed inset-0 z-40 bg-black/60" @click="$store.marketplaceFilters.open = false"></div>

        <aside
            x-cloak
            x-show="$store.marketplaceFilters.open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="glass-panel fixed right-0 top-0 z-50 h-full w-full max-w-lg overflow-y-auto rounded-none border-y-0 border-r-0 p-6 shadow-2xl"
        >
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <div class="section-kicker">Marketplace Filters</div>
                    <h2 class="mt-2 text-2xl font-black text-white">Filter Drawer</h2>
                </div>
                <button type="button" class="btn-ghost" @click="$store.marketplaceFilters.open = false">Close</button>
            </div>

            <form method="GET" action="{{ route('marketplace.index') }}" class="space-y-5">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="type" value="{{ request('type') }}">
                <input type="hidden" name="category" value="{{ request('category', 'All') }}">
                <input type="hidden" name="sort" value="{{ $sort }}">

                <div>
                    <label class="text-sm font-medium text-[#eedcbb]">Department</label>
                    <select name="department" x-model="selectedDepartment" class="glass-input">
                        <option value="">All departments</option>
                        @foreach($departments as $department => $programs)
                            <option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-[#eedcbb]">Program</label>
                    <select name="program" class="glass-input">
                        <option value="">All programs</option>
                        <template x-for="program in currentPrograms" :key="program">
                            <option :value="program" x-text="program" :selected="program === @js(request('program'))"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-[#eedcbb]">Condition</label>
                    <select name="condition" class="glass-input">
                        <option value="">Any condition</option>
                        @foreach($conditions as $condition)
                            <option value="{{ $condition }}" @selected(request('condition') === $condition)>{{ str($condition)->replace('_', ' ')->title() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-[#eedcbb]">Min Price (PHP)</label>
                        <input type="number" step="0.01" min="0" name="min_price" value="{{ request('min_price') }}" class="glass-input" placeholder="₱0" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#eedcbb]">Max Price (PHP)</label>
                        <input type="number" step="0.01" min="0" name="max_price" value="{{ request('max_price') }}" class="glass-input" placeholder="₱5000" />
                    </div>
                </div>

                <div class="flex gap-3 pt-3">
                    <a href="{{ route('marketplace.index') }}" class="btn-secondary flex-1">Reset</a>
                    <button type="submit" class="btn-primary flex-1">Apply</button>
                </div>
            </form>
        </aside>

        <div class="sticky-toolbar flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-lg text-[#eedcbbd0]">
                <span class="text-2xl font-black text-[#ff2b1c]">{{ $items->total() }}</span> items found
            </div>

            <form method="GET" action="{{ route('marketplace.index') }}" class="sort-shell">
                @foreach(request()->except('sort', 'page') as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $entry)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $entry }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <label class="text-sm text-[#eedcbbd0]">Sort by</label>
                <select name="sort" class="bg-transparent pr-2 text-sm font-semibold text-white outline-none" onchange="this.form.submit()">
                    <option value="newest" @selected($sort === 'newest')>Newest first</option>
                    <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                    <option value="price_low" @selected($sort === 'price_low')>Lowest price</option>
                    <option value="price_high" @selected($sort === 'price_high')>Highest price</option>
                </select>
            </form>
        </div>

        @if($items->count())
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($items as $item)
                    <x-item-card :item="$item" />
                @endforeach
            </div>
            <div>{{ $items->links() }}</div>
        @else
            <div class="glass-card px-6 py-16 text-center">
                <h3 class="text-2xl font-black text-white">No matching items found</h3>
                <p class="mt-3 text-sm text-[#eedcbbcc]">Try changing the search term, category, or filters.</p>
            </div>
        @endif
    </div>
</x-app-layout>
