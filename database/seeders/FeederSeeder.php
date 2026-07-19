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
            // ── Dhaka Feeders (existing, updated with coordinates) ─────────────
            [
                'feeder_name'       => 'Gulshan Industrial Trunk 1',
                'substation_code'   => 'SUB-DH-04A',
                'division'          => 'Dhaka',
                'district'          => 'Dhaka',
                'upazila'           => 'Gulshan',
                'max_capacity_mw'   => 25.00,
                'current_demand_mw' => 18.45,
                'is_priority_zone'  => true,
                'status'            => 'Active',
                'admin_id'          => $adminId,
                'latitude'          => 23.7937,
                'longitude'         => 90.4144,
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
                'latitude'          => 23.7934,
                'longitude'         => 90.4002,
            ],
            [
                'feeder_name'       => 'Tejgaon Commercial Trunk 2',
                'substation_code'   => 'SUB-DH-04C',
                'division'          => 'Dhaka',
                'district'          => 'Dhaka',
                'upazila'           => 'Tejgaon',
                'max_capacity_mw'   => 30.00,
                'current_demand_mw' => 26.10,
                'is_priority_zone'  => true,
                'status'            => 'Active',
                'admin_id'          => $adminId,
                'latitude'          => 23.7698,
                'longitude'         => 90.3893,
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
                'latitude'          => 23.7736,
                'longitude'         => 90.4279,
            ],

            // ── Khulna Feeders (new — real area coordinates for Mapbox demo) ──
            [
                'feeder_name'       => 'Khulna City Core Feeder',
                'substation_code'   => 'SUB-KH-01A',
                'division'          => 'Khulna',
                'district'          => 'Khulna',
                'upazila'           => 'Khulna Sadar',
                'max_capacity_mw'   => 22.00,
                'current_demand_mw' => 16.80,
                'is_priority_zone'  => true,
                'status'            => 'Active',
                'admin_id'          => $adminId,
                'latitude'          => 22.8456,
                'longitude'         => 89.5403,
            ],
            [
                'feeder_name'       => 'Sonadanga Grid Hub',
                'substation_code'   => 'SUB-KH-01B',
                'division'          => 'Khulna',
                'district'          => 'Khulna',
                'upazila'           => 'Sonadanga',
                'max_capacity_mw'   => 15.00,
                'current_demand_mw' => 12.40,
                'is_priority_zone'  => false,
                'status'            => 'Active',
                'admin_id'          => $adminId,
                'latitude'          => 22.8350,
                'longitude'         => 89.5603,
            ],
            [
                'feeder_name'       => 'Daulatpur Industrial Feeder',
                'substation_code'   => 'SUB-KH-01C',
                'division'          => 'Khulna',
                'district'          => 'Khulna',
                'upazila'           => 'Daulatpur',
                'max_capacity_mw'   => 28.00,
                'current_demand_mw' => 0.00,
                'is_priority_zone'  => true,
                'status'            => 'Outage',
                'admin_id'          => $adminId,
                'latitude'          => 22.8762,
                'longitude'         => 89.5273,
            ],
            [
                'feeder_name'       => 'Khalishpur Distribution Node',
                'substation_code'   => 'SUB-KH-01D',
                'division'          => 'Khulna',
                'district'          => 'Khulna',
                'upazila'           => 'Khalishpur',
                'max_capacity_mw'   => 10.00,
                'current_demand_mw' => 0.00,
                'is_priority_zone'  => false,
                'status'            => 'Maintenance',
                'admin_id'          => $adminId,
                'latitude'          => 22.8200,
                'longitude'         => 89.5500,
            ],
        ];

        foreach ($feeders as $feederData) {
            Feeder::updateOrCreate(
                ['substation_code' => $feederData['substation_code']],
                $feederData
            );
        }
    }
}
