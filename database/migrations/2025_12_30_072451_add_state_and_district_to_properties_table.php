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
            if (!Schema::hasColumn('properties', 'state')) {
                $table->string('state')->nullable()->after('land_type');
            }
            if (!Schema::hasColumn('properties', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('state');
                $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'district_id')) {
                try {
                    $table->dropForeign(['district_id']);
                } catch (\Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                $table->dropColumn('district_id');
            }
            if (Schema::hasColumn('properties', 'state')) {
                $table->dropColumn('state');
            }
        });
    }
};
