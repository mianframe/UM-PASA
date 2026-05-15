<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->index(['status', 'moderation_status', 'created_at'], 'items_marketplace_status_idx');
            $table->index(['category', 'listing_type'], 'items_category_type_idx');
            $table->index(['department', 'program'], 'items_department_program_idx');
            $table->index(['condition', 'price'], 'items_condition_price_idx');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['buyer_id', 'status', 'created_at'], 'transactions_buyer_status_idx');
            $table->index(['seller_id', 'status', 'created_at'], 'transactions_seller_status_idx');
            $table->index(['item_id', 'status'], 'transactions_item_status_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'is_read', 'created_at'], 'notifications_user_read_idx');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->index(['starter_id', 'recipient_id', 'item_id'], 'conversations_participants_idx');
            $table->index(['last_message_at', 'updated_at'], 'conversations_activity_idx');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index(['conversation_id', 'created_at'], 'messages_conversation_created_idx');
            $table->index(['conversation_id', 'read_at', 'user_id'], 'messages_conversation_read_idx');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_conversation_read_idx');
            $table->dropIndex('messages_conversation_created_idx');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex('conversations_activity_idx');
            $table->dropIndex('conversations_participants_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_read_idx');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('transactions_item_status_idx');
            $table->dropIndex('transactions_seller_status_idx');
            $table->dropIndex('transactions_buyer_status_idx');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex('items_condition_price_idx');
            $table->dropIndex('items_department_program_idx');
            $table->dropIndex('items_category_type_idx');
            $table->dropIndex('items_marketplace_status_idx');
        });
    }
};
