@php
    $percent = fn ($value, $total) => $total > 0 ? round(($value / $total) * 100, 1) : 0;
    $maxCategory = max(1, (int) ($categoryChart->max('total') ?? 0));
    $maxDepartment = max(1, (int) ($departmentChart->max('total') ?? 0));
    $maxMonth = max(1, (int) ($monthlyChart->max('total') ?? 0));
    $salesPercent = $percent($stats['sales'], $stats['items']);
    $rentalPercent = $percent($stats['rentals'], $stats['items']);
    $approvedPercent = $percent($stats['approvedItems'], $stats['items']);
    $pendingPercent = $percent($stats['pendingItems'], $stats['items']);
    $rejectedPercent = $percent($stats['rejectedItems'], $stats['items']);
    $studentPercent = $percent($stats['students'], $stats['users']);
    $activePercent = $percent($stats['activeListings'], $stats['items']);
    $completedPercent = $percent($stats['completed'], $stats['transactions']);

    $statCards = [
        [
            'label' => 'Total Users',
            'value' => $stats['users'],
            'meta' => $stats['students'].' student accounts',
            'route' => route('admin.users'),
            'tone' => 'red',
            'icon' => 'users',
        ],
        [
            'label' => 'Total Listings',
            'value' => $stats['items'],
            'meta' => $stats['activeListings'].' active listings',
            'route' => route('admin.items'),
            'tone' => 'orange',
            'icon' => 'tag',
        ],
        [
            'label' => 'Transactions',
            'value' => $stats['transactions'],
            'meta' => $stats['completed'].' completed',
            'route' => route('admin.transactions'),
            'tone' => 'red',
            'icon' => 'arrows',
        ],
        [
            'label' => 'Completed Deals',
            'value' => $stats['completed'],
            'meta' => $completedPercent.'% completion share',
            'route' => route('admin.transactions'),
            'tone' => 'gold',
            'icon' => 'check',
        ],
        [
            'label' => 'Student Accounts',
            'value' => $stats['students'],
            'meta' => $studentPercent.'% of total users',
            'route' => route('admin.users'),
            'tone' => 'red',
            'icon' => 'cap',
        ],
        [
            'label' => 'Pending Review',
            'value' => $stats['pendingItems'],
            'meta' => $pendingPercent.'% of listings',
            'route' => route('admin.items'),
            'tone' => 'orange',
            'icon' => 'clock',
        ],
        [
            'label' => 'Approved Listings',
            'value' => $stats['approvedItems'],
            'meta' => $approvedPercent.'% of listings',
            'route' => route('admin.items'),
            'tone' => 'gold',
            'icon' => 'shield',
        ],
        [
            'label' => 'Active Listings',
            'value' => $stats['activeListings'],
            'meta' => $activePercent.'% of listings',
            'route' => route('marketplace.index'),
            'tone' => 'gold',
            'icon' => 'store',
        ],
    ];

    $recentActivities = $transactions
        ->toBase()
        ->map(fn ($transaction) => [
            'title' => 'Transaction '.str($transaction->status)->title().': '.($transaction->item?->title ?? 'Deleted listing'),
            'tag' => str($transaction->status)->title(),
            'tone' => $transaction->status,
            'time' => $transaction->created_at->diffForHumans(),
            'created_at' => $transaction->created_at,
        ])
        ->merge($users->map(fn ($userItem) => [
            'title' => 'New user registered: '.$userItem->email,
            'tag' => str($userItem->role)->title(),
            'tone' => 'user',
            'time' => $userItem->created_at->diffForHumans(),
            'created_at' => $userItem->created_at,
        ]))
        ->sortByDesc('created_at')
        ->take(5)
        ->values();
@endphp

