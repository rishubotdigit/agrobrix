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
        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('offer_price', 10, 2)->nullable();
            $table->decimal('discount', 5, 2)->nullable();
            $table->integer('contacts_to_unlock')->nullable();
            $table->integer('validity_days')->nullable();
            $table->json('features')->nullable();
            $table->string('persona')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['original_price', 'offer_price', 'discount', 'contacts_to_unlock', 'validity_days', 'features', 'persona', 'status']);
        });
    }
};
