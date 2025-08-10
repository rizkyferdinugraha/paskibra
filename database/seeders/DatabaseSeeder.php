<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and jurusans first
        $this->call([
            RoleSeeder::class,
            JurusanSeeder::class,
        ]);

        // User::factory(10)->create();

        // Create Super Admin
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@paskibra.com',
            'super_admin' => true,
            'is_admin' => true,
            'role_id' => 1, // Super Admin Role
        ]);

        // Create biodata for super admin
        $this->call([
            SuperAdminBiodataSeeder::class,
        ]);
    }
}
