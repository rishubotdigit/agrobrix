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
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Template name e.g., "OTP Verification"
            $table->string('slug')->unique(); // Unique identifier e.g., "otp", "registration"
            $table->string('template_id'); // MSG91/2Factor template ID
            $table->string('gateway')->default('msg91'); // Gateway: msg91, 2factor, twilio
            $table->text('description')->nullable(); // Optional description
            $table->json('variables')->nullable(); // Template variables e.g., ["var1", "var2"]
            $table->boolean('is_active')->default(true); // Enable/disable template
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_templates');
    }
};
