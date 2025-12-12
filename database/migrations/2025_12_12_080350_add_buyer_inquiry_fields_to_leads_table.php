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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('buying_purpose')->nullable();
            $table->enum('buying_timeline', ['3 months', '6 months', 'More than 6 months'])->nullable();
            $table->boolean('interested_in_site_visit')->default(false);
            $table->text('additional_message')->nullable();
            $table->enum('buyer_type', ['agent', 'buyer'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['buying_purpose', 'buying_timeline', 'interested_in_site_visit', 'additional_message', 'buyer_type']);
        });
    }
};
