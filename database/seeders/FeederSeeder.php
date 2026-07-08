<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Feeder;
use Illuminate\Database\Seeder;

class FeederSeeder extends Seeder
{
    public function run(): void
    {
        // Get the default operator admin to assign as feeder owner
        $admin = Admin::where('username', 'operator_dh4')->first();
        $adminId = $admin?->id;

        $feeders = [
            [
                'feeder_name'       => 'Gulshan Industrial Trunk 1',
                'substation_code'   => 'SUB-DH-04A',
                'division'          => 'Dhaka',
                'district'          => 'Dhaka',
                'upazila'           => 'Gulshan',
                'max_capacity_mw'   => 25.00,
                'current_demand_mw' => 18.45,
                'is_priority_zone'  => true,   // Industrial — high priority
                'status'            => 'Active',
                'admin_id'          => $adminId,
            ],
            [
                'feeder_name'       => 'Banani Residential Grid',
                'substation_code'   => 'SUB-DH-04B',
                'division'          => 'Dhaka',
                'district'          => 'Dhaka',
                'upazila'           => 'Banani',
                'max_capacity_mw'   => 18.00,
                'current_demand_mw' => 0.00,
                'is_priority_zone'  => false,
                'status'            => 'Outage',
                'admin_id'          => $adminId,
            ],
            [
                'feeder_name'       => 'Tejgaon Commercial Trunk 2',
                'substation_code'   => 'SUB-DH-04C',
                'division'          => 'Dhaka',
                'district'          => 'Dhaka',
                'upazila'           => 'Tejgaon',
                'max_capacity_mw'   => 30.00,
                'current_demand_mw' => 26.10,
                'is_priority_zone'  => true,   // Commercial — priority
                'status'            => 'Active',
                'admin_id'          => $adminId,
            ],
            [
                'feeder_name'       => 'Badda Suburban Feeder',
                'substation_code'   => 'SUB-DH-04D',
                'division'          => 'Dhaka',
                'district'          => 'Dhaka',
                'upazila'           => 'Badda',
                'max_capacity_mw'   => 12.00,
                'current_demand_mw' => 0.00,
                'is_priority_zone'  => false,
                'status'            => 'Maintenance',
                'admin_id'          => $adminId,
            ],
        ];

        foreach ($feeders as $feederData) {
            Feeder::firstOrCreate(
                ['substation_code' => $feederData['substation_code']],
                $feederData
            );
        }
    }
}
