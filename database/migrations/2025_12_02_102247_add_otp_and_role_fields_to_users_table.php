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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->nullable()->after('email');
            $table->string('otp_code')->nullable()->after('mobile');
            $table->timestamp('otp_expiry')->nullable()->after('otp_code');
            $table->timestamp('verified_at')->nullable()->after('otp_expiry');
            $table->enum('role', ['admin', 'owner', 'agent', 'buyer'])->default('buyer')->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'otp_code', 'otp_expiry', 'verified_at', 'role']);
        });
    }
};
