<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Admin</p>
                <h1 class="section-title mt-2">Item Moderation</h1>
                <p class="section-copy mt-2">Review marketplace listings and remove inappropriate entries when needed.</p>
            </div>
            <a href="{{ route('admin.reports') }}" class="btn-secondary">Generate Report</a>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <x-flash-messages />

        <div class="table-shell">
            @if($items->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="table-head">
                            <tr>
                                <th class="px-4 py-4">Listing</th>
                                <th class="px-4 py-4">Seller</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-4 py-4">Review</th>
                                <th class="px-4 py-4">Reason</th>
                                <th class="px-4 py-4">Type</th>
                                <th class="px-4 py-4">Terms</th>
                                <th class="px-4 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="table-row">
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-white">{{ $item->title }}</div>
                                        <div class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-400">{{ $item->category ?: 'General' }} · {{ $item->course_code }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-slate-200">{{ $item->user->name }}</div>
                                        <div class="mt-1 text-xs text-slate-400">{{ $item->user->email }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="badge-base border-white/15 bg-white/10 text-slate-200">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="badge-base border-white/15 bg-white/10 text-slate-200">{{ $item->getModerationStatusLabel() }}</span>
                                    </td>
                                    <td class="max-w-xs px-4 py-4 text-sm leading-6 text-slate-300">{{ $item->rejection_reason ?: 'N/A' }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ ucfirst($item->listing_type) }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $item->listing_type === 'rent' ? (($item->minimum_rental_days ?? 1) . '-' . ($item->maximum_rental_days ?? $item->rental_duration_days ?? 'TBD') . ' day(s)') : 'N/A' }}</td>
                                    <td class="min-w-72 px-4 py-4">
                                        <div class="grid gap-2 rounded-2xl border border-white/10 bg-white/5 p-3">
                                            <a href="{{ route('marketplace.show', $item) }}" class="btn-secondary text-center">View</a>
                                            @if($item->moderation_status !== 'approved')
                                                <form method="POST" action="{{ route('admin.items.approve', $item) }}">
                                                    @csrf
                                                    <button type="submit" class="btn-primary w-full">Approve</button>
                                                </form>
                                            @endif
                                            @if($item->moderation_status !== 'rejected')
                                                <form method="POST" action="{{ route('admin.items.reject', $item) }}" class="grid gap-2" onsubmit="return confirm('Reject this listing? The seller will see the reason.');">
                                                    @csrf
                                                    <label class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Rejection note</label>
                                                    <input type="text" name="rejection_reason" class="glass-input" placeholder="Reason for seller">
                                                    <button type="submit" class="btn-secondary w-full">Reject</button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.items.destroy', $item) }}" onsubmit="return confirm('Delete this listing permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger w-full">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">{{ $items->links() }}</div>
            @else
                <div class="px-6 py-12 text-center text-slate-300">No items available for moderation.</div>
            @endif
        </div>
    </div>
</x-app-layout>
