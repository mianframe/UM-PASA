<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Item;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MarketplaceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_price_cannot_be_negative(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('items.store'), [
            'title' => 'Calculus Notes',
            'category' => 'Books',
            'description' => 'Clean annotated notes for review.',
            'department' => 'Department of Computing Education',
            'program' => 'BS in Information Technology',
            'course_code' => 'MATH101',
            'listing_type' => 'sell',
            'accepted_payment_methods' => ['gcash'],
            'condition' => 'good',
            'price' => -50,
        ]);

        $response->assertSessionHasErrors('price');
        $this->assertDatabaseCount('items', 0);
    }

    public function test_item_conversation_must_target_the_actual_seller(): void
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = $this->createItemFor($seller);

        $response = $this->actingAs($buyer)->post(route('messages.store'), [
            'recipient_id' => $otherUser->id,
            'item_id' => $item->id,
            'body' => 'Is this still available?',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('conversations', 0);
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_meetup_proposal_can_only_be_actioned_once(): void
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = $this->createItemFor($seller);

        $conversation = Conversation::create([
            'starter_id' => $buyer->id,
            'recipient_id' => $seller->id,
            'item_id' => $item->id,
            'last_message_at' => now(),
        ]);

        $proposal = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $buyer->id,
            'body' => 'Can we meet after class?',
            'type' => 'meetup_proposal',
            'proposal_status' => 'pending',
            'meetup_location' => 'UM Library Lobby',
            'meetup_time' => now()->addDay(),
        ]);

        $this->actingAs($seller)
            ->post(route('messages.proposals.accept', $proposal))
            ->assertSessionHasNoErrors();

        $this->actingAs($seller)
            ->post(route('messages.proposals.decline', $proposal))
            ->assertForbidden();

        $this->assertSame('accepted', $proposal->refresh()->proposal_status);
        $this->assertSame(1, Message::where('type', 'system')->count());
    }

    public function test_transaction_can_move_from_pending_to_approved_to_completed(): void
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = $this->createItemFor($seller);

        $this->actingAs($buyer)
            ->post(route('transactions.store', $item), [
                'payment_method' => 'gcash',
            ])
            ->assertRedirect(route('transactions.history'));

        $transaction = Transaction::firstOrFail();

        $this->assertSame('pending', $transaction->status);
        $this->assertSame('gcash', $transaction->payment_method);
        $this->assertSame('pending', $item->refresh()->status);

        $meetupTime = now()->addDay()->setSecond(0);

        $this->actingAs($seller)
            ->post(route('transactions.approve', $transaction), [
                'meetup_location' => 'UM Main Library Lobby',
                'meetup_time' => $meetupTime->format('Y-m-d\TH:i'),
            ])
            ->assertSessionHasNoErrors();

        $transaction->refresh();

        $this->assertSame('approved', $transaction->status);
        $this->assertSame('UM Main Library Lobby', $transaction->meetup_location);
        $this->assertSame('pending', $item->refresh()->status);

        $this->actingAs($seller)
            ->post(route('transactions.complete', $transaction))
            ->assertRedirect(route('transactions.show', $transaction));

        $this->assertSame('completed', $transaction->refresh()->status);
        $this->assertSame('sold', $item->refresh()->status);
    }

    public function test_seller_can_message_transaction_buyer_about_requested_item(): void
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = $this->createItemFor($seller);

        Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'item_id' => $item->id,
            'status' => 'pending',
        ]);

        $this->actingAs($seller)
            ->post(route('messages.store'), [
                'recipient_id' => $buyer->id,
                'item_id' => $item->id,
                'body' => 'I saw your request. Are you free after class?',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('conversations', [
            'starter_id' => $seller->id,
            'recipient_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $this->assertDatabaseHas('messages', [
            'user_id' => $seller->id,
            'body' => 'I saw your request. Are you free after class?',
        ]);
    }

    public function test_admin_item_deletion_removes_uploaded_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create();

        Storage::disk('public')->put('items/sample.jpg', 'sample');
        $item = $this->createItemFor($seller, ['image' => 'items/sample.jpg']);

        $this->actingAs($admin)
            ->delete(route('admin.items.destroy', $item))
            ->assertRedirect();

        Storage::disk('public')->assertMissing('items/sample.jpg');
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function test_pending_listing_is_hidden_from_guests_but_visible_to_owner(): void
    {
        $seller = User::factory()->create();
        $item = $this->createItemFor($seller, [
            'moderation_status' => 'pending',
        ]);

        $this->get(route('marketplace.show', $item))->assertForbidden();

        $this->actingAs($seller)
            ->get(route('marketplace.show', $item))
            ->assertOk()
            ->assertSee($item->title);
    }

    public function test_rental_due_date_is_based_on_approved_meetup_and_item_reopens_after_completion(): void
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = $this->createItemFor($seller, [
            'listing_type' => 'rent',
            'price' => 40,
            'daily_rental_rate' => 40,
            'minimum_rental_days' => 2,
            'maximum_rental_days' => 7,
            'rental_duration_days' => 7,
        ]);

        $this->actingAs($buyer)
            ->post(route('transactions.store', $item), [
                'payment_method' => 'gcash',
                'rental_duration_days' => 3,
            ])
            ->assertRedirect(route('transactions.history'));

        $transaction = Transaction::firstOrFail();
        $this->assertNull($transaction->rental_due_date);

        $meetupTime = now()->addDay()->setSecond(0);

        $this->actingAs($seller)
            ->post(route('transactions.approve', $transaction), [
                'meetup_location' => 'UM Student Center',
                'meetup_time' => $meetupTime->format('Y-m-d\TH:i'),
            ])
            ->assertSessionHasNoErrors();

        $transaction->refresh();

        $this->assertSame($meetupTime->copy()->addDays(3)->toDateString(), $transaction->rental_due_date->toDateString());

        $this->actingAs($seller)
            ->post(route('transactions.complete', $transaction))
            ->assertRedirect(route('transactions.show', $transaction));

        $this->assertSame('completed', $transaction->refresh()->status);
        $this->assertSame('available', $item->refresh()->status);
    }

    public function test_stale_pending_requests_command_rejects_request_and_reopens_item(): void
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = $this->createItemFor($seller, [
            'status' => 'pending',
        ]);

        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'item_id' => $item->id,
            'status' => 'pending',
            'payment_method' => 'gcash',
        ]);
        $transaction->forceFill([
            'created_at' => now()->subDays(8),
            'updated_at' => now()->subDays(8),
        ])->save();

        Artisan::call('um-pasa:expire-stale-requests', ['--days' => 7]);

        $this->assertSame('rejected', $transaction->refresh()->status);
        $this->assertSame('available', $item->refresh()->status);
        $this->assertSame(2, Notification::where('type', 'request_expired')->count());
    }

    private function createItemFor(User $user, array $overrides = []): Item
    {
        return Item::create(array_merge([
            'user_id' => $user->id,
            'title' => 'Engineering Calculator',
            'category' => 'Electronics',
            'description' => 'Scientific calculator in good condition.',
            'department' => 'College of Engineering',
            'program' => 'BS Civil Engineering',
            'course_code' => 'ENG101',
            'listing_type' => 'sell',
            'condition' => 'good',
            'price' => 500,
            'status' => 'available',
            'moderation_status' => 'approved',
        ], $overrides));
    }
}
