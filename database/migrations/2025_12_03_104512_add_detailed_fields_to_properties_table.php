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
            // Basic Information
            $table->enum('land_type', ['Agriculture', 'Residential Plot', 'Commercial Plot'])->nullable()->after('title');

            // Location Details
            $table->string('state')->nullable()->after('land_type');
            $table->string('city')->nullable()->after('state');
            $table->string('area')->nullable()->after('city');
            $table->string('full_address')->nullable()->after('area');
            $table->decimal('google_map_lat', 10, 8)->nullable()->after('full_address');
            $table->decimal('google_map_lng', 11, 8)->nullable()->after('google_map_lat');

            // Land Details
            $table->decimal('plot_area', 10, 2)->nullable()->after('google_map_lng');
            $table->enum('plot_area_unit', ['sq ft', 'sq yd', 'acre'])->nullable()->after('plot_area');
            $table->decimal('frontage', 8, 2)->nullable()->after('plot_area_unit');
            $table->decimal('depth', 8, 2)->nullable()->after('frontage');
            $table->decimal('road_width', 8, 2)->nullable()->after('depth');
            $table->boolean('corner_plot')->nullable()->after('road_width');
            $table->boolean('gated_community')->nullable()->after('corner_plot');
            $table->enum('ownership_type', ['Freehold', 'Leasehold'])->nullable()->after('gated_community');

            // Price Details
            $table->boolean('price_negotiable')->default(false)->after('price');

            // Contact Info
            $table->string('contact_name')->nullable()->after('price_negotiable');
            $table->string('contact_mobile')->nullable()->after('contact_name');
            $table->enum('contact_role', ['Owner', 'Agent'])->nullable()->after('contact_mobile');

            // Media
            $table->json('property_images')->nullable()->after('contact_role');
            $table->string('property_video')->nullable()->after('property_images');

            // Status
            $table->string('status')->default('For Sale')->after('property_video');

            // Remove old location field
            $table->dropColumn('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'land_type', 'state', 'city', 'area', 'full_address', 'google_map_lat', 'google_map_lng',
                'plot_area', 'plot_area_unit', 'frontage', 'depth', 'road_width', 'corner_plot', 'gated_community', 'ownership_type',
                'price_negotiable', 'contact_name', 'contact_mobile', 'contact_role', 'property_images', 'property_video', 'status'
            ]);
            $table->string('location')->after('price');
        });
    }
};
