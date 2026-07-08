<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outage_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feeder_id')->constrained('feeders')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('admins')->cascadeOnDelete();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['planned', 'active', 'completed'])->default('planned');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outage_schedules');
    }
};
