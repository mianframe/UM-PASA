<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between print:hidden">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Receipt</p>
                <h1 class="section-title mt-2">Transaction Receipt</h1>
                <p class="section-copy mt-2">A clean printable summary for approved or completed marketplace transactions.</p>
            </div>
            <button onclick="window.print()" class="btn-primary">Print Receipt</button>
        </div>
    </x-slot>

    <div class="page-wrap">
        <div class="mx-auto max-w-4xl rounded-[2rem] bg-white p-6 text-slate-900 shadow-2xl print:max-w-none print:rounded-none print:shadow-none">
            <div class="flex flex-col gap-2 border-b border-slate-200 pb-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-red-700">UM-Pasa Receipt</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">Transaction #{{ $transaction->id }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $transaction->created_at->format('F d, Y') }}</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold uppercase tracking-[0.18em] text-white">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>

            <div class="mt-8 grid gap-6 md:grid-cols-2">
                <div class="rounded-3xl bg-slate-100 p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Buyer</h3>
                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $transaction->buyer->name }}</p>
                    <p class="text-sm text-slate-600">{{ $transaction->buyer->email }}</p>
                </div>
                <div class="rounded-3xl bg-slate-100 p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Seller</h3>
                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $transaction->seller->name }}</p>
                    <p class="text-sm text-slate-600">{{ $transaction->seller->email }}</p>
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-slate-200 p-6">
                <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Item Details</h3>
                <div class="mt-3 text-2xl font-semibold text-slate-900">{{ $transaction->item->title }}</div>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $transaction->item->description }}</p>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Listing Type</div>
                        <div class="mt-1 text-sm text-slate-900">{{ ucfirst($transaction->item->listing_type) }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Price</div>
                        <div class="mt-1 text-sm text-slate-900">{{ $transaction->item->price ? 'P' . number_format($transaction->item->price, 2) : 'Negotiable / Swap' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Department</div>
                        <div class="mt-1 text-sm text-slate-900">{{ $transaction->item->department }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Course Code</div>
                        <div class="mt-1 text-sm text-slate-900">{{ $transaction->item->course_code }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Meetup Location</div>
                        <div class="mt-1 text-sm text-slate-900">{{ $transaction->meetup_location ?? 'To be announced' }}</div>
                    </div>
                    <div class="rounded-3xl bg-gray-50 p-5">
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Meetup Time</div>
                        <div class="mt-1 text-sm text-slate-900">{{ $transaction->meetup_time ? $transaction->meetup_time->format('F d, Y h:i A') : 'To be announced' }}</div>
                    </div>
                </div>
            </div>

            @if($canRate)
                <div class="mt-8 rounded-3xl border border-red-200 bg-red-50 p-5 print:hidden">
                    <p class="text-sm text-red-700">This transaction is completed. You can now leave a rating for the other student.</p>
                    <a href="{{ route('ratings.create', $transaction) }}" class="mt-4 inline-flex items-center rounded-xl bg-red-700 px-4 py-3 text-sm font-semibold text-white">Leave a Rating</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
