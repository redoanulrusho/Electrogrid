<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add nullable latitude and longitude columns to feeders table.
     * Used by Mapbox GL JS map for marker placement.
     */
    public function up(): void
    {
        Schema::table('feeders', function (Blueprint $table) {
            if (!Schema::hasColumn('feeders', 'latitude')) {
                $table->decimal('latitude',  10, 7)->nullable()->after('admin_id');
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('feeders', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
