<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="section-kicker">Help</p>
            <h1 class="section-title">Instructions and support</h1>
            <p class="section-copy">Use this page for quick guidance on listing, requests, messaging, and account support.</p>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-white">How to use the marketplace</h2>
                <div class="mt-4 space-y-4 text-sm leading-7 text-[#eedcbbcc]">
                    <p><strong class="text-white">1. Browse or search:</strong> open the marketplace and use search, category chips, or filters to find items.</p>
                    <p><strong class="text-white">2. Open a listing:</strong> check the item details, condition, category, and price before sending a request.</p>
                    <p><strong class="text-white">3. Request or message:</strong> send a direct request or open a conversation with the seller.</p>
                    <p><strong class="text-white">4. Confirm meetup:</strong> use chat or the transaction flow to finalize where and when the exchange happens.</p>
                    <p><strong class="text-white">5. Complete and rate:</strong> finish the transaction, then leave a rating and feedback.</p>
                </div>
            </div>

            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-white">Posting instructions</h2>
                <div class="mt-4 space-y-4 text-sm leading-7 text-[#eedcbbcc]">
                    <p><strong class="text-white">Choose a category:</strong> select the closest match such as Books, Uniforms, Gadgets, Calculators, Supplies, or Lab & Science.</p>
                    <p><strong class="text-white">Select the right department and program:</strong> this keeps filters accurate for other students.</p>
                    <p><strong class="text-white">Describe the item clearly:</strong> include condition, price, and meetup notes when possible.</p>
                    <p><strong class="text-white">Upload an image:</strong> use a clear photo for faster item recognition.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-white">Common questions</h2>
                <div class="mt-4 space-y-4 text-sm leading-7 text-[#eedcbbcc]">
                    <p><strong class="text-white">Who can register?</strong> Only UM email accounts are accepted.</p>
                    <p><strong class="text-white">Can I message before requesting?</strong> Yes. The message area is available from item pages.</p>
                    <p><strong class="text-white">How do I find my pending requests?</strong> Open the dashboard or transaction history pages.</p>
                    <p><strong class="text-white">Where do I see ratings?</strong> Open your profile ratings page after completed deals.</p>
                </div>
            </div>

            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-white">Contact support</h2>
                <div class="mt-4 space-y-3 text-sm text-[#eedcbbcc]">
                    <p>If you run into issues with login, posting, requests, or messages, contact support using the details below.</p>
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">Email</div>
                        <div class="mt-1">support@umpasa.local</div>
                    </div>
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">Office</div>
                        <div class="mt-1">Student Services Desk</div>
                    </div>
                    <div class="glass-panel p-4">
                        <div class="font-semibold text-white">Support Hours</div>
                        <div class="mt-1">Monday to Friday · 8:00 AM to 5:00 PM</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
