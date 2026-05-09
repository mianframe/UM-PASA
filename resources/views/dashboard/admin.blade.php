<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Admin Dashboard</p>
                <h1 class="section-title mt-2">Marketplace Control Panel</h1>
                <p class="section-copy mt-2">Monitor users, listings, and transaction activity for the UM-Pasa demo environment.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('admin.users') }}" class="btn-secondary">View Users</a>
                <a href="{{ route('admin.items') }}" class="btn-primary">Moderate Items</a>
            </div>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="glass-card p-5">
                <div class="text-sm text-slate-300">Users</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['users'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Registered UM accounts in the system.</p>
            </div>
            <div class="glass-card p-5">
                <div class="text-sm text-slate-300">Items</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['items'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Marketplace listings posted by students.</p>
            </div>
            <div class="glass-card p-5">
                <div class="text-sm text-slate-300">Transactions</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['transactions'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Total request records across the platform.</p>
            </div>
            <div class="glass-card p-5">
                <div class="text-sm text-slate-300">Completed Deals</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['completed'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Transactions already marked as completed.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="glass-card p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-white">Quick Actions</h2>
                    <span class="text-sm text-slate-400">Admin tools</span>
                </div>
                <div class="mt-4 grid gap-3">
                    <a href="{{ route('admin.users') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">Open user list</a>
                    <a href="{{ route('admin.transactions') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">Review transaction overview</a>
                    <a href="{{ route('admin.items') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">Delete inappropriate items</a>
                </div>
            </div>

            <div class="glass-card p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-white">Recent Transactions</h2>
                    <a href="{{ route('admin.transactions') }}" class="text-sm text-red-200">See all</a>
                </div>
                <div class="mt-4 space-y-3">
                    @foreach($transactions as $transaction)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="font-semibold text-white">{{ $transaction->item->title }}</div>
                                    <div class="mt-1 text-sm text-slate-300">{{ $transaction->buyer->name }} to {{ $transaction->seller->name }}</div>
                                </div>
                                <span class="badge-base border-white/15 bg-white/10 text-slate-200">{{ ucfirst($transaction->status) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
