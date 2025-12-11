<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plan_purchases', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('plan_purchases', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'purchased', 'activated', 'expired'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_purchases', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('plan_purchases', function (Blueprint $table) {
            $table->enum('status', ['purchased', 'activated', 'expired'])->default('purchased');
        });
    }
};
