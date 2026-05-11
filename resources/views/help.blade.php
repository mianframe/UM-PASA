<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="section-kicker">Help</p>
            <h1 class="section-title">Instructions and support</h1>
            <p class="section-copy">Use this page for marketplace flow, payment modes, rental reminders, and contact links.</p>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <div id="system-flow" class="hero-flow-card scroll-mt-28">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.24em] text-[#f3df32]">System Flow</div>
                    <div class="mt-3 text-2xl font-extrabold leading-tight text-white">List item. Admin reviews. Buyer requests. Payment proof uploaded. Transaction tracked.</div>
                </div>
                <div class="hero-flow-mark">UM</div>
            </div>
            <div class="flow-steps">
                <div class="flow-step">
                    <div class="flow-icon flow-icon-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 12 12 4H5v7l8 8 7-7Z"></path><path d="M8.5 8.5h.01"></path></svg></div>
                    <span>01</span>
                    <div><strong>Post listing</strong><p>Seller lists sale or rental terms and accepted payments.</p></div>
                </div>
                <div class="flow-step">
                    <div class="flow-icon flow-icon-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m9 12 2 2 4-5"></path><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"></path></svg></div>
                    <span>02</span>
                    <div><strong>Admin review</strong><p>Admin approves or rejects the listing with a reason when needed.</p></div>
                </div>
                <div class="flow-step">
                    <div class="flow-icon flow-icon-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.3 8.8 8.8 0 0 1-3.8-.9L3 20l1.2-4.6A8 8 0 1 1 21 11.5Z"></path></svg></div>
                    <span>03</span>
                    <div><strong>Request and pay</strong><p>Buyer selects payment mode, rental duration if applicable, and uploads proof.</p></div>
                </div>
                <div class="flow-step">
                    <div class="flow-icon flow-icon-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 7h16"></path><path d="M6 7v13h12V7"></path><path d="M9 11h6"></path></svg></div>
                    <span>04</span>
                    <div><strong>Track and report</strong><p>Students monitor status, due dates, proof status, receipts, and reports.</p></div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div id="instructions" class="glass-card scroll-mt-28 p-6">
                <h2 class="text-xl font-bold text-white">How to use the marketplace</h2>
                <div class="mt-4 space-y-4 text-sm leading-7 text-[#eedcbbcc]">
                    <p><strong class="text-white">1. Browse or search:</strong> open the marketplace and use search, category chips, or filters to find items.</p>
                    <p><strong class="text-white">2. Open a listing:</strong> check the item details, condition, category, and price before sending a request.</p>
                    <p><strong class="text-white">3. Request or message:</strong> send a direct request or open a conversation with the seller.</p>
                    <p><strong class="text-white">4. Confirm meetup:</strong> use chat or the transaction flow to finalize where and when the exchange happens.</p>
                    <p><strong class="text-white">5. Complete and rate:</strong> finish the transaction, then leave a rating and feedback.</p>
                </div>
            </div>

            <div id="payment-methods" class="glass-card scroll-mt-28 p-6">
                <h2 class="text-xl font-bold text-white">Payment Methods</h2>
                <div class="mt-4 space-y-4 text-sm leading-7 text-[#eedcbbcc]">
                    <p><strong class="text-white">GCash:</strong> mobile wallet payment selected during request.</p>
                    <p><strong class="text-white">Maya:</strong> mobile wallet payment selected during request.</p>
                    <p><strong class="text-white">Bank Transfer:</strong> bank-to-bank payment with uploaded proof.</p>
                    <p><strong class="text-white">Cash on Pickup:</strong> cash payment during agreed meetup or pickup.</p>
                    <p><strong class="text-white">Other payment method:</strong> custom method specified by the buyer and accepted by the seller.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
            <div id="common-questions" class="glass-card scroll-mt-28 p-6">
                <h2 class="text-xl font-bold text-white">Common questions</h2>
                <div class="mt-4 space-y-4 text-sm leading-7 text-[#eedcbbcc]">
                    <p><strong class="text-white">Who can register?</strong> Only UM email accounts are accepted.</p>
                    <p><strong class="text-white">Can I message before requesting?</strong> Yes. The message area is available from item pages.</p>
                    <p><strong class="text-white">How do I find my pending requests?</strong> Open the dashboard or transaction history pages.</p>
                    <p><strong class="text-white">Where do I see ratings?</strong> Open your profile ratings page after completed deals.</p>
                </div>
            </div>

            <div id="contact-us" class="glass-card scroll-mt-28 p-6">
                <h2 class="text-xl font-bold text-white">Contact Us</h2>
                <div class="mt-4 space-y-3 text-sm text-[#eedcbbcc]">
                    <p>If you run into issues with login, posting, requests, messages, payment mode, or payment proof, use the contact links below.</p>
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">Email</div>
                        <a href="mailto:support@umpasa.local" class="mt-1 inline-block text-red-200">support@umpasa.local</a>
                    </div>
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">Office</div>
                        <div class="mt-1">Student Services Desk · Monday to Friday, 8:00 AM to 5:00 PM</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
