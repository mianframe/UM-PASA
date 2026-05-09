<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Item;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'department' => 'College of Arts and Sciences',
            'program' => 'BS Mathematics',
            'course_code' => 'MATH101',
            'listing_type' => 'sell',
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
        ], $overrides));
    }
}
