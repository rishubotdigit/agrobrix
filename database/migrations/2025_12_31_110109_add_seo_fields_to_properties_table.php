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
            $table->string('slug')->unique()->nullable()->after('title');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
        });

        // Generate slugs for existing properties
        $properties = \DB::table('properties')->get();
        foreach ($properties as $property) {
            $slug = \Illuminate\Support\Str::slug($property->title) . '-' . $property->id;
            \DB::table('properties')->where('id', $property->id)->update(['slug' => $slug]);
        }

        // Make slug required after population (optional, but good practice if we want to enforce it)
        // Schema::table('properties', function (Blueprint $table) {
        //    $table->string('slug')->nullable(false)->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['slug', 'meta_title', 'meta_description', 'meta_keywords']);
        });
    }
};
