<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Transactions</p>
                <h1 class="section-title mt-2">Transaction History</h1>
                <p class="section-copy mt-2">Track every request from pending to approved meetup, completed deal, and rating.</p>
            </div>
            <a href="{{ route('transactions.pending') }}" class="btn-secondary">Pending Requests</a>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <x-flash-messages />

        <div class="transaction-guide">
            <div class="transaction-guide-step transaction-guide-step-active">
                <span>1</span>
                <div>
                    <strong>Pending</strong>
                    <p>Waiting for the seller to approve or reject.</p>
                </div>
            </div>
            <div class="transaction-guide-line"></div>
            <div class="transaction-guide-step transaction-guide-step-active">
                <span>2</span>
                <div>
                    <strong>Approved</strong>
                    <p>Meetup details are set for handoff.</p>
                </div>
            </div>
            <div class="transaction-guide-line"></div>
            <div class="transaction-guide-step">
                <span>3</span>
                <div>
                    <strong>Completed</strong>
                    <p>Seller confirms the deal after meetup.</p>
                </div>
            </div>
        </div>

        @if($transactions->count())
            <div class="transaction-list">
                @foreach($transactions as $transaction)
                    @php
                        $statusClasses = match($transaction->status) {
                            'pending' => 'border-amber-400/30 bg-amber-500/20 text-amber-100',
                            'approved' => 'border-sky-400/30 bg-sky-500/20 text-sky-100',
                            'rejected' => 'border-red-400/30 bg-red-500/20 text-red-100',
                            'completed' => 'border-emerald-400/30 bg-emerald-500/20 text-emerald-100',
                            default => 'border-white/20 bg-white/10 text-white',
                        };
                        $isSeller = auth()->id() === $transaction->seller_id;
                        $nextStep = match($transaction->status) {
                            'pending' => $isSeller ? 'Review this request in Pending Requests.' : 'Waiting for the seller to approve or reject your request.',
                            'approved' => $isSeller ? 'Meet the buyer, then mark the deal as completed.' : 'Meet the seller using the approved meetup details.',
                            'rejected' => 'This request was rejected. The item can receive new requests again.',
                            'completed' => 'Deal completed. You can open the receipt and leave a rating if you have not rated yet.',
                            default => 'Open the receipt for more details.',
                        };
                    @endphp

                    <div class="transaction-card">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="transaction-kicker">{{ $isSeller ? 'Selling record' : 'Buying record' }}</p>
                                <h2 class="transaction-title">{{ $transaction->item->title }}</h2>
                                <p class="transaction-subtitle">Buyer: {{ $transaction->buyer->name }} · Seller: {{ $transaction->seller->name }}</p>
                            </div>
                            <span class="badge-base {{ $statusClasses }}">{{ ucfirst($transaction->status) }}</span>
                        </div>

                        <div class="transaction-status-note">
                            <span>Next step</span>
                            <p>{{ $nextStep }}</p>
                        </div>

                        <div class="transaction-meta-grid">
                            <div class="transaction-meta-tile">
                                <span>Meetup Location</span>
                                <strong>{{ $transaction->meetup_location ?? 'Not set' }}</strong>
                            </div>
                            <div class="transaction-meta-tile">
                                <span>Meetup Time</span>
                                <strong>{{ $transaction->meetup_time ? $transaction->meetup_time->format('M d, Y h:i A') : 'To be announced' }}</strong>
                            </div>
                            <div class="transaction-meta-tile">
                                <span>Transaction Date</span>
                                <strong>{{ $transaction->created_at->format('M d, Y') }}</strong>
                            </div>
                        </div>

                        <div class="transaction-card-actions">
                            <a href="{{ route('transactions.show', $transaction) }}" class="btn-secondary">View Receipt</a>
                            <form method="POST" action="{{ route('messages.store') }}">
                                @csrf
                                <input type="hidden" name="recipient_id" value="{{ $isSeller ? $transaction->buyer_id : $transaction->seller_id }}">
                                <input type="hidden" name="item_id" value="{{ $transaction->item_id }}">
                                <input type="hidden" name="body" value="Hi, I want to coordinate about {{ $transaction->item->title }}.">
                                <button type="submit" class="btn-secondary">{{ $isSeller ? 'Message Buyer' : 'Message Seller' }}</button>
                            </form>
                            @if($isSeller && $transaction->status === 'pending')
                                <a href="{{ route('transactions.pending') }}" class="btn-primary">Review Request</a>
                            @endif
                            @if($isSeller && $transaction->status === 'approved')
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
            <div class="transaction-empty-state">
                <div class="transaction-empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M6 7v12h12V7M9 11h6"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7a3 3 0 0 1 6 0"></path>
                    </svg>
                </div>
                <h3>No transactions yet</h3>
                <p>Requests and approvals will show up here once you start using the marketplace.</p>
                <a href="{{ route('marketplace.index') }}" class="btn-primary mt-5">Browse Marketplace</a>
            </div>
        @endif
    </div>
</x-app-layout>
