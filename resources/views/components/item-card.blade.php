@props(['item'])

<a href="{{ route('marketplace.show', $item) }}" class="market-card group block focus:outline-none focus:ring-2 focus:ring-red-400">
    <div class="market-card-media">
        @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition duration-500 hover:scale-105" />
        @else
            <div class="flex h-full items-center justify-center text-sm text-slate-400">No image uploaded</div>
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

    <div class="flex min-h-[17rem] flex-col space-y-4 p-5">
        <div>
            <h3 class="line-clamp-2 text-lg font-bold leading-snug text-white">{{ $item->title }}</h3>
            <p class="mt-2 text-sm leading-6 text-[#eedcbbc7]">{{ \Illuminate\Support\Str::limit($item->description, 95) }}</p>
        </div>

        <div class="flex items-start justify-between gap-3 text-sm text-[#eedcbbcc]">
            <span class="min-w-0 truncate">{{ $item->department }}</span>
            <span class="shrink-0 text-base font-black text-white">{{ $item->price ? 'P' . number_format($item->price, 2) : 'Flexible' }}</span>
        </div>

        @if($item->listing_type === 'rent')
            <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-[#eedcbbcc]">
                Rental: {{ $item->minimum_rental_days ?? 1 }}-{{ $item->maximum_rental_days ?? $item->rental_duration_days ?? 1 }} day(s) · P{{ number_format($item->daily_rental_rate ?? $item->price ?? 0, 2) }}/day
            </div>
        @endif

        <div class="flex items-center justify-between gap-3">
            <span class="market-card-bottom-pill">
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
                <span class="text-xs font-medium text-[#eedcbb8c]">
                    {{ str($item->condition)->replace('_', ' ')->title() }}
                </span>
            @endif
        </div>

        <div class="mt-auto flex items-center justify-between gap-3 border-t border-white/5 pt-3">
            <span class="text-xs uppercase tracking-[0.18em] text-slate-500">
                {{ $item->course_code }} · {{ $item->user->name }}
            </span>
            <span class="text-sm font-semibold text-white transition group-hover:text-[#ff8c82]">Open</span>
        </div>
    </div>
</a>
