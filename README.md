# Sistem Pendaftaran Anggota Paskibra

<p align="center">
  <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

## Tentang Proyek

Aplikasi ini adalah sistem informasi berbasis web yang dibangun untuk mengelola proses pendaftaran anggota baru Paskibra. Sistem ini dirancang untuk memudahkan calon anggota dalam melakukan pendaftaran secara online, serta membantu admin dalam memverifikasi dan mengelola data pendaftar secara efisien.

Proyek ini dibangun menggunakan framework [Laravel](https://laravel.com) dan template admin [Mazer](https://github.com/zuramai/mazer).

## Fitur Utama

### Untuk Calon Anggota (User)
-   **Autentikasi:** Registrasi akun baru dan login ke sistem.
-   **Manajemen Profil:** Mengubah data profil pribadi dan password.
-   **Formulir Pendaftaran:** Mengisi biodata lengkap yang dibutuhkan untuk seleksi.
-   **Unggah Dokumen:** Mengunggah dokumen pendukung seperti pas foto.
-   **Status Pendaftaran:** Memantau status pendaftaran secara real-time (Menunggu Verifikasi, Diterima, Ditolak).

### Untuk Admin
-   **Dashboard:** Menampilkan ringkasan statistik pendaftar.
-   **Manajemen Pendaftar:** Melihat, mencari, dan mengelola seluruh data pendaftar.
-   **Verifikasi Data:** Melakukan verifikasi kelengkapan dan keabsahan data pendaftar.
-   **Ubah Status:** Mengubah status pendaftaran (menerima atau menolak calon anggota).

## Teknologi yang Digunakan

-   **Backend:** PHP 8.1+, Laravel 10.x
-   **Frontend:** Blade, Vite, Bootstrap 5
-   **Template Admin:** [Mazer](https://github.com/zuramai/mazer)
-   **Database:** MySQL, PostgreSQL (atau database lain yang didukung Laravel)

## Prasyarat

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   Database (contoh: MySQL, PostgreSQL)

## Panduan Instalasi

1.  **Clone repositori:**
    ```bash
    git clone https://github.com/rizkyferdinugraha/paskibra.git
    cd paskibra
    ```

2.  **Install dependensi backend (PHP):**
    ```bash
    composer install
    ```

3.  **Install dependensi frontend (JavaScript):**
    ```bash
    npm install
    ```

4.  **Build aset frontend:**
    Untuk development, jalankan:
    ```bash
    npm run dev
    ```
    Untuk production, jalankan:
    ```bash
    npm run build
    ```

5.  **Buat file `.env`:**
    Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```

6.  **Generate kunci aplikasi:**
    ```bash
    php artisan key:generate
    ```

7.  **Konfigurasi database:**
    Buka file `.env` dan sesuaikan pengaturan database Anda (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

8.  **Jalankan migrasi database:**
    Perintah ini akan membuat tabel-tabel yang dibutuhkan oleh aplikasi.
    ```bash
    php artisan migrate
    ```

9.  **(Opsional) Jalankan seeder untuk data awal:**
    Perintah ini akan mengisi tabel dengan data awal (contoh: data jurusan).
    ```bash
    php artisan db:seed
    ```
    > **Catatan:** Secara default, seeder tidak membuat akun admin. Anda dapat membuatnya melalui halaman registrasi dan mengubah role-nya di database, atau membuat seeder khusus untuk user admin.

10. **Jalankan server pengembangan:**
    ```bash
    php artisan serve
    ```

11. **Akses aplikasi:**
    Buka browser dan kunjungi `http://127.0.0.1:8000`.

## Kontribusi

Kontribusi untuk pengembangan proyek ini sangat diterima. Jika Anda ingin berkontribusi, silakan lakukan fork pada repositori ini, buat branch baru untuk fitur atau perbaikan Anda, dan ajukan Pull Request.

## Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT.
