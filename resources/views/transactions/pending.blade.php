<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Transactions</p>
                <h1 class="section-title mt-2">Pending Requests</h1>
                <p class="section-copy mt-2">These are buyer requests for items you posted. Approving a request sets the meetup details and moves it to the completion step.</p>
            </div>
            <a href="{{ route('transactions.history') }}" class="btn-secondary">Transaction History</a>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <x-flash-messages />

        <div class="transaction-guide">
            <div class="transaction-guide-step transaction-guide-step-active">
                <span>1</span>
                <div>
                    <strong>Buyer sends request</strong>
                    <p>The item becomes pending while you review it.</p>
                </div>
            </div>
            <div class="transaction-guide-line"></div>
            <div class="transaction-guide-step">
                <span>2</span>
                <div>
                    <strong>Seller approves or rejects</strong>
                    <p>Approving requires a meetup location and time.</p>
                </div>
            </div>
            <div class="transaction-guide-line"></div>
            <div class="transaction-guide-step">
                <span>3</span>
                <div>
                    <strong>Complete after meetup</strong>
                    <p>Approved deals can be marked completed from history or receipt.</p>
                </div>
            </div>
        </div>

        @if($transactions->count())
            <div class="transaction-list">
                @foreach($transactions as $transaction)
                    <div class="transaction-card">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="transaction-kicker">Incoming request</p>
                                <h2 class="transaction-title">{{ $transaction->item->title }}</h2>
                                <p class="transaction-subtitle">Requested by {{ $transaction->buyer->name }} · {{ $transaction->buyer->email }}</p>
                            </div>
                            <span class="badge-base border-amber-400/30 bg-amber-500/20 text-amber-100">Pending</span>
                        </div>

                        <div class="transaction-meta-grid">
                            <div class="transaction-meta-tile">
                                <span>Course Code</span>
                                <strong>{{ $transaction->item->course_code }}</strong>
                            </div>
                            <div class="transaction-meta-tile">
                                <span>Listing Type</span>
                                <strong>{{ ucfirst($transaction->item->listing_type) }}</strong>
                            </div>
                            <div class="transaction-meta-tile">
                                <span>Mode of Payment</span>
                                <strong>{{ $transaction->getPaymentMethodLabel() }}</strong>
                            </div>
	                            <div class="transaction-meta-tile">
	                                <span>Payment Proof</span>
	                                @if($transaction->payment_proof)
	                                    <strong>
	                                        <a href="{{ route('transactions.paymentProof.show', $transaction) }}" target="_blank" class="text-emerald-100 underline decoration-emerald-200/50 underline-offset-4">
	                                            Uploaded - View file
	                                        </a>
	                                    </strong>
	                                @else
	                                    <strong>Not uploaded yet</strong>
	                                @endif
	                            </div>
                            @if($transaction->item->listing_type === 'rent')
                                <div class="transaction-meta-tile">
                                    <span>Rental Duration</span>
                                    <strong>{{ $transaction->rental_duration_days ? $transaction->rental_duration_days . ' day(s)' : 'To be discussed' }}</strong>
                                </div>
                                <div class="transaction-meta-tile">
                                    <span>Due Date</span>
                                    <strong>{{ $transaction->rental_due_date ? $transaction->rental_due_date->format('M d, Y') : 'Not set' }}</strong>
                                </div>
                            @endif
                            <div class="transaction-meta-tile">
                                <span>Requested On</span>
                                <strong>{{ $transaction->created_at->format('M d, Y h:i A') }}</strong>
                            </div>
                        </div>

                        <div class="transaction-action-panel">
                            <form method="POST" action="{{ route('transactions.approve', $transaction) }}" class="transaction-approve-form">
                                @csrf
                                <div>
                                    <label class="text-sm font-medium text-slate-200">Meetup Location</label>
                                    <input type="text" name="meetup_location" required class="glass-input" placeholder="Example: Main Library lobby" />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-200">Meetup Time</label>
                                    <input type="datetime-local" name="meetup_time" required class="glass-input" />
                                </div>
                                <div class="transaction-form-footer">
                                    <p>After approval, the buyer is notified and the deal moves to transaction history.</p>
                                    <button type="submit" class="btn-primary w-full sm:w-auto">Approve Request</button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('transactions.reject', $transaction) }}" class="transaction-reject-form">
                                @csrf
                                <button type="submit" class="btn-danger w-full">Reject Request</button>
                                <p>Rejecting makes the item available again.</p>
                            </form>
                        </div>

                        <div class="transaction-card-actions">
	                            <form method="POST" action="{{ route('messages.store') }}">
	                                @csrf
	                                <input type="hidden" name="recipient_id" value="{{ $transaction->buyer_id }}">
	                                <input type="hidden" name="item_id" value="{{ $transaction->item_id }}">
	                                <input type="hidden" name="body" value="Hi {{ $transaction->buyer->name }}, I saw your request for {{ $transaction->item->title }}.">
	                                <button type="submit" class="btn-secondary">Message Buyer</button>
	                            </form>
	                            <a href="{{ route('transactions.show', $transaction) }}" class="btn-secondary">View Receipt</a>
	                            @if($transaction->payment_proof)
	                                <a href="{{ route('transactions.paymentProof.show', $transaction) }}" class="btn-secondary" target="_blank">View Uploaded Proof</a>
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
                <h3>No pending requests</h3>
                <p>When another student requests one of your available listings, it will appear here with approve and reject actions.</p>
                <a href="{{ route('transactions.history') }}" class="btn-secondary mt-5">Open Transaction History</a>
            </div>
        @endif
    </div>
</x-app-layout>
