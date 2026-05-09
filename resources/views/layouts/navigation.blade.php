<nav x-data="{ open: false }" class="top-nav">
    <div class="page-wrap">
        <div class="nav-shell">
            <a href="{{ route('home') }}" class="nav-brand justify-self-start">
                <div class="nav-logo-box">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 9.5 12 4l8 5.5"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 10.5V20h11V10.5"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 13.5h5"></path>
                    </svg>
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
                <button type="button" onclick="window.toggleTheme()" class="theme-toggle">
                    <span class="theme-toggle-dot"></span>
                    <span class="theme-toggle-label">Theme</span>
                </button>
                @auth
                    <a href="{{ route('notifications.index') }}" class="btn-ghost">
                        Alerts
                        @php $unreadNotifications = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                        @if($unreadNotifications)
                            <span class="badge-sale">{{ $unreadNotifications }}</span>
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

    <div x-show="open" x-transition class="border-t border-white/10 bg-[#161617] lg:hidden">
        <div class="page-wrap space-y-2 py-4">
            <a href="{{ route('home') }}" class="nav-pill block {{ request()->routeIs('home') ? 'nav-pill-active' : '' }}">Home</a>
            <a href="{{ route('marketplace.index') }}" class="nav-pill block {{ request()->routeIs('marketplace.*') || request()->routeIs('items.*') ? 'nav-pill-active' : '' }}">Marketplace</a>
            @auth
                <a href="{{ route('dashboard') }}" class="nav-pill block {{ request()->routeIs('dashboard') ? 'nav-pill-active' : '' }}">Dashboard</a>
                <a href="{{ route('transactions.history') }}" class="nav-pill block {{ request()->routeIs('transactions.*') ? 'nav-pill-active' : '' }}">Transactions</a>
                <a href="{{ route('messages.index') }}" class="nav-pill block {{ request()->routeIs('messages.*') ? 'nav-pill-active' : '' }}">Messages</a>
                <a href="{{ route('notifications.index') }}" class="nav-pill block">Alerts</a>
                <a href="{{ route('profile.edit') }}" class="nav-pill block">Profile</a>
                <button type="button" onclick="window.toggleTheme()" class="nav-pill block w-full text-left">Toggle theme</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-primary mt-2 w-full">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-pill block">Login</a>
                <button type="button" onclick="window.toggleTheme()" class="nav-pill block w-full text-left">Toggle theme</button>
                <a href="{{ route('register') }}" class="btn-primary mt-2 w-full">Register</a>
            @endauth
        </div>
    </div>
</nav>
