# Sistem Pendaftaran Anggota Paskibra

<p align="center">
  <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

## Tentang Proyek

Aplikasi ini adalah sistem informasi untuk mengelola pendaftaran anggota Paskibra: mulai dari pembuatan akun, pengisian biodata, pemantauan status, hingga persetujuan oleh admin. Admin panel menggunakan template [Mazer](https://github.com/zuramai/mazer).

## Teknologi

- **Backend**: PHP 8.2+, Laravel 12.x
- **Frontend**: Blade, Vite 7, Bootstrap 5
- **Template Admin**: Mazer
- **Database**: MySQL (atau database lain yang didukung Laravel)

## Fitur Utama

- **User**:
  - Autentikasi dan verifikasi email
  - Kelola profil akun
  - Pengisian dan pembaruan biodata
  - Cek Status Keanggotaan (`/membership/status`)
  - Pratinjau KTA (`/template/kta`)
- **Admin Panel** (`/admin`):
  - Dashboard admin
  - Persetujuan anggota (lihat, setujui, tolak, nonaktifkan, bulk approve)
  - Kelola Jurusan
  - Kelola Roles
  - Kelola Users (khusus Super Admin)

## Kontrol Akses

- Middleware `admin`: mengizinkan akses untuk pengguna dengan `is_admin` atau `super_admin` dan sudah memiliki `biodata`.
- Middleware `super_admin`: hanya untuk pengguna `super_admin` dan sudah memiliki `biodata`.
- Navigasi sidebar menampilkan seluruh menu admin untuk `admin` dan `super_admin`, namun menu/rute Kelola Users (`/admin/users`) hanya tampil dan bisa diakses oleh `super_admin`.

## Prasyarat

- PHP >= 8.2
- Composer
- Node.js >= 18 dan npm
- Database (contoh: MySQL)

## Instalasi & Menjalankan Aplikasi

1. Clone repo
   ```bash
   git clone https://github.com/rizkyferdinugraha/paskibra.git
   cd paskibra
   ```
2. Install dependensi backend
   ```bash
   composer install
   ```
3. Salin env dan generate key
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Konfigurasi database di `.env`
5. Migrasi database
   ```bash
   php artisan migrate
   ```
6. Seed data awal
   ```bash
   php artisan db:seed
   ```
   Seeder default akan:
   - Membuat data Roles dan Jurusan
   - Membuat akun Super Admin dan biodatanya
7. Install dependensi frontend
   ```bash
   npm install
   ```
8. Jalankan untuk pengembangan (opsi 1: terpisah)
   ```bash
   npm run dev
   php artisan serve
   ```
   atau (opsi 2: satu perintah via composer, membutuhkan npx)
   ```bash
   composer run dev
   ```

## Akun Awal (Seeder)

- Super Admin
  - Email: `superadmin@paskibra.com`
  - Password: `password`
  - Akses: semua fitur admin termasuk Kelola Users

## Seeder Opsional

Jalankan jika diperlukan:

```bash
php artisan db:seed --class=Database\Seeders\RoleSeeder
php artisan db:seed --class=Database\Seeders\JurusanSeeder
php artisan db:seed --class=Database\Seeders\SuperAdminBiodataSeeder
php artisan db:seed --class=Database\Seeders\TestMembersSeeder
php artisan db:seed --class=Database\Seeders\InitialStatusLogsSeeder
```

## Testing & Kualitas Kode

- Jalankan test:
  ```bash
  composer test
  ```
- Format kode (Laravel Pint):
  ```bash
  ./vendor/bin/pint
  ```

## Lisensi

MIT License.
