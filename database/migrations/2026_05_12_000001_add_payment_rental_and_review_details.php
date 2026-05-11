<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->json('accepted_payment_methods')->nullable()->after('listing_type');
            $table->unsignedInteger('minimum_rental_days')->nullable()->after('accepted_payment_methods');
            $table->unsignedInteger('maximum_rental_days')->nullable()->after('minimum_rental_days');
            $table->decimal('daily_rental_rate', 10, 2)->nullable()->after('maximum_rental_days');
            $table->text('rejection_reason')->nullable()->after('moderation_status');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedInteger('rental_duration_days')->nullable()->after('payment_method');
            $table->date('rental_due_date')->nullable()->after('rental_duration_days');
            $table->string('payment_proof')->nullable()->after('rental_due_date');
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof');
            $table->string('other_payment_method')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'rental_duration_days',
                'rental_due_date',
                'payment_proof',
                'payment_proof_uploaded_at',
                'other_payment_method',
            ]);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'accepted_payment_methods',
                'minimum_rental_days',
                'maximum_rental_days',
                'daily_rental_rate',
                'rejection_reason',
            ]);
        });
    }
};
