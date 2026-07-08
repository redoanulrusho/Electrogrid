<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feeders', function (Blueprint $table) {
            $table->id();
            $table->string('feeder_name');
            $table->string('substation_code')->unique();
            $table->string('division');
            $table->string('district');
            $table->string('upazila');
            $table->decimal('max_capacity_mw', 8, 2);
            $table->decimal('current_demand_mw', 8, 2)->default(0);
            $table->boolean('is_priority_zone')->default(false);
            // MySQL real ENUM constraint for status
            $table->enum('status', ['Active', 'Outage', 'Maintenance'])->default('Active');
            // FK to admins — nullable in case admin not yet assigned
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feeders');
    }
};