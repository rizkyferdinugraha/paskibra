<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'nama_role' => 'Pembina'],
            ['id' => 2, 'nama_role' => 'Pelatih'],
            ['id' => 3, 'nama_role' => 'Purna'],
            ['id' => 4, 'nama_role' => 'Senior'],
            ['id' => 5, 'nama_role' => 'Junior'],
            ['id' => 6, 'nama_role' => 'Calon Junior'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
