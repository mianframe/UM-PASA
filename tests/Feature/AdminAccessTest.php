<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.users'))
            ->assertOk();
    }

    public function test_admin_dashboard_renders_marketplace_oversight(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Marketplace Oversight, made clear.')
            ->assertSee('Review Listings');
    }

    public function test_student_cannot_access_admin_pages(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->get(route('admin.users'))
            ->assertForbidden();
    }

    public function test_admin_can_open_student_record_and_transaction_receipt(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $buyer = User::factory()->create(['role' => 'student']);
        $seller = User::factory()->create(['role' => 'student']);
        $item = Item::create([
            'user_id' => $seller->id,
            'title' => 'Engineering Calculator',
            'category' => 'Calculators',
            'description' => 'Scientific calculator in good condition.',
            'department' => 'Department of Engineering Education',
            'program' => 'Major in Computer Engineering',
            'course_code' => 'ENG101',
            'listing_type' => 'sell',
            'condition' => 'good',
            'price' => 500,
            'status' => 'sold',
            'moderation_status' => 'approved',
        ]);
        $transaction = Transaction::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'item_id' => $item->id,
            'status' => 'completed',
            'payment_method' => 'gcash',
            'meetup_location' => 'UM Main Library',
            'meetup_time' => now()->subDay(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.record', $buyer))
            ->assertOk()
            ->assertSee('Student Record')
            ->assertSee('Engineering Calculator')
            ->assertSee('View Receipt');

        $this->actingAs($admin)
            ->get(route('transactions.show', $transaction))
            ->assertOk()
            ->assertSee('Transaction Receipt')
            ->assertDontSee('Leave a Rating');
    }
}
