<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Create Listing</p>
                <h1 class="section-title mt-2">Post a New Academic Item</h1>
                <p class="section-copy mt-2">Use the form below to create a sale or rental listing for the campus marketplace.</p>
            </div>
            <a href="{{ route('marketplace.index') }}" class="btn-secondary">Back to Marketplace</a>
        </div>
    </x-slot>

    <div class="page-wrap">
        <div class="glass-card p-6 sm:p-8">
            @include('items._form')
        </div>
    </div>
</x-app-layout>
