<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Student Dashboard</p>
                <h1 class="section-title mt-2">Welcome back, {{ $user->name }}</h1>
                <p class="section-copy mt-2">Manage listings, review requests, check notifications, and continue current transactions.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('reports.student') }}" class="btn-secondary">Generate Report</a>
                <a href="{{ route('items.create') }}" class="btn-primary">Post New Item</a>
                <a href="{{ route('marketplace.index') }}" class="btn-secondary">Browse Marketplace</a>
            </div>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <x-flash-messages />

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            <a href="{{ route('marketplace.index') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Approved Listings</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['approvedListings'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Listings visible in the marketplace.</p>
            </a>
            <a href="{{ route('reports.student') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Pending Listing Review</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['pendingListings'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Waiting for admin approval.</p>
            </a>
            <a href="{{ route('reports.student') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Total Items</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['totalItems'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Listings posted from your account.</p>
            </a>
            <a href="{{ route('transactions.pending') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Pending Requests</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['pendingRequests'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Requests waiting for your approval.</p>
            </a>
            <a href="{{ route('transactions.history') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Completed Transactions</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['completedTransactions'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Completed deals linked to your account.</p>
            </a>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="space-y-6">
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Seller Analytics</h3>
                            <p class="mt-1 text-sm text-slate-300">Quick totals for active listings, requests, and completed sales.</p>
                        </div>
                        <a href="{{ route('items.create') }}" class="btn-primary">+ Add Listing</a>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Active Listings</div>
                            <div class="mt-2 text-2xl font-bold text-white">{{ $stats['totalItems'] }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Pending Requests</div>
                            <div class="mt-2 text-2xl font-bold text-white">{{ $stats['pendingRequests'] }}</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Total Earned</div>
                            <div class="mt-2 text-2xl font-bold text-white">P{{ number_format($user->items()->where('status', 'sold')->sum('price') ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Recent Notifications</h3>
                        <a href="{{ route('notifications.index') }}" class="text-sm text-red-200">View all</a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse($notifications as $note)
                            <div class="rounded-2xl border p-4 {{ $note->is_read ? 'border-white/10 bg-white/5 text-slate-300' : 'border-red-500/20 bg-red-600/10 text-white' }}">
                                <div class="text-sm">{{ $note->message }}</div>
                                <div class="mt-2 text-xs text-slate-400">{{ $note->created_at->diffForHumans() }}</div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-white/15 px-4 py-6 text-sm text-slate-400">No notifications yet.</div>
                        @endforelse
                    </div>
                </div>

                <div class="glass-card p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                        <span class="text-sm text-slate-400">Marketplace shortcuts</span>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                        <a href="{{ route('transactions.pending') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">
                            Review pending requests
                        </a>
                        <a href="{{ route('transactions.history') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">
                            Transaction history and receipts
                        </a>
                        <a href="{{ route('reports.student') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">
                            Generate report
                        </a>
                        <a href="{{ route('messages.index') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">
                            Open inbox
                        </a>
                        <a href="{{ route('profile.ratings') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">
                            Review ratings and feedback
                        </a>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-white">Marketplace Preview</h3>
                        <p class="mt-1 text-sm text-slate-300">Recent available items from the marketplace.</p>
                    </div>
                    <div class="text-sm text-slate-300">Average rating: <span class="font-semibold text-white">{{ $user->average_rating }}</span></div>
                </div>
                <div class="mt-4 space-y-4">
                    @forelse($recentItems as $item)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="font-semibold text-white">{{ $item->title }}</h4>
                                    <p class="mt-1 text-sm text-slate-300">{{ $item->department }} · {{ $item->course_code }}</p>
                                    <p class="mt-2 text-sm text-slate-400">Seller: {{ $item->user->name }}</p>
                                </div>
                                <span class="badge-base border-white/10 bg-white/10 text-slate-200">{{ ucfirst($item->listing_type) }}</span>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('marketplace.show', $item) }}" class="btn-secondary w-full">View Details</a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-white/15 px-4 py-6 text-sm text-slate-400">No items available yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
