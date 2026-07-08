<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bills table for simulated electricity billing.
     *
     * Rate formula (documented):
     *   Residential : BDT 6.00 / unit (kWh)
     *   Commercial  : BDT 9.50 / unit
     *   Industrial  : Tiered —
     *       0–500 kWh   : BDT 9.00 / unit
     *       500–1000 kWh: BDT 10.50 / unit
     *       >1000 kWh   : BDT 12.00 / unit
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('month');                         // e.g. 2026-06-01 = June 2026
            $table->decimal('units_consumed', 10, 2);      // kWh simulated
            $table->decimal('rate_applied', 8, 2);         // BDT per unit
            $table->decimal('amount', 10, 2);              // total = units × rate
            $table->enum('paid_status', ['paid', 'unpaid'])->default('unpaid');
            $table->date('due_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
