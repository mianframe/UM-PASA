<footer class="um-footer mt-12 pb-10 pt-8">
    <div class="page-wrap">
        <div class="footer-glass liquid-rise">
            <div class="footer-main-grid">
                <div class="footer-brand-block">
                    <div class="flex items-start gap-4 sm:gap-5">
                        <div class="footer-logo-ring shrink-0">
                            <img src="{{ asset('UMPASALOGO.png') }}" alt="UM-Pasa logo" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <div class="footer-brand-title">UM-Pasa</div>
                            <div class="footer-brand-subtitle">Campus Marketplace</div>
                            <p class="footer-brand-copy">
                                Browse items, post listings, request transactions, open conversations, and track marketplace activity from one student workspace.
                            </p>
                        </div>
                    </div>

                    <div class="footer-mini-actions">
                        <a href="mailto:support@umpasa.local" class="footer-icon-button" aria-label="Email support" title="Email support">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"></path>
                            </svg>
                        </a>
                        @auth
                            <a href="{{ route('messages.index') }}" class="footer-icon-button" aria-label="Open inbox" title="Inbox">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a7.5 7.5 0 0 1-7.5 7.5H8l-5 2 1.7-4.3A7.5 7.5 0 1 1 21 12Z"></path>
                                </svg>
                            </a>
                        @endauth
                        <a href="{{ route('help') }}" class="footer-icon-button" aria-label="Open help" title="Help">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.7 3 8.4 7 10 4-1.6 7-5.3 7-10V6l-7-3Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.5 12 1.8 1.8 3.7-4"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="footer-separator hidden xl:block"></div>

                <div class="footer-links-block">
                    <div class="footer-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 13a5 5 0 0 0 7.1 0l2.1-2.1a5 5 0 0 0-7.1-7.1L11 4.9"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 11a5 5 0 0 0-7.1 0l-2.1 2.1a5 5 0 0 0 7.1 7.1L13 19.1"></path>
                        </svg>
                        Quick Links
                    </div>
                    <div class="footer-link-grid">
                        <a href="{{ route('about') }}" class="footer-pill">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            About Us
                        </a>
                        <a href="{{ route('marketplace.index') }}" class="footer-pill">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path stroke-linecap="round" d="m20 20-3.5-3.5"></path>
                            </svg>
                            Browse Items
                        </a>
                        @auth
                            <a href="{{ route('messages.index') }}" class="footer-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a7.5 7.5 0 0 1-7.5 7.5H8l-5 2 1.7-4.3A7.5 7.5 0 1 1 21 12Z"></path>
                                </svg>
                                Inbox
                            </a>
                            <a href="{{ route('dashboard') }}" class="footer-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <rect x="4" y="4" width="6" height="6" rx="1.5"></rect>
                                    <rect x="14" y="4" width="6" height="6" rx="1.5"></rect>
                                    <rect x="4" y="14" width="6" height="6" rx="1.5"></rect>
                                    <rect x="14" y="14" width="6" height="6" rx="1.5"></rect>
                                </svg>
                                Dashboard
                            </a>
                        @endauth
                        <a href="{{ route('help') }}" class="footer-pill">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.8 9.2a2.4 2.4 0 0 1 4.6 1c0 1.7-2.4 1.9-2.4 3.5"></path>
                                <path stroke-linecap="round" d="M12 17h.01"></path>
                            </svg>
                            Help
                        </a>
                        <a href="{{ route('help') }}#contact-us" class="footer-pill">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.9v3a2 2 0 0 1-2.2 2 19.7 19.7 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.7 19.7 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.8a2 2 0 0 1-.4 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"></path>
                            </svg>
                            Contact
                        </a>
                    </div>
                </div>

                <div class="footer-instructions">
                    <div class="footer-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"></path>
                            <path stroke-linecap="round" d="M8 7h8M8 11h8"></path>
                        </svg>
                        Quick Instructions
                    </div>
                    <ol class="footer-step-list">
                        <li class="footer-step">
                            <span>1</span>
                            <p>Browse the marketplace or search by item type and category.</p>
                        </li>
                        <li class="footer-step">
                            <span>2</span>
                            <p>Open a listing and send a request or start a message.</p>
                        </li>
                        <li class="footer-step">
                            <span>3</span>
                            <p>Confirm the meetup details, complete the transaction, and leave feedback.</p>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-copyright">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.7 3 8.4 7 10 4-1.6 7-5.3 7-10V6l-7-3Z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.5 12 1.8 1.8 3.7-4"></path>
                    </svg>
                    UM-Pasa © {{ now()->year }}
                </div>
                <a href="mailto:support@umpasa.local" class="footer-support">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"></path>
                    </svg>
                    support@umpasa.local
                </a>
                <div class="footer-badges">
                    <span class="market-card-bottom-pill">Liquid glass UI</span>
                    <span class="market-card-bottom-pill">Campus-safe trading</span>
                </div>
            </div>
        </div>
    </div>
</footer>
