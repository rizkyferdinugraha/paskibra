<?php

namespace Database\Seeders;

use App\Models\Absen;
use App\Models\Acara;
use App\Models\AcaraAbsen;
use App\Models\AcaraPenilaian;
use App\Models\AcaraPhoto;
use App\Models\Biodata;
use App\Models\Complaint;
use App\Models\Jurusan;
use App\Models\KasTransaksi;
use App\Models\MemberStatusLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('id_ID');

        // Ensure there are at least a couple admin users in addition to Super Admin
        $existingSuperAdmin = User::where('email', 'superadmin@paskibra.com')->first();

        $admins = collect();
        if ($existingSuperAdmin) {
            $admins->push($existingSuperAdmin);
        }

        // Create 2 admin users if not exist
        for ($i = 1; $i <= 2; $i++) {
            $email = "admin{$i}@paskibra.com";
            $admin = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Admin ' . $i,
                    'password' => Hash::make('password'),
                    'is_admin' => true,
                    'super_admin' => false,
                    'role_id' => 2, // Pelatih as admin
                ]
            );
            $admins->push($admin);
        }

        // Create 30 member users
        $memberUsers = collect();
        for ($i = 0; $i < 30; $i++) {
            $memberUsers->push(User::factory()->create([
                'is_admin' => false,
                'super_admin' => false,
                'role_id' => Arr::random([5, 6, 4, 3]), // Junior/Calon Junior/Senior/Purna
            ]));
        }

        // Ensure jurusan data exists
        if (Jurusan::count() === 0) {
            $this->call(JurusanSeeder::class);
        }
        $jurusanIds = Jurusan::query()->pluck('id')->all();

        // Create biodata for all users without biodata
        $usersNeedingBiodata = User::doesntHave('biodata')->get();
        foreach ($usersNeedingBiodata as $user) {
            Biodata::create([
                'user_id' => $user->id,
                'no_kta' => (int) (now()->year . str_pad((string)$user->id, 4, '0', STR_PAD_LEFT)),
                'pas_foto_url' => 'pas_foto/default-admin.jpg',
                'nama_lengkap' => $user->name,
                'tanggal_lahir' => $faker->dateTimeBetween('-22 years', '-16 years')->format('Y-m-d'),
                'jenis_kelamin' => Arr::random(['Laki-laki', 'Perempuan']),
                'no_telepon' => '08' . $faker->numerify('##########'),
                'alamat' => $faker->address(),
                'jurusan_id' => Arr::random($jurusanIds),
                'tahun_angkatan' => (int) $faker->numberBetween(2020, 2025),
                'is_active' => $user->is_admin || $user->super_admin ? true : (bool) $faker->boolean(80),
                'riwayat_penyakit' => Arr::random(['Tidak ada', 'Asma ringan', 'Alergi debu', 'Tidak ada']),
            ]);
        }

        // Refresh collections
        $admins = User::where('is_admin', true)->orWhere('super_admin', true)->get();
        $adminIds = $admins->pluck('id')->all();
        $allMembers = User::where('is_admin', false)->where('super_admin', false)->get();
        $memberIds = $allMembers->pluck('id')->all();

        // Create 15 events (acaras)
        $acaras = collect();
        for ($i = 1; $i <= 15; $i++) {
            $tanggal = $faker->dateTimeBetween('-20 days', '+20 days');
            $waktuMulai = (clone $tanggal)->modify('+'.rand(0, 2).' hours');
            $waktuSelesai = (clone $waktuMulai)->modify('+'.rand(1, 3).' hours');
            $acara = Acara::create([
                'nama' => 'Kegiatan ' . $i,
                'deskripsi' => $faker->sentence(12),
                'tanggal' => $tanggal,
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'lokasi' => Arr::random(['Lapangan Upacara', 'Aula Sekolah', 'Lapangan Basket', 'Ruang Serbaguna']),
                'seragam' => Arr::random(['PDL', 'PDH', 'Olahraga', 'Bebas Rapi']),
                'perlengkapan' => ['Topi', 'Minum', 'Buku Catatan'],
                'created_by' => Arr::random($adminIds),
                'selesai' => $waktuSelesai < now(),
                'feedback' => $faker->boolean(50) ? $faker->sentences(2, true) : null,
            ]);
            $acaras->push($acara);

            // Wajib hadir (optional pivot) - add 10 random members
            $pivotUserIds = Arr::random($memberIds, min(10, count($memberIds)));
            $acara->wajibHadir()->syncWithoutDetaching($pivotUserIds);

            // Acara absen for 15 participants
            $pesertaUserIds = Arr::random($memberIds, min(15, count($memberIds)));
            foreach ($pesertaUserIds as $uid) {
                AcaraAbsen::firstOrCreate([
                    'acara_id' => $acara->id,
                    'user_id' => $uid,
                ], [
                    'hadir' => (bool) $faker->boolean(80),
                ]);
            }

            // Photos 5 per acara
            for ($p = 0; $p < 5; $p++) {
                AcaraPhoto::create([
                    'acara_id' => $acara->id,
                    'path' => 'acara_photos/dummy_' . $acara->id . '_' . $p . '.jpg',
                    'caption' => $faker->boolean(60) ? $faker->sentence(6) : null,
                    'uploaded_by' => Arr::random($adminIds),
                ]);
            }

            // Penilaian: 10 penilaian per acara
            $dinilaiUserIds = Arr::random($memberIds, min(10, count($memberIds)));
            foreach ($dinilaiUserIds as $uid) {
                $graderId = Arr::random($adminIds);
                // ensure unique: acara_id, user_id, graded_by
                AcaraPenilaian::firstOrCreate([
                    'acara_id' => $acara->id,
                    'user_id' => $uid,
                    'graded_by' => $graderId,
                ], [
                    'fisik' => rand(60, 100),
                    'kepedulian' => rand(60, 100),
                    'tanggung_jawab' => rand(60, 100),
                    'disiplin' => rand(60, 100),
                    'kerjasama' => rand(60, 100),
                ]);
            }
        }

        // Daily Absen: for last 15 days create attendance for 15 random users per day
        $tanggalList = [];
        for ($d = 0; $d < 15; $d++) {
            $tanggalList[] = now()->copy()->subDays($d)->toDateString();
        }

        foreach ($tanggalList as $tgl) {
            $usersForDay = Arr::random($memberIds, min(15, count($memberIds)));
            foreach ($usersForDay as $uid) {
                Absen::firstOrCreate([
                    'tanggal' => $tgl,
                    'user_id' => $uid,
                ], [
                    'hadir' => (bool) $faker->boolean(85),
                    'created_by' => Arr::random($adminIds),
                ]);
            }
        }

        // Kas Transaksi: 20 entries
        for ($k = 0; $k < 20; $k++) {
            $jenis = Arr::random(['pemasukan', 'pengeluaran']);
            KasTransaksi::create([
                'tanggal' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                'jenis' => $jenis,
                'jumlah' => $jenis === 'pemasukan' ? rand(50000, 300000) : rand(20000, 200000),
                'keterangan' => Arr::random(['Iuran anggota', 'Pembelian perlengkapan', 'Donasi alumni', 'Konsumsi latihan', 'Transportasi']),
                'created_by' => Arr::random($adminIds),
            ]);
        }

        // Member Status Logs: 30 entries
        $actions = ['pending', 'approved', 'rejected', 'activated', 'deactivated'];
        $statuses = ['pending', 'active', 'inactive', 'rejected'];
        for ($s = 0; $s < 30; $s++) {
            $userId = Arr::random($memberIds);
            $biodataId = optional(User::find($userId)->biodata)->id;
            $action = Arr::random($actions);
            $status = $action === 'approved' ? 'active' : Arr::random($statuses);
            MemberStatusLog::create([
                'user_id' => $userId,
                'biodata_id' => $biodataId,
                'action' => $action,
                'status' => $status,
                'reason' => $faker->boolean(30) ? $faker->sentence(8) : null,
                'admin_name' => User::find($aid = Arr::random($adminIds))->name,
                'admin_id' => $aid,
                'metadata' => [
                    'source' => Arr::random(['system', 'manual']),
                ],
            ]);
        }

        // Complaints: 15 entries
        for ($c = 1; $c <= 15; $c++) {
            $status = Arr::random(['baru', 'diproses', 'selesai']);
            $complaint = Complaint::create([
                'nama_pelapor' => $faker->name(),
                'judul' => 'Laporan ' . $c . ': ' . $faker->sentence(4),
                'deskripsi' => $faker->paragraph(2),
                'terlapor_user_id' => Arr::random($memberIds),
                'bukti_path' => $faker->boolean(40) ? 'complaints/bukti_' . Str::random(8) . '.jpg' : null,
                'status' => $status,
            ]);

            if ($status !== 'baru') {
                $fuBy = Arr::random($adminIds);
                $complaint->update([
                    'follow_up' => Arr::random([
                        'Sedang ditindaklanjuti oleh tim',
                        'Diverifikasi, menunggu klarifikasi',
                        'Telah diselesaikan dengan mediasi',
                    ]),
                    'follow_up_by_user_id' => $fuBy,
                    'follow_up_at' => $faker->dateTimeBetween('-10 days', 'now'),
                ]);
            }
        }
    }
}


