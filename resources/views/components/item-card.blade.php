@props(['item'])

@php
    $categoryIcon = match($item->category) {
        'Books', 'Review Materials', 'Thesis & Research' => 'book',
        'Gadgets', 'Electronics' => 'device',
        'Calculators', 'Engineering Tools' => 'calculator',
        'Uniforms' => 'uniform',
        'School Bags', 'Dorm Essentials' => 'bag',
        default => 'tag',
    };
@endphp

<a href="{{ route('marketplace.show', $item) }}" class="market-card group block focus:outline-none focus:ring-2 focus:ring-red-400">
    <div class="market-card-media">
        @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition duration-500 hover:scale-105" />
        @else
            <div class="market-card-placeholder">
                @switch($categoryIcon)
                    @case('book')
                        <svg viewBox="0 0 24 24"><path d="M5 4.5h11a2 2 0 0 1 2 2v13H7a2 2 0 0 0-2 2v-17Z"></path><path d="M5 4.5v13A2 2 0 0 1 7 15.5h11"></path></svg>
                        @break
                    @case('device')
                        <svg viewBox="0 0 24 24"><rect x="7" y="3.5" width="10" height="17" rx="2"></rect><path d="M10 6.5h4M11 17.5h2"></path></svg>
                        @break
                    @case('calculator')
                        <svg viewBox="0 0 24 24"><rect x="6" y="3.5" width="12" height="17" rx="2"></rect><path d="M9 7h6M9 11h.01M12 11h.01M15 11h.01M9 15h.01M12 15h.01M15 15h.01"></path></svg>
                        @break
                    @case('uniform')
                        <svg viewBox="0 0 24 24"><path d="M8 5.5 10.5 4h3L16 5.5l2 3.5-2.5 1.2V20h-7V10.2L6 9l2-3.5Z"></path></svg>
                        @break
                    @case('bag')
                        <svg viewBox="0 0 24 24"><path d="M7 8h10l1 12H6L7 8Z"></path><path d="M9 8a3 3 0 0 1 6 0"></path></svg>
                        @break
                    @default
                        <svg viewBox="0 0 24 24"><path d="M20 12 12 4H5v7l8 8 7-7Z"></path><path d="M8.5 8.5h.01"></path></svg>
                @endswitch
                <span>{{ $item->category ?: 'General Item' }}</span>
            </div>
        @endif

        @php
            $listingClasses = match($item->listing_type) {
                'sell' => 'badge-sale',
                'rent' => 'badge-rent',
                default => 'badge-base bg-white/10 text-white',
            };
        @endphp

        <span class="{{ $listingClasses }} absolute left-4 top-4">
            {{ ucfirst($item->listing_type) }}
        </span>
    </div>

    <div class="flex min-h-[14.5rem] flex-col space-y-3 p-4">
        <div>
            <h3 class="line-clamp-2 text-base font-bold leading-snug text-white">{{ $item->title }}</h3>
            <p class="mt-2 line-clamp-2 text-sm leading-6 text-[#eedcbbc7]">{{ \Illuminate\Support\Str::limit($item->description, 88) }}</p>
        </div>

        <div class="flex items-center justify-between gap-3 text-sm text-[#eedcbbcc]">
            <span class="min-w-0 truncate">{{ $item->department }}</span>
            <span class="market-card-price">{{ $item->price ? 'P' . number_format($item->price, 2) : 'Flexible' }}</span>
        </div>

        @if($item->listing_type === 'rent')
            <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-[#eedcbbcc]">
                Rental: {{ $item->minimum_rental_days ?? 1 }}-{{ $item->maximum_rental_days ?? $item->rental_duration_days ?? 1 }} day(s) · P{{ number_format($item->daily_rental_rate ?? $item->price ?? 0, 2) }}/day
            </div>
        @endif

        <div class="flex items-center justify-between gap-3 text-xs">
            <span class="market-card-bottom-pill min-w-0 max-w-[70%] truncate">
                @switch($item->category)
                    @case('Books')
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h8a2 2 0 0 1 2 2v9H7a2 2 0 0 0-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5v11a2 2 0 0 1 2-2h8"></path></svg>
                        @break
                    @case('Uniforms')
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 5.5 8.5 4h3L14 5.5l1.5 3-2 1V16H6.5V9.5l-2-1 1.5-3z"></path></svg>
                        @break
                    @case('Gadgets')
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="6.5" y="3.5" width="7" height="13" rx="1.8"></rect><path stroke-linecap="round" d="M8.5 6h3"></path></svg>
                        @break
                    @case('Calculators')
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="5.5" y="3.5" width="9" height="13" rx="1.8"></rect><path stroke-linecap="round" d="M7.5 7h5"></path></svg>
                        @break
                    @case('Supplies')
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M5 14 14 5l1 1-9 9-2.5.5L4 13z"></path></svg>
                        @break
                    @default
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M8 4.5h4l3 5.5-5 5-5-5 3-5.5z"></path></svg>
                @endswitch
                {{ $item->category ?: 'General' }}
            </span>

            @if($item->condition)
                <span class="shrink-0 font-semibold text-[#eedcbb8c]">
                    {{ str($item->condition)->replace('_', ' ')->title() }}
                </span>
            @endif
        </div>

        <div class="mt-auto flex items-center justify-between gap-3 border-t border-white/5 pt-3">
            <span class="min-w-0 truncate text-xs uppercase tracking-[0.18em] text-slate-500">
                {{ $item->course_code }} · {{ $item->user->name }}
            </span>
            <span class="market-card-view-button">View Details</span>
        </div>
    </div>
</a>
