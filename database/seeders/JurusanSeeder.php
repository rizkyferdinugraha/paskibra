<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusans = [
            ['id' => 1, 'nama_jurusan' => 'Teknik Komputer dan Jaringan'],
            ['id' => 2, 'nama_jurusan' => 'Management Perkantoran'],
            ['id' => 3, 'nama_jurusan' => 'Accounting'],
            ['id' => 4, 'nama_jurusan' => 'Teknik Sepeda Motor'],
            ['id' => 5, 'nama_jurusan' => 'Rekayasa Perangkat Lunak'],
            ['id' => 6, 'nama_jurusan' => 'Teknik Kontruksi Baja'],
            ['id' => 7, 'nama_jurusan' => 'Teknik Mesin'],
        ];

        foreach ($jurusans as $jurusan) {
            \App\Models\Jurusan::create($jurusan);
        }
    }
}
