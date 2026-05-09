<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Transactions</p>
                <h1 class="section-title mt-2">Pending Requests</h1>
                <p class="section-copy mt-2">Approve or reject item requests, then add meetup details for the next step of the demo flow.</p>
            </div>
            <a href="{{ route('transactions.history') }}" class="btn-secondary">Transaction History</a>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <x-flash-messages />

        @if($transactions->count())
            <div class="space-y-4">
                @foreach($transactions as $transaction)
                    <div class="glass-card p-6">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-white">{{ $transaction->item->title }}</h2>
                                <p class="mt-1 text-sm text-slate-300">Requested by {{ $transaction->buyer->name }} · {{ $transaction->buyer->email }}</p>
                            </div>
                            <span class="badge-base border-amber-400/30 bg-amber-500/20 text-amber-100">Pending</span>
                        </div>

                        <div class="mt-5 grid gap-3 md:grid-cols-3">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                                Course Code
                                <div class="mt-1 font-semibold text-white">{{ $transaction->item->course_code }}</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                                Listing Type
                                <div class="mt-1 font-semibold text-white">{{ ucfirst($transaction->item->listing_type) }}</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                                Requested On
                                <div class="mt-1 font-semibold text-white">{{ $transaction->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 lg:grid-cols-[1fr_auto]">
                            <form method="POST" action="{{ route('transactions.approve', $transaction) }}" class="grid gap-4 lg:grid-cols-2">
                                @csrf
                                <div>
                                    <label class="text-sm font-medium text-slate-200">Meetup Location</label>
                                    <input type="text" name="meetup_location" required class="glass-input" placeholder="Example: Main Library" />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-200">Meetup Time</label>
                                    <input type="datetime-local" name="meetup_time" required class="glass-input" />
                                </div>
                                <div class="lg:col-span-2">
                                    <button type="submit" class="btn-primary w-full sm:w-auto">Approve</button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('transactions.reject', $transaction) }}" class="self-end">
                                @csrf
                                <button type="submit" class="btn-danger w-full sm:w-auto">Reject</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>{{ $transactions->links() }}</div>
        @else
            <div class="glass-card px-6 py-12 text-center">
                <h3 class="text-lg font-semibold text-white">No pending requests</h3>
                <p class="mt-2 text-sm text-slate-300">Incoming item requests will appear here for approval or rejection.</p>
            </div>
        @endif
    </div>
</x-app-layout>
