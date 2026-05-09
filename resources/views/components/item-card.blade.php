@props(['item'])

<article class="market-card">
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
                'swap' => 'badge-swap',
                default => 'badge-base bg-white/10 text-white',
            };
        @endphp

        <span class="{{ $listingClasses }} absolute left-4 top-4">
            {{ ucfirst($item->listing_type) }}
        </span>
        <button type="button" class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-black/35 text-white/90 transition hover:border-white/20 hover:bg-black/50">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="m12 20.5-1.1-1C5.7 14.8 2.5 11.9 2.5 8.2 2.5 5.4 4.7 3.5 7.4 3.5c1.5 0 2.9.7 3.8 1.9.9-1.2 2.3-1.9 3.8-1.9 2.7 0 4.9 1.9 4.9 4.7 0 3.7-3.2 6.6-8.4 11.3L12 20.5Z"></path>
            </svg>
        </button>
    </div>

    <div class="space-y-4 p-5">
        <div>
            <h3 class="text-xl font-bold tracking-tight text-white">{{ $item->title }}</h3>
            <p class="mt-2 text-sm leading-6 text-[#eedcbbc7]">{{ \Illuminate\Support\Str::limit($item->description, 95) }}</p>
        </div>

        <div class="flex items-center justify-between text-sm text-[#eedcbbcc]">
            <span>{{ $item->department }}</span>
            <span class="font-semibold text-white">{{ $item->price ? 'P' . number_format($item->price, 2) : 'Flexible' }}</span>
        </div>

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

        <div class="flex items-center justify-between gap-3 border-t border-white/5 pt-3">
            <span class="text-xs uppercase tracking-[0.18em] text-slate-500">
                {{ $item->course_code }} · {{ $item->user->name }}
            </span>
            <a href="{{ route('marketplace.show', $item) }}" class="text-sm font-semibold text-white transition hover:text-[#ff8c82]">Open</a>
        </div>
    </div>
</article>
