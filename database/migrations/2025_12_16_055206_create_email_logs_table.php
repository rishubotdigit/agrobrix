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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email_type'); // e.g., welcome_user, notify_admin_new_user
            $table->string('recipient_email');
            $table->unsignedBigInteger('user_id')->nullable(); // the user related to the email
            $table->string('status'); // sent, failed
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
