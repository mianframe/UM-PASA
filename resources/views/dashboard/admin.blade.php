<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Admin Dashboard</p>
                <h1 class="section-title mt-2">Marketplace Control Panel</h1>
                <p class="section-copy mt-2">Monitor users, listings, and transaction activity across UM-Pasa.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('admin.reports') }}" class="btn-secondary">Generate Report</a>
                <a href="{{ route('admin.users') }}" class="btn-secondary">View Users</a>
                <a href="{{ route('admin.items') }}" class="btn-primary">Moderate Items</a>
            </div>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('admin.users') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Users</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['users'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Registered UM accounts in the system.</p>
            </a>
            <a href="{{ route('admin.items') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Items</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['items'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Marketplace listings posted by students.</p>
            </a>
            <a href="{{ route('admin.transactions') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Transactions</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['transactions'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Total request records across the platform.</p>
            </a>
            <a href="{{ route('admin.transactions', ['status' => 'completed']) }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Completed Deals</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['completed'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Transactions already marked as completed.</p>
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('admin.users') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Students</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['students'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Student role accounts.</p>
            </a>
            <a href="{{ route('admin.items') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Pending Items</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['pendingItems'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Listings waiting for review.</p>
            </a>
            <a href="{{ route('admin.items') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Approved Items</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['approvedItems'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Approved marketplace content.</p>
            </a>
            <a href="{{ route('marketplace.index') }}" class="glass-card block p-5 transition hover:-translate-y-1">
                <div class="text-sm text-slate-300">Active Listings</div>
                <div class="mt-3 text-3xl font-bold text-white">{{ $stats['activeListings'] }}</div>
                <p class="mt-2 text-sm text-slate-400">Visible and available listings.</p>
            </a>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="glass-card p-6">
                <h2 class="text-lg font-semibold text-white">Most Listed Categories</h2>
                <div class="mt-5 space-y-4">
                    @foreach($categoryChart as $row)
                        @php($width = $categoryChart->max('total') ? max(8, ($row->total / $categoryChart->max('total')) * 100) : 0)
                        <div class="analytics-row">
                            <div class="analytics-row-label"><span>{{ $row->category ?: 'General' }}</span><strong>{{ $row->total }}</strong></div>
                            <div class="analytics-bar"><span style="width: {{ $width }}%"></span></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="glass-card p-6">
                <h2 class="text-lg font-semibold text-white">Approval Status</h2>
                <div class="mt-5 space-y-4">
                    @foreach($approvalChart as $row)
                        @php($width = $stats['items'] ? max(8, ($row['total'] / $stats['items']) * 100) : 0)
                        <div class="analytics-row">
                            <div class="analytics-row-label"><span>{{ $row['label'] }}</span><strong>{{ $row['total'] }}</strong></div>
                            <div class="analytics-bar"><span style="width: {{ $width }}%"></span></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="glass-card p-6">
                <h2 class="text-lg font-semibold text-white">Listing Types</h2>
                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    @foreach($typeChart as $row)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-sm text-slate-300">{{ $row['label'] }}</div>
                            <div class="mt-2 text-3xl font-bold text-white">{{ $row['total'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="glass-card p-6">
                <h2 class="text-lg font-semibold text-white">Monthly Transactions</h2>
                <div class="mt-5 space-y-4">
                    @forelse($monthlyChart as $row)
                        @php($width = $monthlyChart->max('total') ? max(8, ($row['total'] / $monthlyChart->max('total')) * 100) : 0)
                        <div class="analytics-row">
                            <div class="analytics-row-label"><span>{{ $row['label'] }}</span><strong>{{ $row['total'] }}</strong></div>
                            <div class="analytics-bar"><span style="width: {{ $width }}%"></span></div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">No transaction trend yet.</p>
                    @endforelse
                </div>
            </div>
            <div class="glass-card p-6 xl:col-span-2">
                <h2 class="text-lg font-semibold text-white">Most Active Departments</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach($departmentChart as $row)
                        @php($width = $departmentChart->max('total') ? max(8, ($row->total / $departmentChart->max('total')) * 100) : 0)
                        <div class="analytics-row">
                            <div class="analytics-row-label"><span>{{ $row->department ?: 'Unassigned' }}</span><strong>{{ $row->total }}</strong></div>
                            <div class="analytics-bar"><span style="width: {{ $width }}%"></span></div>
                        </div>
                    @endforeach
                </div>
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
                    <a href="{{ route('admin.items') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">Approve, reject, or delete listed items</a>
                    <a href="{{ route('admin.reports') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200 transition hover:bg-white/10">Generate admin report</a>
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
