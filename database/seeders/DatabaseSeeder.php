<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Item;
use App\Models\Message;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    private const PASSWORD = 'password';

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedBaseUsers();
        $this->resetDemoUsers();
        $this->seedDemoFiles();

        $seller = $this->createUser('seller@umindanao.edu.ph', 'Ava Seller');
        $buyer = $this->createUser('buyer@umindanao.edu.ph', 'Marco Buyer');
        $renter = $this->createUser('renter@umindanao.edu.ph', 'Bea Renter');
        $secondSeller = $this->createUser('seller2@umindanao.edu.ph', 'Jessa Seller');

        $availableSale = $this->createItem($seller, [
            'title' => 'Engineering Calculator FX-991ES',
            'category' => 'Calculators',
            'description' => 'Scientific calculator with clean keys, fresh battery, and a protective case.',
            'department' => 'Department of Engineering Education',
            'program' => 'Major in Computer Engineering',
            'course_code' => 'ENG101',
            'listing_type' => Item::TYPE_SELL,
            'condition' => 'good',
            'price' => 650,
            'accepted_payment_methods' => ['gcash', 'cash_on_pickup'],
        ]);

        $availableRental = $this->createItem($seller, [
            'title' => 'Arduino Starter Kit Rental',
            'category' => 'Lab & Science',
            'description' => 'Starter kit with board, jumper wires, breadboard, LEDs, sensors, and USB cable.',
            'department' => 'Department of Computing Education',
            'program' => 'BS in Information Technology',
            'course_code' => 'IT311',
            'listing_type' => Item::TYPE_RENT,
            'condition' => 'like_new',
            'price' => 75,
            'daily_rental_rate' => 75,
            'minimum_rental_days' => 2,
            'maximum_rental_days' => 7,
            'rental_duration_days' => 7,
            'accepted_payment_methods' => ['gcash', 'maya', 'cash_on_pickup'],
        ]);

        $pendingListing = $this->createItem($seller, [
            'title' => 'Nursing Uniform Set for Review',
            'category' => 'Uniforms',
            'description' => 'Complete uniform set waiting for admin moderation before it appears publicly.',
            'department' => 'Department of Arts and Sciences Education',
            'program' => 'BS in Psychology',
            'course_code' => 'NSTP101',
            'listing_type' => Item::TYPE_SELL,
            'condition' => 'good',
            'price' => 850,
            'moderation_status' => Item::MODERATION_PENDING,
            'accepted_payment_methods' => ['cash_on_pickup'],
        ]);

        $rejectedListing = $this->createItem($seller, [
            'title' => 'Rejected Photocopied Lab Manual',
            'category' => 'Books',
            'description' => 'Example rejected listing so the seller can see moderation feedback.',
            'department' => 'Department of Computing Education',
            'program' => 'BS in Computer Science',
            'course_code' => 'CS204',
            'listing_type' => Item::TYPE_SELL,
            'condition' => 'fair',
            'price' => 120,
            'moderation_status' => Item::MODERATION_REJECTED,
            'rejection_reason' => 'Please upload a clearer photo and confirm that the material is allowed for resale.',
            'accepted_payment_methods' => ['gcash'],
        ]);

        $pendingRequestItem = $this->createItem($seller, [
            'title' => 'Data Structures Textbook',
            'category' => 'Books',
            'description' => 'Used reference book with highlights on stacks, queues, trees, and graphs.',
            'department' => 'Department of Computing Education',
            'program' => 'BS in Computer Science',
            'course_code' => 'CS202',
            'listing_type' => Item::TYPE_SELL,
            'condition' => 'good',
            'price' => 480,
            'status' => Item::STATUS_PENDING,
            'accepted_payment_methods' => ['gcash', 'bank_transfer'],
        ]);

        $approvedRequestItem = $this->createItem($secondSeller, [
            'title' => 'Accounting Review Handouts',
            'category' => 'Books',
            'description' => 'Clean review handouts for accounting majors, bundled by topic.',
            'department' => 'Department of Accounting Education',
            'program' => 'BS in Accountancy',
            'course_code' => 'ACC201',
            'listing_type' => Item::TYPE_SELL,
            'condition' => 'like_new',
            'price' => 300,
            'status' => Item::STATUS_PENDING,
            'accepted_payment_methods' => ['maya', 'cash_on_pickup'],
        ]);

        $completedSaleItem = $this->createItem($seller, [
            'title' => 'Java Programming Book',
            'category' => 'Books',
            'description' => 'Completed sale sample with receipt and ratings already available.',
            'department' => 'Department of Computing Education',
            'program' => 'BS in Information Technology',
            'course_code' => 'IT203',
            'listing_type' => Item::TYPE_SELL,
            'condition' => 'good',
            'price' => 520,
            'status' => Item::STATUS_SOLD,
            'accepted_payment_methods' => ['gcash'],
        ]);

        $secondSellerItem = $this->createItem($secondSeller, [
            'title' => 'Tourism Presentation Clicker',
            'category' => 'Gadgets',
            'description' => 'Wireless clicker with laser pointer for class reports and defenses.',
            'department' => 'Department of Hospitality Education',
            'program' => 'BS in Tourism Management',
            'course_code' => 'TM102',
            'listing_type' => Item::TYPE_SELL,
            'condition' => 'like_new',
            'price' => 350,
            'accepted_payment_methods' => ['gcash', 'maya'],
        ]);

        $pendingTransaction = $this->createTransaction($buyer, $seller, $pendingRequestItem, [
            'status' => Transaction::STATUS_PENDING,
            'payment_method' => 'gcash',
        ]);

        $approvedTransaction = $this->createTransaction($renter, $secondSeller, $approvedRequestItem, [
            'status' => Transaction::STATUS_APPROVED,
            'payment_method' => 'maya',
            'meetup_location' => 'UM Main Library Lobby',
            'meetup_time' => now()->addDay()->setTime(15, 30),
            'payment_proof' => 'payment-proofs/demo-payment-proof-sample.pdf',
            'payment_proof_uploaded_at' => now()->subHours(2),
        ]);

        $completedTransaction = $this->createTransaction($buyer, $seller, $completedSaleItem, [
            'status' => Transaction::STATUS_COMPLETED,
            'payment_method' => 'gcash',
            'meetup_location' => 'DPT Building Entrance',
            'meetup_time' => now()->subDays(2)->setTime(10, 0),
        ]);

        $completedRentalTransaction = $this->createTransaction($renter, $seller, $availableRental, [
            'status' => Transaction::STATUS_COMPLETED,
            'payment_method' => 'cash_on_pickup',
            'rental_duration_days' => 3,
            'rental_due_date' => now()->subDay()->toDateString(),
            'meetup_location' => 'Engineering Lab 2',
            'meetup_time' => now()->subDays(5)->setTime(13, 0),
        ]);

        $this->seedConversation($buyer, $seller, $pendingRequestItem);
        $this->seedConversation($renter, $secondSeller, $approvedRequestItem);
        $this->seedRatings($completedTransaction, $buyer, $seller);
        $this->seedNotifications(
            seller: $seller,
            buyer: $buyer,
            renter: $renter,
            secondSeller: $secondSeller,
            pendingListing: $pendingListing,
            rejectedListing: $rejectedListing,
            pendingTransaction: $pendingTransaction,
            approvedTransaction: $approvedTransaction,
            completedTransaction: $completedTransaction,
            completedRentalTransaction: $completedRentalTransaction,
            availableSale: $availableSale,
            secondSellerItem: $secondSellerItem,
        );
    }

    private function seedBaseUsers(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@umindanao.edu.ph'],
            [
                'name' => 'Admin User',
                'password' => Hash::make(self::PASSWORD),
            ]
        )->forceFill(['role' => 'admin'])->save();

        User::updateOrCreate(
            ['email' => 'student@umindanao.edu.ph'],
            [
                'name' => 'Student User',
                'password' => Hash::make(self::PASSWORD),
            ]
        )->forceFill(['role' => 'student'])->save();
    }

    private function resetDemoUsers(): void
    {
        User::whereIn('email', [
            'seller@umindanao.edu.ph',
            'buyer@umindanao.edu.ph',
            'renter@umindanao.edu.ph',
            'seller2@umindanao.edu.ph',
        ])->get()->each->delete();
    }

    private function seedDemoFiles(): void
    {
        Storage::disk('local')->put(
            'payment-proofs/demo-payment-proof-sample.pdf',
            "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF\n"
        );
    }

    private function createUser(string $email, string $name): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(self::PASSWORD),
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
            'role' => 'student',
        ])->save();

        return $user;
    }

    private function createItem(User $owner, array $attributes): Item
    {
        return Item::create(array_merge([
            'user_id' => $owner->id,
            'title' => 'Demo Item',
            'category' => 'Supplies',
            'description' => 'Demo listing for UM-Pasa walkthroughs.',
            'department' => 'Department of Computing Education',
            'program' => 'BS in Information Technology',
            'course_code' => 'IT101',
            'listing_type' => Item::TYPE_SELL,
            'accepted_payment_methods' => ['gcash', 'cash_on_pickup'],
            'minimum_rental_days' => null,
            'maximum_rental_days' => null,
            'daily_rental_rate' => null,
            'rental_duration_days' => null,
            'condition' => 'good',
            'price' => 100,
            'status' => Item::STATUS_AVAILABLE,
            'moderation_status' => Item::MODERATION_APPROVED,
            'rejection_reason' => null,
        ], $attributes));
    }

    private function createTransaction(User $buyer, User $seller, Item $item, array $attributes): Transaction
    {
        return Transaction::create(array_merge([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'item_id' => $item->id,
            'status' => Transaction::STATUS_PENDING,
            'payment_method' => 'gcash',
            'other_payment_method' => null,
            'rental_duration_days' => null,
            'rental_due_date' => null,
            'payment_proof' => null,
            'payment_proof_uploaded_at' => null,
            'meetup_location' => null,
            'meetup_time' => null,
        ], $attributes));
    }

    private function seedConversation(User $starter, User $recipient, Item $item): void
    {
        $conversation = Conversation::create([
            'starter_id' => $starter->id,
            'recipient_id' => $recipient->id,
            'item_id' => $item->id,
            'last_message_at' => now()->subMinutes(15),
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $starter->id,
            'body' => "Hi, is {$item->title} still available?",
            'type' => 'text',
            'read_at' => now()->subMinutes(14),
            'meta' => ['item_title' => $item->title],
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $recipient->id,
            'body' => 'Yes, it is still available. I can meet at the university.',
            'type' => 'text',
            'read_at' => now()->subMinutes(10),
            'meta' => ['item_title' => $item->title],
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $recipient->id,
            'body' => 'Meetup proposal sent.',
            'type' => 'meetup_proposal',
            'proposal_status' => 'pending',
            'meetup_location' => 'UM Main Library Lobby',
            'meetup_time' => now()->addDay()->setTime(16, 0),
            'meta' => ['item_title' => $item->title],
        ]);
    }

    private function seedRatings(Transaction $transaction, User $buyer, User $seller): void
    {
        Rating::create([
            'reviewer_id' => $buyer->id,
            'reviewed_user_id' => $seller->id,
            'transaction_id' => $transaction->id,
            'rating' => 5,
            'comment' => 'Smooth meetup and the item matched the description.',
        ]);

        Rating::create([
            'reviewer_id' => $seller->id,
            'reviewed_user_id' => $buyer->id,
            'transaction_id' => $transaction->id,
            'rating' => 5,
            'comment' => 'Buyer arrived on time and paid as agreed.',
        ]);
    }

    private function seedNotifications(
        User $seller,
        User $buyer,
        User $renter,
        User $secondSeller,
        Item $pendingListing,
        Item $rejectedListing,
        Transaction $pendingTransaction,
        Transaction $approvedTransaction,
        Transaction $completedTransaction,
        Transaction $completedRentalTransaction,
        Item $availableSale,
        Item $secondSellerItem,
    ): void {
        $notifications = [
            [$seller, 'request', $pendingTransaction, "New request received for {$pendingTransaction->item->title}.", false],
            [$buyer, 'message', $pendingTransaction, "A seller replied about {$pendingTransaction->item->title}.", false],
            [$renter, 'approval', $approvedTransaction, "Your request for {$approvedTransaction->item->title} was approved.", false],
            [$secondSeller, 'payment_proof', $approvedTransaction, "Payment proof was uploaded for {$approvedTransaction->item->title}.", false],
            [$buyer, 'completion', $completedTransaction, "Transaction completed for {$completedTransaction->item->title}.", true],
            [$seller, 'rating', $completedTransaction, 'Marco Buyer left a new rating.', false],
            [$renter, 'rental_overdue', $completedRentalTransaction, "Rental for {$completedRentalTransaction->item->title} is overdue.", false],
            [$seller, 'item_pending', $pendingListing, "Your listing {$pendingListing->title} is waiting for admin approval.", true],
            [$seller, 'item_rejected', $rejectedListing, "Your listing {$rejectedListing->title} was rejected by admin.", false],
            [$seller, 'item_approved', $availableSale, "Your listing {$availableSale->title} is visible in the marketplace.", true],
            [$secondSeller, 'item_approved', $secondSellerItem, "Your listing {$secondSellerItem->title} is visible in the marketplace.", true],
        ];

        $notificationService = app(NotificationService::class);

        foreach ($notifications as [$user, $type, $related, $message, $isRead]) {
            $notificationService->send($user, $type, $message, $related, $isRead);
        }
    }
}
