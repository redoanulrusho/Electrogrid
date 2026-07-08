<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Default Grid Operator — password is hashed with Hash::make (bcrypt)
        Admin::firstOrCreate(
            ['username' => 'operator_dh4'],
            [
                'email'         => 'operator@electrogrid.bd',
                'password'      => Hash::make('admin1234'),  // bcrypt hash — NOT plaintext
                'designation'   => 'Senior Grid Operator',
                'assigned_zone' => 'Dhaka Substation 4',
                'role'          => 'admin',
            ]
        );

        // Secondary superadmin
        Admin::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'email'         => 'superadmin@electrogrid.bd',
                'password'      => Hash::make('super@1234'),
                'designation'   => 'Grid Operations Director',
                'assigned_zone' => 'National Control Center',
                'role'          => 'superadmin',
            ]
        );
    }
}
