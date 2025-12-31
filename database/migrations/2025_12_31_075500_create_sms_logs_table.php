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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('mobile');
            $table->text('message')->nullable();
            $table->string('template_slug')->nullable();
            $table->string('gateway'); // msg91, 2factor, twilio
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->json('response')->nullable(); // API response
            $table->text('error')->nullable(); // Error message if failed
            $table->string('type')->default('otp'); // otp, notification, test, etc.
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('mobile');
            $table->index('status');
            $table->index('gateway');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
