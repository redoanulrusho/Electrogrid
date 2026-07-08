<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BillSeeder extends Seeder
{
    /**
     * Generate a demo consumer and 6 months of simulated bills.
     *
     * Rate formula (same as Bill::calculateAmount):
     *   Residential: BDT 6.00/unit
     *   Commercial:  BDT 9.50/unit
     *   Industrial:  Tiered — 0-500: 9.00 | 500-1000: 10.50 | >1000: 12.00
     *
     * Units are randomised per class:
     *   Residential: 150-400 kWh/month
     *   Commercial:  400-900 kWh/month
     *   Industrial:  800-2000 kWh/month
     */
    public function run(): void
    {
        // Create a demo consumer (only if not already existing)
        $consumer = User::firstOrCreate(
            ['email' => 'consumer@electrogrid.bd'],
            [
                'name'           => 'Demo Consumer',
                'username'       => 'demo_consumer',
                'phone'          => '+8801700000001',
                'meter_number'   => 'MTR-DEMO-001',
                'consumer_class' => 'Residential',
                'feeder_id'      => \App\Models\Feeder::where('substation_code', 'SUB-DH-04A')->value('id'),
                'division'       => 'Dhaka',
                'district'       => 'Dhaka',
                'upazila'        => 'Gulshan',
                'area'           => 'Road 11, Gulshan 2',
                'password'       => Hash::make('consumer1234'),
            ]
        );

        // Generate 6 months of bills for demo consumer (last 6 months)
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->startOfMonth()->subMonths($i);

            // Skip if bill already exists for this month
            if (Bill::where('user_id', $consumer->id)->whereDate('month', $month)->exists()) {
                continue;
            }

            // Simulate units based on consumer class
            $units = match ($consumer->consumer_class) {
                'Residential' => rand(150, 400),
                'Commercial'  => rand(400, 900),
                'Industrial'  => rand(800, 2000),
                default       => rand(150, 400),
            };

            ['rate' => $rate, 'amount' => $amount] = Bill::calculateAmount($units, $consumer->consumer_class);

            Bill::create([
                'user_id'        => $consumer->id,
                'month'          => $month->toDateString(),
                'units_consumed' => $units,
                'rate_applied'   => $rate,
                'amount'         => $amount,
                'paid_status'    => $i > 0 ? 'paid' : 'unpaid', // Latest month = unpaid
                'due_date'       => $month->copy()->addMonth()->startOfMonth()->addDays(14)->toDateString(),
            ]);
        }
    }
}
