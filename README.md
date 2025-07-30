# Sistem Pendaftaran Anggota Paskibra

<p align="center">
  <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

## Tentang Proyek

Aplikasi ini adalah sistem informasi berbasis web yang dibangun untuk mengelola proses pendaftaran anggota baru Paskibra. Sistem ini memudahkan calon anggota untuk mendaftar secara online dan bagi admin untuk memverifikasi dan mengelola data pendaftar.

Proyek ini dibangun menggunakan framework [Laravel](https://laravel.com) dengan template admin [Mazer](https://github.com/zuramai/mazer).

## Fitur Utama

-   **Registrasi & Login Pengguna:** Calon anggota dapat membuat akun dan masuk ke sistem.
-   **Formulir Pendaftaran:** Formulir pengisian biodata lengkap, termasuk unggah pas foto.
-   **Status Pendaftaran:** Pengguna dapat melihat status pendaftaran mereka (Menunggu Verifikasi, Diterima).
-   **Panel Admin:** Halaman khusus untuk admin mengelola data pendaftar, melakukan verifikasi, dan mengubah status pendaftaran.
-   **Informasi Paskibra:** Menampilkan informasi terkait Paskibra kepada calon anggota.

## Prasyarat

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   Database (contoh: MySQL, PostgreSQL)

## Panduan Instalasi

1.  **Clone repositori ini:**
    ```bash
    git clone https://github.com/rizkyferdinugraha/paskibra.git
    cd paskibra
    ```

2.  **Install dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Install dependensi JavaScript:**
    ```bash
    npm install
    ```

4.  **Buat file `.env`:**
    Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```

5.  **Generate kunci aplikasi:**
    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi database:**
    Buka file `.env` dan sesuaikan pengaturan database Anda (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

7.  **Jalankan migrasi database:**
    Perintah ini akan membuat tabel-tabel yang dibutuhkan oleh aplikasi.
    ```bash
    php artisan migrate
    ```

8.  **(Opsional) Jalankan seeder untuk data awal:**
    Jika ada, perintah ini akan mengisi tabel dengan data awal (contoh: data jurusan).
    ```bash
    php artisan db:seed
    ```

9.  **Jalankan server pengembangan:**
    ```bash
    php artisan serve
    ```

10. **Akses aplikasi:**
    Buka browser dan kunjungi `http://127.0.0.1:8000`.

## Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT.
