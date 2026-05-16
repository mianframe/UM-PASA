<footer class="um-footer mt-10 pb-6 pt-5">
    <div class="page-wrap">
        <div class="footer-glass liquid-rise">
            <div class="footer-main-grid">
                <div class="footer-brand-block">
                    <div class="flex items-center gap-3">
                        <div class="footer-logo-ring shrink-0">
                            <img src="{{ asset('UMPASALOGO.png') }}" alt="UM-Pasa logo" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <div class="footer-brand-title">UM-Pasa</div>
                            <div class="footer-brand-subtitle">Campus Marketplace</div>
                        </div>
                    </div>
                    <p class="footer-brand-copy">Campus-focused buying, selling, renting, messaging, and transaction tracking.</p>
                </div>

                <div class="footer-links-block">
                    <div class="footer-section-title">Quick Links</div>
                    <div class="footer-link-grid">
                        <a href="{{ route('marketplace.index') }}" class="footer-pill">Marketplace</a>
                        <a href="{{ route('about') }}" class="footer-pill">About</a>
                        <a href="{{ route('help') }}" class="footer-pill">Help</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="footer-pill">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="footer-pill">Log in</a>
                        @endauth
                    </div>
                </div>

                <div class="footer-instructions">
                    <div class="footer-section-title">Quick Steps</div>
                    <ol class="footer-step-list">
                        <li class="footer-step"><span>1</span><p>Browse or post a listing.</p></li>
                        <li class="footer-step"><span>2</span><p>Request, message, and confirm meetup details.</p></li>
                    </ol>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-copyright">UM-Pasa © {{ now()->year }}</div>
                <a href="mailto:support@umpasa.local" class="footer-support">support@umpasa.local</a>
            </div>
        </div>
    </div>
</footer>
