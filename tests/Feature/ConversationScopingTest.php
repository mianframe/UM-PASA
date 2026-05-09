<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_scopes_conversations_to_specific_items(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $item1 = Item::create([
            'user_id' => $user2->id,
            'title' => 'Item 1',
            'category' => 'Books',
            'description' => 'Description 1',
            'department' => 'CAS',
            'program' => 'BSM',
            'course_code' => 'M101',
            'listing_type' => 'sell',
            'condition' => 'good',
            'price' => 100,
            'status' => 'available',
        ]);

        $item2 = Item::create([
            'user_id' => $user2->id,
            'title' => 'Item 2',
            'category' => 'Books',
            'description' => 'Description 2',
            'department' => 'CAS',
            'program' => 'BSM',
            'course_code' => 'M101',
            'listing_type' => 'sell',
            'condition' => 'good',
            'price' => 200,
            'status' => 'available',
        ]);

        // User 1 sends message to User 2 about Item 1
        $this->actingAs($user1)->post(route('messages.store'), [
            'recipient_id' => $user2->id,
            'item_id' => $item1->id,
            'body' => 'Message 1',
        ]);

        $this->assertDatabaseCount('conversations', 1);
        $conv1 = Conversation::first();
        $this->assertEquals($item1->id, $conv1->item_id);

        // User 1 sends message to User 2 about Item 2
        // This should create a NEW conversation because item_id is different
        $this->actingAs($user1)->post(route('messages.store'), [
            'recipient_id' => $user2->id,
            'item_id' => $item2->id,
            'body' => 'Message 2',
        ]);

        $this->assertDatabaseCount('conversations', 2);
    }

    public function test_it_finds_existing_conversation_in_reverse_direction_with_item_id(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $item = Item::create([
            'user_id' => $user2->id,
            'title' => 'Item 1',
            'category' => 'Books',
            'description' => 'Description 1',
            'department' => 'CAS',
            'program' => 'BSM',
            'course_code' => 'M101',
            'listing_type' => 'sell',
            'condition' => 'good',
            'price' => 100,
            'status' => 'available',
        ]);

        // User 1 starts conversation with User 2
        $conv = Conversation::create([
            'starter_id' => $user1->id,
            'recipient_id' => $user2->id,
            'item_id' => $item->id,
            'last_message_at' => now(),
        ]);

        // User 2 replies to User 1 about the same item via the message store route
        // This simulates User 2 initiating back if they didn't use the existing conv id
        $this->actingAs($user2)->post(route('messages.store'), [
            'recipient_id' => $user1->id,
            'item_id' => $item->id,
            'body' => 'Reply',
        ]);

        // Should NOT create a new conversation
        $this->assertDatabaseCount('conversations', 1);
    }
}