<x-app-layout>
    <x-slot name="header">
        <section class="admin-hero">
            <div class="admin-hero-glow"></div>
            <div class="relative z-10 grid gap-8 xl:grid-cols-[1fr_auto] xl:items-center">
                <div>
                    <p class="admin-kicker">Admin Dashboard</p>
                    <h1 class="admin-hero-title">Marketplace Oversight, made clear.</h1>
                    <p class="admin-hero-copy">Monitor users, listings, approvals, and transactions across UM-Pasa in one organized control panel.</p>
                </div>

                <div class="admin-hero-actions">
                    <a href="{{ route('admin.reports') }}" class="admin-action-button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l4 4v14H7z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M9 15h6M9 18h4"></path>
                        </svg>
                        Export Report
                    </a>
                    <a href="{{ route('admin.users') }}" class="admin-action-button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 19v-1a4 4 0 0 0-8 0v1M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM18 8a3 3 0 0 1 0 6M21 19v-1a3.5 3.5 0 0 0-3-3.45"></path>
                        </svg>
                        Manage Users
                    </a>
                    <a href="{{ route('admin.items') }}" class="admin-action-button admin-action-button-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 19 6v5c0 4.5-2.9 8.3-7 10-4.1-1.7-7-5.5-7-10V6z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-5"></path>
                        </svg>
                        Review Listings
                    </a>
                </div>
            </div>
        </section>
    </x-slot>

    <div class="page-wrap space-y-5">
        <section class="admin-stat-grid">
            @foreach($statCards as $card)
                <a href="{{ $card['route'] }}" class="admin-stat-card admin-stat-{{ $card['tone'] }}">
                    <div class="admin-stat-icon">
                        @switch($card['icon'])
                            @case('users')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 19v-1a4 4 0 0 0-8 0v1M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM18 8a3 3 0 0 1 0 6M21 19v-1a3.5 3.5 0 0 0-3-3.45M6 8a3 3 0 0 0 0 6M3 19v-1a3.5 3.5 0 0 1 3-3.45"></path></svg>
                                @break
                            @case('tag')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12 12 4H5v7l8 8z"></path><path stroke-linecap="round" d="M8.5 8.5h.01"></path></svg>
                                @break
                            @case('arrows')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h13l-4-4M17 17H4l4 4"></path></svg>
                                @break
                            @case('cap')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m3 8 9-4 9 4-9 4zM7 10v5c2.5 2 7.5 2 10 0v-5"></path></svg>
                                @break
                            @case('clock')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"></circle><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"></path></svg>
                                @break
                            @case('shield')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 19 6v5c0 4.5-2.9 8.3-7 10-4.1-1.7-7-5.5-7-10V6z"></path><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-5"></path></svg>
                                @break
                            @case('store')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 10h16l-1.5-5h-13zM6 10v10h12V10M9 20v-5h6v5"></path></svg>
                                @break
                            @default
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-5"></path><circle cx="12" cy="12" r="8"></circle></svg>
                        @endswitch
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-label">{{ $card['label'] }}</div>
                        <div class="admin-stat-value">{{ number_format($card['value']) }}</div>
                        <div class="admin-stat-meta">{{ $card['meta'] }}</div>
                    </div>
                </a>
            @endforeach
        </section>

        <section class="grid gap-5 xl:grid-cols-[1.05fr_0.95fr_0.8fr_1.1fr]">
            <div class="admin-panel">
                <div class="admin-panel-header">
                    <div class="admin-panel-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18V9M12 18V5M18 18v-7"></path></svg>
                    </div>
                    <h2>Listing Activity</h2>
                </div>
                <div class="admin-bar-list">
                    @forelse($categoryChart as $row)
                        @php($width = max(7, ($row->total / $maxCategory) * 100))
                        <div class="admin-bar-row">
                            <span>{{ $row->category ?: 'General' }}</span>
                            <div class="admin-bar-track"><b style="width: {{ $width }}%"></b></div>
                            <strong>{{ $row->total }}</strong>
                        </div>
                    @empty
                        <p class="admin-empty">No listing activity yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-panel-header">
                    <div class="admin-panel-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 12h10M7 17h6"></path></svg>
                    </div>
                    <h2>Moderation Status</h2>
                </div>
                <div class="admin-bar-list">
                    @foreach([
                        ['label' => 'Pending', 'total' => $stats['pendingItems'], 'percent' => $pendingPercent],
                        ['label' => 'Approved', 'total' => $stats['approvedItems'], 'percent' => $approvedPercent],
                        ['label' => 'Rejected', 'total' => $stats['rejectedItems'], 'percent' => $rejectedPercent],
                    ] as $row)
                        <div class="admin-bar-row">
                            <span>{{ $row['label'] }}</span>
                            <div class="admin-bar-track"><b style="width: {{ max(6, $row['percent']) }}%"></b></div>
                            <strong>{{ $row['total'] }} <em>{{ $row['percent'] }}%</em></strong>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-panel-header">
                    <div class="admin-panel-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M4.5 8h15M6 16h12"></path></svg>
                    </div>
                    <h2>Listing Types</h2>
                </div>
                <div class="admin-donut-wrap">
                    <div class="admin-donut" style="--sales: {{ $salesPercent }};">
                        <span>{{ number_format($stats['items']) }}</span>
                        <small>Total</small>
                    </div>
                    <div class="admin-donut-legend">
                        <div><span class="dot-sale"></span>Sales <strong>{{ $salesPercent }}%</strong></div>
                        <div><span class="dot-rent"></span>Rentals <strong>{{ $rentalPercent }}%</strong></div>
                    </div>
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-panel-header">
                    <div class="admin-panel-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 18h16M6 15l4-4 3 2 5-7"></path></svg>
                    </div>
                    <h2>Monthly Transactions</h2>
                </div>
                <div class="admin-trend">
                    @forelse($monthlyChart as $row)
                        @php($height = max(10, ($row['total'] / $maxMonth) * 100))
                        <div class="admin-trend-col">
                            <strong>{{ $row['total'] }}</strong>
                            <span style="height: {{ $height }}%"></span>
                            <small>{{ $row['label'] }}</small>
                        </div>
                    @empty
                        <p class="admin-empty">No transaction trend yet.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="grid gap-5 xl:grid-cols-[1fr_0.98fr]">
            <div class="admin-panel">
                <div class="admin-panel-header">
                    <div class="admin-panel-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 20h16M6 20V8l6-4 6 4v12M10 20v-6h4v6"></path></svg>
                    </div>
                    <h2>Most Active Departments</h2>
                </div>
                <div class="admin-department-list">
                    @forelse($departmentChart as $row)
                        @php($width = max(7, ($row->total / $maxDepartment) * 100))
                        <div class="admin-department-row">
                            <span>{{ $row->department ?: 'Unassigned' }}</span>
                            <div class="admin-bar-track"><b style="width: {{ $width }}%"></b></div>
                            <strong>{{ $row->total }}</strong>
                        </div>
                    @empty
                        <p class="admin-empty">No department activity yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-panel-header admin-panel-header-split">
                    <div class="flex items-center gap-4">
                        <div class="admin-panel-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 19 6v5c0 4.5-2.9 8.3-7 10-4.1-1.7-7-5.5-7-10V6z"></path></svg>
                        </div>
                        <h2>Recent Admin Activity</h2>
                    </div>
                    <a href="{{ route('admin.transactions') }}">View All</a>
                </div>
                <div class="admin-activity-list">
                    @forelse($recentActivities as $activity)
                        <div class="admin-activity-row">
                            <div class="admin-activity-icon"></div>
                            <p>{{ $activity['title'] }}</p>
                            <span class="admin-activity-tag admin-activity-{{ $activity['tone'] }}">{{ $activity['tag'] }}</span>
                            <time>{{ $activity['time'] }}</time>
                        </div>
                    @empty
                        <p class="admin-empty">No recent admin activity yet.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
