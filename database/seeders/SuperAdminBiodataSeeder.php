<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminBiodataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari super admin user
        $superAdmin = \App\Models\User::where('email', 'superadmin@paskibra.com')->first();
        
        if ($superAdmin && !$superAdmin->biodata) {
            // Buat biodata untuk super admin
            \App\Models\Biodata::create([
                'user_id' => $superAdmin->id,
                'no_kta' => '2024' . str_pad($superAdmin->id, 4, '0', STR_PAD_LEFT),
                'pas_foto_url' => 'pas_foto/default-admin.jpg', // Foto default
                'nama_lengkap' => 'Super Admin Paskibra',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'no_telepon' => '081234567890',
                'alamat' => 'Alamat Super Admin',
                'jurusan_id' => 1, // Teknik Komputer dan Jaringan
                'tahun_angkatan' => 2020,
                'is_active' => true, // Super admin langsung aktif
                'riwayat_penyakit' => 'Tidak ada',
            ]);
            
            echo "Biodata Super Admin berhasil dibuat.\n";
        } else {
            echo "Super Admin sudah memiliki biodata atau user tidak ditemukan.\n";
        }
    }
}
