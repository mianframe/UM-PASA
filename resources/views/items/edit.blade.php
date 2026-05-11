<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Edit Listing</p>
                <h1 class="section-title mt-2">Update Marketplace Item</h1>
                <p class="section-copy mt-2">Revise the details below before publishing the updated marketplace listing.</p>
            </div>
            <a href="{{ route('marketplace.show', $item) }}" class="btn-secondary">Back to Item</a>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <div class="glass-card p-6 sm:p-8">
            @include('items._form')
        </div>

        <div class="glass-card flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-white">Delete This Listing</h2>
                <p class="mt-1 text-sm text-slate-300">Only remove the item if it should no longer appear in the marketplace.</p>
            </div>
            <form method="POST" action="{{ route('items.destroy', $item) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete Item</button>
            </form>
        </div>
    </div>
</x-app-layout>
