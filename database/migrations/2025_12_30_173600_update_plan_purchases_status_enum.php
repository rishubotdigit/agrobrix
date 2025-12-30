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
        try {
            DB::statement("ALTER TABLE plan_purchases MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'purchased', 'activated', 'expired', 'deactivated') DEFAULT 'pending'");
        } catch (\Exception $e) {
            if (DB::connection()->getDriverName() === 'sqlite') {
                // SQLite does not support MODIFY COLUMN or ENUMs natively in this way.
                // We ignore this error for local SQLite testing, assuming the target environment is MySQL.
                return;
            }
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE plan_purchases MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'purchased', 'activated', 'expired') DEFAULT 'pending'");
        } catch (\Exception $e) {
             if (DB::connection()->getDriverName() === 'sqlite') {
                return;
            }
        }
    }
};
