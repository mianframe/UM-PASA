<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Marketplace Item</p>
                <h1 class="section-title mt-2">{{ $item->title }}</h1>
                <p class="section-copy mt-2">{{ $item->category ?: 'General' }} · {{ $item->department }} · {{ $item->course_code }}</p>
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

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(360px,0.85fr)]">
            <section class="space-y-4">
                <div class="glass-card overflow-hidden">
                    <div class="relative aspect-[4/3] min-h-[22rem]">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-white/5 text-sm font-semibold text-slate-400">No image uploaded</div>
                        @endif
                        <div class="absolute left-5 top-5 flex flex-wrap gap-2">
                            <span class="badge-base {{ $item->listing_type === 'rent' ? 'badge-rent' : 'badge-sale' }}">{{ $item->getListingTypeLabel() }}</span>
                            <span class="badge-base border-white/15 bg-black/35 text-white">{{ ucfirst($item->status) }}</span>
                            <span class="badge-base border-white/15 bg-black/35 text-white">{{ $item->getModerationStatusLabel() }}</span>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <h2 class="text-xl font-bold text-white">Description</h2>
                        <span class="text-sm text-slate-400">Posted {{ $item->created_at->format('M d, Y') }}</span>
                    </div>
                    <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-200">{{ $item->description }}</p>
                </div>
            </section>

            <aside class="space-y-4 xl:sticky xl:top-28 xl:self-start">
                <div class="glass-card p-6">
                    <div class="flex flex-col gap-4 border-b border-white/10 pb-5">
                        <div>
                            <div class="text-sm uppercase tracking-[0.18em] text-slate-400">{{ $item->category ?: 'General' }}</div>
                            <h2 class="mt-2 text-3xl font-black leading-tight text-white">{{ $item->title }}</h2>
                        </div>
                        <div>
                            <div class="text-sm text-slate-400">{{ $item->listing_type === 'rent' ? 'Daily rate' : 'Price' }}</div>
                            <div class="mt-1 text-4xl font-black text-white">
                                {{ $item->price ? 'P' . number_format($item->price, 2) : 'Negotiable' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Condition</span>
                            <strong class="mt-2 block text-sm text-white">{{ $item->condition ? str($item->condition)->replace('_', ' ')->title() : 'Not specified' }}</strong>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Program</span>
                            <strong class="mt-2 block text-sm text-white">{{ $item->program ?: 'General' }}</strong>
                        </div>
                        @if($item->listing_type === 'rent')
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Rental Range</span>
                                <strong class="mt-2 block text-sm text-white">{{ $item->minimum_rental_days ?? 1 }} to {{ $item->maximum_rental_days ?? $item->rental_duration_days ?? 1 }} day(s)</strong>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Daily Rate</span>
                                <strong class="mt-2 block text-sm text-white">P{{ number_format($item->daily_rental_rate ?? $item->price ?? 0, 2) }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Accepted Payments</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($item->getAcceptedPaymentMethodLabels() as $label)
                                <span class="market-card-bottom-pill">{{ $label }}</span>
                            @endforeach
                        </div>
                    </div>

                    @if($item->moderation_status === 'rejected' && $item->rejection_reason)
                        <div class="mt-5 rounded-2xl border border-red-400/20 bg-red-500/10 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-red-100">Rejection Reason</div>
                            <div class="mt-2 text-sm font-semibold text-white">{{ $item->rejection_reason }}</div>
                        </div>
                    @endif
                </div>

                <div class="glass-card p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-lg font-black text-white">
                            {{ str($item->user->name)->substr(0, 1)->upper() }}
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm uppercase tracking-[0.18em] text-slate-400">Seller</div>
                            <div class="mt-1 text-lg font-bold text-white">{{ $item->user->name }}</div>
                            <div class="mt-1 text-sm text-slate-300">{{ $item->user->email }}</div>
                            <div class="mt-2 text-sm text-slate-300">
                                {{ number_format($item->user->average_rating, 1) }} rating · {{ $item->user->ratingsReceived()->count() }} review{{ $item->user->ratingsReceived()->count() === 1 ? '' : 's' }}
                            </div>
                        </div>
                    </div>
                </div>

                @guest
                    <div class="glass-card p-6">
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="btn-primary w-full">Login to Request</a>
                            <a href="{{ route('register') }}" class="btn-secondary w-full">Create Student Account</a>
                        </div>
                        <p class="mt-3 text-sm text-slate-300">Guests can browse listings. Logged-in UM students can request items or message sellers.</p>
                    </div>
                @endguest

                @auth
                    @if(auth()->id() !== $item->user_id)
                        <div class="glass-card p-6">
                            @if($item->status === 'available' && $item->moderation_status === 'approved')
                                <div class="grid gap-3">
                                    <form method="POST" action="{{ route('transactions.store', $item) }}" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label for="payment_method" class="text-sm font-medium text-slate-200">Mode of Payment</label>
                                            <select id="payment_method" name="payment_method" class="glass-input mt-2" required>
                                                @foreach(($item->accepted_payment_methods ?: array_keys(\App\Models\Item::paymentMethodOptions())) as $methodValue)
                                                    <option value="{{ $methodValue }}">{{ \App\Models\Item::paymentMethodOptions()[$methodValue] ?? $methodValue }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if(in_array('other', $item->accepted_payment_methods ?: []))
                                            <div>
                                                <label for="other_payment_method" class="text-sm font-medium text-slate-200">Other payment method</label>
                                                <input id="other_payment_method" type="text" name="other_payment_method" class="glass-input mt-2" placeholder="Specify if other is selected">
                                            </div>
                                        @endif
                                        @if($item->listing_type === 'rent')
                                            <div>
                                                <label for="rental_duration_days" class="text-sm font-medium text-slate-200">Rental Duration</label>
                                                <input id="rental_duration_days" type="number" name="rental_duration_days" min="{{ $item->minimum_rental_days ?? 1 }}" max="{{ $item->maximum_rental_days ?? 365 }}" value="{{ $item->minimum_rental_days ?? 1 }}" class="glass-input mt-2" required>
                                                <p class="mt-2 text-xs text-slate-400">Allowed: {{ $item->minimum_rental_days ?? 1 }} to {{ $item->maximum_rental_days ?? 365 }} day(s).</p>
                                            </div>
                                        @endif
                                        <button type="submit" class="btn-primary w-full">{{ $item->listing_type === 'rent' ? 'Request Rental' : 'Request Item' }}</button>
                                    </form>

                                    <form method="POST" action="{{ route('messages.store') }}">
                                        @csrf
                                        <input type="hidden" name="recipient_id" value="{{ $item->user_id }}">
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="body" value="Hi, is this item still available?">
                                        <button type="submit" class="btn-secondary w-full">Message Seller</button>
                                    </form>
                                </div>
                            @else
                                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4 text-sm text-slate-300">This item is currently not available for new requests.</div>
                            @endif
                        </div>
                    @else
                        <div class="glass-card p-6">
                            <div class="text-sm uppercase tracking-[0.18em] text-slate-400">Your Item Status</div>
                            <div class="mt-3 text-xl font-semibold text-white">{{ ucfirst($item->status) }}</div>
                            <p class="mt-2 text-sm text-slate-300">Admin review: {{ $item->getModerationStatusLabel() }}.</p>
                        </div>
                    @endif
                @endauth
            </aside>
        </div>
    </div>
</x-app-layout>
