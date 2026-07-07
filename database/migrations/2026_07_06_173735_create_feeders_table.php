<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feeders', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('feeder_name');
            $table->string('substation_code');
            $table->string('division');
            $table->string('district');
            $table->string('upazila');
            $table->decimal('max_capacity_mw', 8, 2);
            $table->decimal('current_demand_mw', 8, 2);
            $table->boolean('is_priority_zone')->default(false);
            $table->string('status')->default('Active'); // Active, Maintenance, Outage
            
            // Foreign Key Constraint pointing to admins table
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feeders');
    }
};