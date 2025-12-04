<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default company
        DB::table('companies')->insert([
            'name' => 'Super Company',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create an admin user
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => now(),
            'company_id' => 1,
            'role' => 'super-admin',
            'password' => Hash::make('super123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
    }
}
