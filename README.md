# Sistem Informasi Paskibra

<p align="center">
  <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

## Tentang Proyek

Aplikasi sistem informasi untuk organisasi Paskibra: pendaftaran anggota, manajemen keanggotaan, kegiatan/absensi, penilaian, galeri dokumentasi, hingga kas. Antarmuka admin berbasis template `Mazer` yang responsif.

## Teknologi

- **Backend**: PHP 8.2+, Laravel 12.x
- **Frontend**: Blade, Bootstrap 5, Vite 7, JavaScript
- **Charting**: ApexCharts (radar penilaian)
- **Template Admin**: Mazer Bootstrap Admin Template
- **Database**: MySQL/SQLite/pgsql (bebas sesuai .env)
- **Gambar**: Intervention Image (kompresi/resize)

## Fitur Utama

### 👤 Keanggotaan & Profil
- Pendaftaran anggota (Biodata lengkap, upload pas foto, nomor KTA otomatis)
- Status keanggotaan dengan log perubahan (approve/reject/deactivate)
- KTA digital (preview/print)

### 📆 Kegiatan & Absensi
- Buat/kelola acara, tentukan peserta wajib hadir berdasarkan role
- Absensi peserta (dibuka saat acara berjalan)
- Dokumentasi foto kegiatan (upload, lihat, unduh, hapus)
- Feedback hasil/evaluasi acara

### 📊 Dashboard Statistik (Role-aware)
- Tab “Acara & Kegiatan”: kalender AJAX dan ringkasan acara (berlangsung, akan datang, arsip)
- Tab “Statistik & Progress”:
  - Untuk role `Calon Junior`/`Junior`: statistik personal (rata-rata penilaian per aspek, radar chart ApexCharts, kehadiran bulanan dan total, riwayat kehadiran terbaru)
  - Untuk role selain `Calon Junior`/`Junior`: ringkasan statistik semua anggota `Calon Junior` dan `Junior` (nilai keseluruhan, rincian per aspek, kehadiran total dan bulan ini)

### 💰 Keuangan (Role Senior)
- Catat pemasukan/pengeluaran kas, lihat saldo dan laporan

### 🔐 Akses Berbasis Role
- `Super Admin`, `Admin`, `Senior`, `Junior`, `Calon Junior`
- Middleware khusus admin/super_admin/senior

## Prasyarat

- PHP >= 8.2, Composer
- Node.js >= 18 dan npm
- Database (MySQL/SQLite/pgsql)
- Ekstensi PHP GD/Imagick

## Instalasi

1) Clone & install dependencies
```bash
git clone https://github.com/rizkyferdinugraha/paskibra.git
cd paskibra
composer install
npm install
```

2) Setup environment
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` bagian database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paskibra
DB_USERNAME=root
DB_PASSWORD=
```

3) Migrasi & seeding
```bash
php artisan migrate
php artisan db:seed
```
Seeder akan menyiapkan Roles, Jurusan, dan akun Super Admin (`superadmin@paskibra.com` / `password`).

4) Storage symlink
```bash
php artisan storage:link
```

5) Jalankan aplikasi
- Opsi terpisah:
```bash
npm run dev        # build assets
php artisan serve  # server
```
- Opsi satu perintah (script composer):
```bash
composer run dev
```

## Akun Default
- Super Admin: `superadmin@paskibra.com` / `password`

## Struktur Database (inti)
- `users`, `roles`, `biodatas`
- `acaras`, `acara_absens`, `acara_photos`, `acara_penilaians`
- `kas_transaksis`, `member_status_logs`, `jurusans`

## Catatan Implementasi
- Radar chart menggunakan ApexCharts, dimount saat tab Statistik aktif
- Kalender acara AJAX dengan komponen Blade `components/calendar`
- Theme mengikuti `data-bs-theme` Mazer (light/dark) untuk konsistensi
- Gambar disimpan lewat disk `public` dan dikompresi

## Pengembangan
- Testing: `composer test` atau `php artisan test`
- Format kode (opsional): `./vendor/bin/pint`
- Utilitas artisan: `php artisan route:list`, `php artisan tinker`, `php artisan config:clear`

## Kontribusi
1. Fork repo dan buat branch fitur
2. Commit perubahan
3. Push branch dan buka Pull Request

## Lisensi
MIT License