<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Transactions</p>
                <h1 class="section-title mt-2">Transaction History</h1>
                <p class="section-copy mt-2">View your buy and sell records, mark approved meetups as completed, and open the printable receipt page.</p>
            </div>
            <a href="{{ route('transactions.pending') }}" class="btn-secondary">Pending Requests</a>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <x-flash-messages />

        @if($transactions->count())
            <div class="space-y-4">
                @foreach($transactions as $transaction)
                    @php
                        $statusClasses = match($transaction->status) {
                            'pending' => 'border-amber-400/30 bg-amber-500/20 text-amber-100',
                            'approved' => 'border-sky-400/30 bg-sky-500/20 text-sky-100',
                            'rejected' => 'border-red-400/30 bg-red-500/20 text-red-100',
                            'completed' => 'border-emerald-400/30 bg-emerald-500/20 text-emerald-100',
                            default => 'border-white/20 bg-white/10 text-white',
                        };
                    @endphp

                    <div class="glass-card p-6">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-white">{{ $transaction->item->title }}</h2>
                                <p class="mt-1 text-sm text-slate-300">Buyer: {{ $transaction->buyer->name }} · Seller: {{ $transaction->seller->name }}</p>
                            </div>
                            <span class="badge-base {{ $statusClasses }}">{{ ucfirst($transaction->status) }}</span>
                        </div>

                        <div class="mt-5 grid gap-3 md:grid-cols-3">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                                Meetup Location
                                <div class="mt-1 font-semibold text-white">{{ $transaction->meetup_location ?? 'Not set' }}</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                                Meetup Time
                                <div class="mt-1 font-semibold text-white">{{ $transaction->meetup_time ? $transaction->meetup_time->format('M d, Y h:i A') : 'To be announced' }}</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                                Transaction Date
                                <div class="mt-1 font-semibold text-white">{{ $transaction->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('transactions.show', $transaction) }}" class="btn-secondary">View Receipt</a>
                            @if(auth()->id() === $transaction->seller_id && $transaction->status === 'approved')
                                <form method="POST" action="{{ route('transactions.complete', $transaction) }}">
                                    @csrf
                                    <button type="submit" class="btn-primary">Mark as Completed</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div>{{ $transactions->links() }}</div>
        @else
            <div class="glass-card px-6 py-12 text-center">
                <h3 class="text-lg font-semibold text-white">No transactions yet</h3>
                <p class="mt-2 text-sm text-slate-300">Requests and approvals will show up here once you start using the marketplace.</p>
            </div>
        @endif
    </div>
</x-app-layout>
