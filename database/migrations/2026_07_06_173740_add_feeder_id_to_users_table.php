<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add feeder_id FK to users table.
     * Runs AFTER feeders table is created — satisfies MySQL FK constraint ordering.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('feeder_id')->nullable()->after('consumer_class')
                  ->constrained('feeders')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['feeder_id']);
            $table->dropColumn('feeder_id');
        });
    }
};
