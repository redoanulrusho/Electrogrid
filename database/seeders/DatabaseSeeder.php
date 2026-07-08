<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order is critical for MySQL FK constraints:
     *   1. AdminSeeder    — admins table (no deps)
     *   2. FeederSeeder   — feeders.admin_id → admins.id
     *   3. BillSeeder     — creates demo user (users.feeder_id → feeders.id) + bills
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,   // 1st — standalone
            FeederSeeder::class,  // 2nd — needs admin_id
            BillSeeder::class,    // 3rd — needs feeder_id (creates demo user + bills)
        ]);
    }
}
