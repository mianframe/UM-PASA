<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Admin</p>
                <h1 class="section-title mt-2">Item Moderation</h1>
                <p class="section-copy mt-2">Review marketplace listings and remove inappropriate entries when needed.</p>
            </div>
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
                                <th class="px-4 py-4">Title</th>
                                <th class="px-4 py-4">Seller</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-4 py-4">Type</th>
                                <th class="px-4 py-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="table-row">
                                    <td class="px-4 py-4">{{ $item->title }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $item->user->name }}</td>
                                    <td class="px-4 py-4">
                                        <span class="badge-base border-white/15 bg-white/10 text-slate-200">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-300">{{ ucfirst($item->listing_type) }}</td>
                                    <td class="px-4 py-4">
                                        <form method="POST" action="{{ route('admin.items.destroy', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">Delete</button>
                                        </form>
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
