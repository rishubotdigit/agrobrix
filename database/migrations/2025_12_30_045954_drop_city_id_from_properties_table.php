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
        Schema::table('properties', function (Blueprint $table) {
            // Check if column exists before trying to drop it
            if (Schema::hasColumn('properties', 'city_id')) {
                // Try to drop foreign key if it exists
                try {
                    $table->dropForeign(['city_id']);
                } catch (\Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                $table->dropColumn('city_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->nullable()->after('land_type');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }
};
