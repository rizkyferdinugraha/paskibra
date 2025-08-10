<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testMembers = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@student.com',
                'nama_lengkap' => 'Ahmad Rizki Pratama',
                'tanggal_lahir' => '2005-03-15',
                'jenis_kelamin' => 'Laki-laki',
                'no_telepon' => '081234567891',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'jurusan_id' => 1, // TKJ
                'tahun_angkatan' => 2023,
                'riwayat_penyakit' => 'Tidak ada',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.com',
                'nama_lengkap' => 'Siti Nurhaliza Putri',
                'tanggal_lahir' => '2005-07-22',
                'jenis_kelamin' => 'Perempuan',
                'no_telepon' => '081234567892',
                'alamat' => 'Jl. Sudirman No. 456, Bandung',
                'jurusan_id' => 5, // RPL
                'tahun_angkatan' => 2023,
                'riwayat_penyakit' => 'Asma ringan',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.com',
                'nama_lengkap' => 'Budi Santoso',
                'tanggal_lahir' => '2004-11-08',
                'jenis_kelamin' => 'Laki-laki',
                'no_telepon' => '081234567893',
                'alamat' => 'Jl. Gatot Subroto No. 789, Surabaya',
                'jurusan_id' => 4, // TSM
                'tahun_angkatan' => 2022,
                'riwayat_penyakit' => 'Tidak ada',
            ],
        ];

        foreach ($testMembers as $member) {
            // Create user
            $user = \App\Models\User::create([
                'name' => $member['name'],
                'email' => $member['email'],
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'super_admin' => false,
                'is_admin' => false,
                'role_id' => 6, // Anggota
            ]);

            // Create biodata (pending approval)
            \App\Models\Biodata::create([
                'user_id' => $user->id,
                'no_kta' => $member['tahun_angkatan'] . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'pas_foto_url' => 'pas_foto/default-admin.jpg', // Using default photo
                'nama_lengkap' => $member['nama_lengkap'],
                'tanggal_lahir' => $member['tanggal_lahir'],
                'jenis_kelamin' => $member['jenis_kelamin'],
                'no_telepon' => $member['no_telepon'],
                'alamat' => $member['alamat'],
                'jurusan_id' => $member['jurusan_id'],
                'tahun_angkatan' => $member['tahun_angkatan'],
                'is_active' => false, // Pending approval
                'riwayat_penyakit' => $member['riwayat_penyakit'],
            ]);
        }

        echo "Test members created successfully.\n";
    }
}
