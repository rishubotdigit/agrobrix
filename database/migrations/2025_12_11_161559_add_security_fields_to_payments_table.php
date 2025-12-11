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
            $table->timestamp('transaction_submitted_at')->nullable()->after('transaction_id');
            $table->string('transaction_submitted_ip')->nullable()->after('transaction_submitted_at');
            $table->text('transaction_submitted_user_agent')->nullable()->after('transaction_submitted_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['transaction_submitted_at', 'transaction_submitted_ip', 'transaction_submitted_user_agent']);
        });
    }
};
