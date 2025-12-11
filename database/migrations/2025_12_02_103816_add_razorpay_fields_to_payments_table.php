<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('order_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('signature')->nullable();
            $table->string('type')->default('contact_view'); // e.g., contact_view, feature_unlock
            $table->json('metadata')->nullable(); // For additional data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'payment_id', 'signature', 'type', 'metadata']);
        });
    }
};
