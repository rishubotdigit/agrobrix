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
        if (!Schema::hasTable('follow_ups')) {
            Schema::create('follow_ups', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id');
                $table->datetime('follow_up_date');
                $table->text('notes')->nullable();
                $table->enum('status', ['pending', 'completed'])->default('pending');
                $table->timestamps();
                
                $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
