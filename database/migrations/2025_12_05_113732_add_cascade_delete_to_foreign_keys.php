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
        // Properties table
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->dropForeign(['agent_id']);
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Leads table
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
            $table->dropForeign(['property_id']);
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });

        // Payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dropForeign(['property_id']);
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });

        // Property versions table
        Schema::table('property_versions', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Properties table
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->foreign('owner_id')->references('id')->on('users');
            $table->dropForeign(['agent_id']);
            $table->foreign('agent_id')->references('id')->on('users');
        });

        // Leads table
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->foreign('agent_id')->references('id')->on('users');
            $table->dropForeign(['property_id']);
            $table->foreign('property_id')->references('id')->on('properties');
        });

        // Payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->dropForeign(['property_id']);
            $table->foreign('property_id')->references('id')->on('properties');
        });

        // Property versions table
        Schema::table('property_versions', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }
};
