<nav x-data="{ open: false }" class="top-nav">
    <div class="page-wrap">
        <div class="nav-shell">
            <a href="{{ route('home') }}" class="nav-brand justify-self-start">
                <div class="nav-logo-box overflow-hidden p-0">
                    <img src="{{ asset('UMPASALOGO.png') }}" alt="UM-Pasa logo" class="h-full w-full object-cover">
                </div>
                <div>
                    <div class="nav-brand-title">UM-Pasa</div>
                    <div class="nav-brand-subtitle">Campus Marketplace</div>
                </div>
            </a>

            <div class="nav-capsule justify-self-center">
                <a href="{{ route('home') }}" class="nav-pill {{ request()->routeIs('home') ? 'nav-pill-active' : '' }}">Home</a>
                <a href="{{ route('marketplace.index') }}" class="nav-pill {{ request()->routeIs('marketplace.*') || request()->routeIs('items.*') ? 'nav-pill-active' : '' }}">Marketplace</a>
                @auth
                    <a href="{{ route('messages.index') }}" class="nav-pill {{ request()->routeIs('messages.*') ? 'nav-pill-active' : '' }}">Messages</a>
                    <a href="{{ route('dashboard') }}" class="nav-pill {{ request()->routeIs('dashboard') ? 'nav-pill-active' : '' }}">Dashboard</a>
                @endauth
            </div>

            <div class="nav-side justify-self-end">
                <button type="button" onclick="window.toggleTheme()" class="theme-toggle" aria-label="Toggle color theme" title="Toggle theme">
                    <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.5 14.6A7.7 7.7 0 0 1 9.4 3.5 8.8 8.8 0 1 0 20.5 14.6Z"></path>
                    </svg>
                    <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path stroke-linecap="round" d="M12 2.5v2M12 19.5v2M4.6 4.6 6 6M18 18l1.4 1.4M2.5 12h2M19.5 12h2M4.6 19.4 6 18M18 6l1.4-1.4"></path>
                    </svg>
                </button>
                @auth
                    <a href="{{ route('notifications.index') }}" class="nav-icon-button" aria-label="Notifications" title="Notifications">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 21h4"></path>
                        </svg>
                        @php $unreadNotifications = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                        @if($unreadNotifications)
                            <span class="nav-icon-badge">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                    <a href="{{ route('profile.edit') }}" class="nav-auth-soft">{{ Auth::user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-auth-strong">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-auth-soft">Log in</a>
                    <a href="{{ route('register') }}" class="nav-auth-strong">Sign up</a>
                @endauth
            </div>

            <button @click="open = !open" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white lg:hidden justify-self-end">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"></path>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6l-12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition class="glass-panel rounded-none border-x-0 border-t border-white/10 lg:hidden">
        <div class="page-wrap space-y-2 py-4">
            <a href="{{ route('home') }}" class="nav-pill block {{ request()->routeIs('home') ? 'nav-pill-active' : '' }}">Home</a>
            <a href="{{ route('marketplace.index') }}" class="nav-pill block {{ request()->routeIs('marketplace.*') || request()->routeIs('items.*') ? 'nav-pill-active' : '' }}">Marketplace</a>
            @auth
                <a href="{{ route('dashboard') }}" class="nav-pill block {{ request()->routeIs('dashboard') ? 'nav-pill-active' : '' }}">Dashboard</a>
                <a href="{{ route('transactions.history') }}" class="nav-pill block {{ request()->routeIs('transactions.*') ? 'nav-pill-active' : '' }}">Transactions</a>
                <a href="{{ route('messages.index') }}" class="nav-pill block {{ request()->routeIs('messages.*') ? 'nav-pill-active' : '' }}">Messages</a>
                <a href="{{ route('notifications.index') }}" class="nav-pill block">Alerts</a>
                <a href="{{ route('profile.edit') }}" class="nav-pill block">Profile</a>
                <button type="button" onclick="window.toggleTheme()" class="nav-pill flex w-full items-center gap-3 text-left">
                    <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.5 14.6A7.7 7.7 0 0 1 9.4 3.5 8.8 8.8 0 1 0 20.5 14.6Z"></path>
                    </svg>
                    <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path stroke-linecap="round" d="M12 2.5v2M12 19.5v2M4.6 4.6 6 6M18 18l1.4 1.4M2.5 12h2M19.5 12h2M4.6 19.4 6 18M18 6l1.4-1.4"></path>
                    </svg>
                    Theme
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-primary mt-2 w-full">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-pill block">Login</a>
                <button type="button" onclick="window.toggleTheme()" class="nav-pill flex w-full items-center gap-3 text-left">
                    <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.5 14.6A7.7 7.7 0 0 1 9.4 3.5 8.8 8.8 0 1 0 20.5 14.6Z"></path>
                    </svg>
                    <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path stroke-linecap="round" d="M12 2.5v2M12 19.5v2M4.6 4.6 6 6M18 18l1.4 1.4M2.5 12h2M19.5 12h2M4.6 19.4 6 18M18 6l1.4-1.4"></path>
                    </svg>
                    Theme
                </button>
                <a href="{{ route('register') }}" class="btn-primary mt-2 w-full">Register</a>
            @endauth
        </div>
    </div>
</nav>
