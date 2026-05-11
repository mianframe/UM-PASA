<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between print:hidden">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Reports</p>
                <h1 class="section-title mt-2">Student Report</h1>
                <p class="section-copy mt-2">Printable summary of your listings, requests, rental durations, payment modes, and completed deals.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('dashboard') }}" class="btn-secondary">Dashboard</a>
                <button onclick="window.print()" class="btn-primary">Print Report</button>
            </div>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6 print:max-w-none print:text-slate-900">
        <div class="glass-card p-6 print:border print:border-slate-200 print:bg-white print:shadow-none">
            <div class="flex flex-col gap-4 border-b border-white/10 pb-5 sm:flex-row sm:items-start sm:justify-between print:border-slate-200">
                <div>
                    <p class="transaction-kicker print:text-red-700">UM-Pasa Student Report</p>
                    <h2 class="mt-2 text-3xl font-bold text-white print:text-slate-900">{{ $user->name }}</h2>
                    <p class="mt-1 text-sm text-slate-300 print:text-slate-600">{{ now()->format('F d, Y h:i A') }}</p>
                </div>
                <div class="text-sm text-slate-300 print:text-slate-600">{{ $user->email }}</div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                <div class="transaction-meta-tile print:bg-slate-50"><span>Listings</span><strong>{{ $stats['listed'] }}</strong></div>
                <div class="transaction-meta-tile print:bg-slate-50"><span>Approved Listings</span><strong>{{ $stats['approvedListings'] }}</strong></div>
                <div class="transaction-meta-tile print:bg-slate-50"><span>Transactions</span><strong>{{ $stats['transactions'] }}</strong></div>
                <div class="transaction-meta-tile print:bg-slate-50"><span>Completed Deals</span><strong>{{ $stats['completed'] }}</strong></div>
                <div class="transaction-meta-tile print:bg-slate-50"><span>Earned</span><strong>P{{ number_format($stats['earned'], 2) }}</strong></div>
            </div>
        </div>

        <div class="table-shell print:border print:border-slate-200 print:bg-white print:shadow-none">
            <div class="p-4 text-lg font-semibold text-white print:text-slate-900">Listings</div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="px-4 py-4">Item</th>
                            <th class="px-4 py-4">Type</th>
                            <th class="px-4 py-4">Rental / Sale Status</th>
                            <th class="px-4 py-4">Review</th>
                            <th class="px-4 py-4">Payments</th>
                            <th class="px-4 py-4">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr class="table-row">
                                <td class="px-4 py-4">{{ $item->title }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ ucfirst($item->listing_type) }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ $item->listing_type === 'rent' ? (($item->minimum_rental_days ?? 1) . '-' . ($item->maximum_rental_days ?? $item->rental_duration_days ?? 'TBD') . ' day(s)') : ucfirst($item->status) }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ $item->getModerationStatusLabel() }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ implode(', ', $item->getAcceptedPaymentMethodLabels()) }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ $item->price ? 'P' . number_format($item->price, 2) : 'Flexible' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-300">No listings yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-shell print:border print:border-slate-200 print:bg-white print:shadow-none">
            <div class="p-4 text-lg font-semibold text-white print:text-slate-900">Transactions</div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="px-4 py-4">ID</th>
                            <th class="px-4 py-4">Item</th>
                            <th class="px-4 py-4">Role</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Payment</th>
                            <th class="px-4 py-4">Proof</th>
                            <th class="px-4 py-4">Tracking</th>
                            <th class="px-4 py-4">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr class="table-row">
                                <td class="px-4 py-4">#{{ $transaction->id }}</td>
                                <td class="px-4 py-4">{{ $transaction->item->title }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ $transaction->seller_id === $user->id ? 'Seller' : 'Buyer' }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ ucfirst($transaction->status) }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ $transaction->getPaymentMethodLabel() }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ $transaction->getPaymentProofStatusLabel() }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ $transaction->getTrackingStatusLabel() }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ $transaction->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-4 py-8 text-center text-slate-300">No transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
