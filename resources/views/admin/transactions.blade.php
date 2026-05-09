<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Admin</p>
                <h1 class="section-title mt-2">Transaction Overview</h1>
                <p class="section-copy mt-2">Simple table view of buyer-seller activity across pending, approved, rejected, and completed requests.</p>
            </div>
        </div>
    </x-slot>

    <div class="page-wrap">
        <div class="table-shell">
            @if($transactions->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="table-head">
                            <tr>
                                <th class="px-4 py-4">ID</th>
                                <th class="px-4 py-4">Item</th>
                                <th class="px-4 py-4">Buyer</th>
                                <th class="px-4 py-4">Seller</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-4 py-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="table-row">
                                    <td class="px-4 py-4">{{ $transaction->id }}</td>
                                    <td class="px-4 py-4">{{ $transaction->item->title }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $transaction->buyer->name }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $transaction->seller->name }}</td>
                                    <td class="px-4 py-4">
                                        <span class="badge-base border-white/15 bg-white/10 text-slate-200">{{ ucfirst($transaction->status) }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-300">{{ $transaction->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">{{ $transactions->links() }}</div>
            @else
                <div class="px-6 py-12 text-center text-slate-300">No transactions found.</div>
            @endif
        </div>
    </div>
</x-app-layout>
