<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Item Details</p>
                <h1 class="section-title mt-2">{{ $item->title }}</h1>
                <p class="section-copy mt-2">{{ $item->department }} · {{ $item->program ?: 'General' }} · {{ $item->course_code }}</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('marketplace.index') }}" class="btn-secondary">Back</a>
                @auth
                    @if(auth()->id() === $item->user_id)
                        <a href="{{ route('items.edit', $item) }}" class="btn-primary">Edit</a>
                    @endif
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <x-flash-messages />

        <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr]">
            <div class="glass-card overflow-hidden">
                <div class="relative">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-96 object-cover" />
                    @else
                        <div class="flex h-96 items-center justify-center bg-white/5 text-slate-400">No image available</div>
                    @endif
                    <div class="absolute left-5 top-5 flex gap-2">
                        <span class="badge-base border-red-400/30 bg-red-600/20 text-red-100">{{ ucfirst($item->listing_type) }}</span>
                        <span class="badge-base border-white/15 bg-white/10 text-white">{{ ucfirst($item->status) }}</span>
                    </div>
                </div>

                <div class="space-y-6 p-6">
                    <p class="text-base leading-7 text-slate-200 whitespace-pre-line">{{ $item->description }}</p>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Department</div>
                            <div class="mt-2 text-sm font-semibold text-white">{{ $item->department }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Course Code</div>
                            <div class="mt-2 text-sm font-semibold text-white">{{ $item->course_code }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Program</div>
                            <div class="mt-2 text-sm font-semibold text-white">{{ $item->program ?: 'General' }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Condition</div>
                            <div class="mt-2 text-sm font-semibold text-white">{{ $item->condition ? str($item->condition)->replace('_', ' ')->title() : 'Not specified' }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Price</div>
                            <div class="mt-2 text-sm font-semibold text-white">{{ $item->price ? 'P' . number_format($item->price, 2) : 'Negotiable / Swap' }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Posted On</div>
                            <div class="mt-2 text-sm font-semibold text-white">{{ $item->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky-glass space-y-4 self-start">
                <div class="glass-card p-6">
                    <div class="text-sm uppercase tracking-[0.18em] text-slate-400">Seller Info</div>
                    <div class="mt-4 text-xl font-semibold text-white">{{ $item->user->name }}</div>
                    <div class="mt-1 text-sm text-slate-300">{{ $item->user->email }}</div>
                    <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                        Average rating: <span class="font-semibold text-white">{{ $item->user->average_rating }}</span>
                    </div>
                </div>

                @guest
                    <div class="glass-card p-6">
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="btn-primary w-full">Login to Request</a>
                            <a href="{{ route('register') }}" class="btn-secondary w-full">Create Student Account</a>
                        </div>
                        <p class="mt-3 text-sm text-slate-300">Guests can browse listings, but only logged-in UM students can request items or start chat.</p>
                    </div>
                @endguest

                @auth
                @if(auth()->id() !== $item->user_id)
                    <div class="glass-card p-6">
                        @if($item->status === 'available')
                            <div class="grid gap-3">
                                <form method="POST" action="{{ route('transactions.store', $item) }}">
                                    @csrf
                                    <button type="submit" class="btn-primary w-full">Request Item</button>
                                </form>
                                <form method="POST" action="{{ route('messages.store') }}">
                                    @csrf
                                    <input type="hidden" name="recipient_id" value="{{ $item->user_id }}">
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <input type="hidden" name="body" value="Hi, is this item still available?">
                                    <button type="submit" class="btn-secondary w-full">Message Seller</button>
                                </form>
                            </div>
                            <p class="mt-3 text-sm text-slate-300">Start with a message or send a direct request. Meetup proposals can also be sent inside chat.</p>
                        @else
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4 text-sm text-slate-300">This item is currently not available for new requests.</div>
                        @endif
                    </div>
                @endif
                @endauth

                @auth
                @if(auth()->id() === $item->user_id)
                    <div class="glass-card p-6">
                        <div class="text-sm uppercase tracking-[0.18em] text-slate-400">Your Item Status</div>
                        <div class="mt-3 text-xl font-semibold text-white">{{ ucfirst($item->status) }}</div>
                        <p class="mt-2 text-sm text-slate-300">Use edit mode if you want to update the listing before the next demo step.</p>
                    </div>
                @endif
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
