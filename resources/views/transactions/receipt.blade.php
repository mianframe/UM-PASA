<x-app-layout>
    @php
        $isSeller = auth()->id() === $transaction->seller_id;
        $statusClasses = match($transaction->status) {
            'pending' => 'border-amber-400/30 bg-amber-500/20 text-amber-100',
            'approved' => 'border-sky-400/30 bg-sky-500/20 text-sky-100',
            'rejected' => 'border-red-400/30 bg-red-500/20 text-red-100',
            'completed' => 'border-emerald-400/30 bg-emerald-500/20 text-emerald-100',
            default => 'border-white/20 bg-white/10 text-white',
        };
        $nextStep = match($transaction->status) {
            'pending' => $isSeller ? 'Approve or reject this request from Pending Requests.' : 'Your request is waiting for the seller.',
            'approved' => $isSeller ? 'After the university meetup, mark this deal completed.' : 'Meet the seller using the approved location and time.',
            'rejected' => 'This request was rejected.',
            'completed' => 'This deal is completed. Ratings are now available.',
            default => 'Review the transaction details below.',
        };
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between print:hidden">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Receipt</p>
                <h1 class="section-title mt-2">Transaction Receipt</h1>
                <p class="section-copy mt-2">{{ $nextStep }}</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('transactions.history') }}" class="btn-secondary">Transaction History</a>
                <button onclick="window.print()" class="btn-primary">Print Receipt</button>
            </div>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <x-flash-messages />

        <div class="transaction-receipt-shell printable-receipt print:max-w-none print:rounded-none print:border-none print:bg-white print:p-6 print:text-slate-900 print:shadow-none">
            <div class="receipt-header flex flex-col gap-4 border-b border-white/10 pb-6 sm:flex-row sm:items-start sm:justify-between print:border-slate-200">
                <div>
                    <p class="transaction-kicker print:text-red-700">UM-Pasa Receipt</p>
                    <h2 class="mt-2 text-3xl font-bold text-white print:text-slate-900">Transaction #{{ $transaction->id }}</h2>
                    <p class="mt-1 text-sm text-slate-300 print:text-slate-500">Issued {{ now()->format('F d, Y h:i A') }}</p>
                </div>
                <span class="badge-base {{ $statusClasses }} print:border-slate-900 print:bg-slate-900 print:text-white">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>

            <div class="transaction-status-note print:hidden">
                <span>Next step</span>
                <p>{{ $nextStep }}</p>
            </div>

            <div class="transaction-card-actions print:hidden">
                <form method="POST" action="{{ route('messages.store') }}">
                    @csrf
                    <input type="hidden" name="recipient_id" value="{{ $isSeller ? $transaction->buyer_id : $transaction->seller_id }}">
                    <input type="hidden" name="item_id" value="{{ $transaction->item_id }}">
                    <input type="hidden" name="body" value="Hi, I want to coordinate about {{ $transaction->item->title }}.">
                    <button type="submit" class="btn-secondary">{{ $isSeller ? 'Message Buyer' : 'Message Seller' }}</button>
                </form>
                <a href="{{ route('marketplace.show', $transaction->item) }}" class="btn-secondary">View Item</a>
            </div>

            @if(!$isSeller && in_array($transaction->status, ['pending', 'approved']))
                <div class="transaction-final-panel print:hidden">
                    <div>
                        <strong>Payment proof</strong>
                        <p>Upload a receipt or screenshot after payment is done. Accepted files: JPG, PNG, or PDF.</p>
                    </div>
                    <form method="POST" action="{{ route('transactions.paymentProof', $transaction) }}" enctype="multipart/form-data" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        @csrf
                        <input type="file" name="payment_proof" accept="image/png,image/jpeg,application/pdf" class="glass-input" required>
                        <button type="submit" class="btn-primary">Upload Proof</button>
                    </form>
                </div>
            @endif

            <div class="transaction-receipt-panel print:border-slate-200">
                <h3>Transaction Details</h3>
                <div class="transaction-receipt-grid">
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Transaction Date</span>
                        <strong>{{ $transaction->created_at->format('F d, Y h:i A') }}</strong>
                    </div>
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Status</span>
                        <strong>{{ ucfirst($transaction->status) }}</strong>
                    </div>
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Payment Method</span>
                        <strong>{{ $transaction->getPaymentMethodLabel() }}</strong>
                    </div>
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Payment Proof</span>
                        <strong>{{ $transaction->getPaymentProofStatusLabel() }}</strong>
                    </div>
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Meetup Location</span>
                        <strong>{{ $transaction->meetup_location ?? 'To be announced' }}</strong>
                    </div>
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Meetup Time</span>
                        <strong>{{ $transaction->meetup_time ? $transaction->meetup_time->format('F d, Y h:i A') : 'To be announced' }}</strong>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 md:grid-cols-2">
                <div class="transaction-party-card print:bg-slate-100">
                    <h3>Buyer</h3>
                    <p>{{ $transaction->buyer->name }}</p>
                    <span>{{ $transaction->buyer->email }}</span>
                </div>
                <div class="transaction-party-card print:bg-slate-100">
                    <h3>Seller</h3>
                    <p>{{ $transaction->seller->name }}</p>
                    <span>{{ $transaction->seller->email }}</span>
                </div>
            </div>

            <div class="transaction-receipt-panel print:border-slate-200">
                <h3>Item Details</h3>
                <div class="mt-3 text-2xl font-semibold text-white print:text-slate-900">{{ $transaction->item->title }}</div>
                <p class="mt-3 text-sm leading-6 text-slate-300 print:text-slate-600">{{ $transaction->item->description }}</p>

                <div class="transaction-receipt-grid">
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Listing Type</span>
                        <strong>{{ ucfirst($transaction->item->listing_type) }}</strong>
                    </div>
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Price</span>
                        <strong>{{ $transaction->item->price ? 'P' . number_format($transaction->item->price, 2) : 'Negotiable' }}</strong>
                    </div>
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Tracking Status</span>
                        <strong>{{ $transaction->getTrackingStatusLabel() }}</strong>
                    </div>
                    @if($transaction->item->listing_type === 'rent')
                        <div class="transaction-meta-tile print:bg-slate-50">
                            <span>Rental Duration</span>
                            <strong>{{ $transaction->rental_duration_days ? $transaction->rental_duration_days . ' day(s)' : 'To be discussed' }}</strong>
                        </div>
                        <div class="transaction-meta-tile print:bg-slate-50">
                            <span>Rental Due Date</span>
                            <strong>{{ $transaction->rental_due_date ? $transaction->rental_due_date->format('F d, Y') : 'Not set' }}</strong>
                        </div>
                    @endif
                    @if($transaction->payment_proof)
                        <div class="transaction-meta-tile print:bg-slate-50">
                            <span>Receipt File</span>
                            <strong><a href="{{ route('transactions.paymentProof.show', $transaction) }}" class="text-red-200 print:text-slate-900" target="_blank">View uploaded proof</a></strong>
                        </div>
                    @endif
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Department</span>
                        <strong>{{ $transaction->item->department }}</strong>
                    </div>
                    <div class="transaction-meta-tile print:bg-slate-50">
                        <span>Course Code</span>
                        <strong>{{ $transaction->item->course_code }}</strong>
                    </div>
                </div>
            </div>

            @if($isSeller && $transaction->status === 'pending')
                <div class="transaction-final-panel print:hidden">
                    <div>
                        <strong>Ready to process this?</strong>
                        <p>Go to Pending Requests to approve with meetup details or reject the request.</p>
                    </div>
                    <a href="{{ route('transactions.pending') }}" class="btn-primary">Review Request</a>
                </div>
            @endif

            @if($isSeller && $transaction->status === 'approved')
                <div class="transaction-final-panel print:hidden">
                    <div>
                        <strong>Meetup finished?</strong>
                        <p>Mark the transaction completed after the item handoff is done.</p>
                    </div>
                    <form method="POST" action="{{ route('transactions.complete', $transaction) }}">
                        @csrf
                        <button type="submit" class="btn-primary">Mark as Completed</button>
                    </form>
                </div>
            @endif

            @if($canRate)
                <div class="transaction-final-panel print:hidden">
                    <div>
                        <strong>Ready for feedback</strong>
                        <p>This transaction is completed. You can now leave a rating for the other student.</p>
                    </div>
                    <a href="{{ route('ratings.create', $transaction) }}" class="btn-primary">Leave a Rating</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
