# Sistem Informasi Paskibra

<p align="center">
  <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

## Tentang Proyek

Aplikasi sistem informasi lengkap untuk mengelola organisasi Paskibra, mulai dari pendaftaran anggota, manajemen keanggotaan, hingga pengelolaan kegiatan dan keuangan. Admin panel menggunakan template [Mazer](https://github.com/zuramai/mazer) untuk tampilan yang modern dan responsif.

## Teknologi

- **Backend**: PHP 8.2+, Laravel 12.x
- **Frontend**: Blade Templates, Vite 7, Bootstrap 5, JavaScript
- **Template Admin**: Mazer Bootstrap Admin Template
- **Database**: MySQL (atau database lain yang didukung Laravel)
- **File Storage**: Local Storage dengan kompresi otomatis untuk gambar
- **Image Processing**: Intervention Image untuk kompresi dan resize gambar

## Fitur Lengkap

### 👤 **Fitur User/Anggota**
- **Autentikasi**: Register, login, verifikasi email, reset password
- **Profil & Biodata**: 
  - Kelola akun dan biodata lengkap
  - Upload pas foto dengan kompresi otomatis
  - Generate nomor KTA otomatis berdasarkan tahun angkatan
- **Keanggotaan**:
  - Cek status keanggotaan dengan timeline persetujuan (`/membership/status`)
  - Pratinjau dan download KTA digital (`/template/kta`)
- **Kegiatan**: 
  - Lihat detail acara dan status kehadiran
  - Lihat galeri foto kegiatan

### 🛡️ **Fitur Admin Panel** (`/admin`)
- **Dashboard**: Statistik anggota dan ringkasan sistem
- **Manajemen Anggota**:
  - Persetujuan pendaftaran (approve, reject, deactivate)
  - Bulk approval untuk efisiensi
  - Kelola status anggota aktif
  - Lihat riwayat status dengan logging
- **Master Data**:
  - Kelola jurusan/program studi
  - Kelola roles dan permissions
  - Kelola users (khusus Super Admin)

### 🎯 **Fitur Senior** (Role Senior)
- **Manajemen Keuangan** (`/kas`):
  - Catat pemasukan dan pengeluaran kas
  - Lihat laporan keuangan dan saldo
  - Hapus transaksi dengan tracking
- **Manajemen Acara** (`/acara`):
  - Buat, edit, dan hapus acara/kegiatan
  - Tentukan peserta wajib hadir berdasarkan role
  - **Absen Kegiatan**: 
    - Input kehadiran peserta (hanya setelah acara dimulai)
    - Urutkan daftar berdasarkan nomor KTA
  - **Galeri Foto**:
    - Upload foto kegiatan dengan kompresi otomatis
    - Fitur download dan hapus foto
    - Modal viewer dengan navigasi
  - **Feedback Kegiatan**: Catat hasil dan evaluasi kegiatan
  - **Status Control**: Tandai acara selesai/belum selesai

## Kontrol Akses & Security

### **Middleware System**
- **`admin`**: Admin dan Super Admin dengan biodata lengkap
- **`super_admin`**: Hanya Super Admin dengan biodata lengkap  
- **`senior`**: Hanya role Senior untuk fitur khusus

### **Role-based Features**
- **Member**: Akses dasar (profil, status, KTA)
- **Senior**: Fitur manajemen kas dan acara
- **Admin**: Panel administrasi anggota
- **Super Admin**: Akses penuh termasuk user management

### **Smart Restrictions**
- **Acara yang belum dimulai**:
  - ❌ Tidak bisa absen
  - ❌ Tidak bisa upload foto  
  - ❌ Tidak bisa beri feedback
  - ❌ Tidak bisa tandai selesai
- **Data Ordering**: Semua daftar anggota diurutkan berdasarkan nomor KTA
- **File Security**: Kompresi otomatis gambar, validasi tipe file

## Prasyarat

- PHP >= 8.2
- Composer
- Node.js >= 18 dan npm
- Database (MySQL/PostgreSQL/SQLite)
- Extension PHP: GD atau Imagick (untuk image processing)

## Instalasi & Setup

### 1. Clone & Install Dependencies
```bash
git clone https://github.com/rizkyferdinugraha/paskibra.git
cd paskibra
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Configuration
Edit file `.env` untuk konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paskibra
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration & Seeding
```bash
php artisan migrate
php artisan db:seed
```

Seeder akan membuat:
- **Roles**: Super Admin, Admin, Senior, Junior, Calon Junior
- **Jurusan**: Data program studi default
- **Super Admin Account**: Email `superadmin@paskibra.com`, Password `password`
- **Status Logs**: Data logging awal

### 5. Storage Setup
```bash
php artisan storage:link
```

### 6. Run Development Server

**Opsi 1** (Terminal terpisah):
```bash
npm run dev        # Frontend assets
php artisan serve  # Backend server
```

**Opsi 2** (Satu perintah):
```bash
composer run dev   # Menjalankan keduanya
```

## Akun Default (Seeder)

### Super Admin
- **Email**: `superadmin@paskibra.com`
- **Password**: `password`
- **Akses**: Semua fitur termasuk user management

## Database Structure

### Key Tables
- **`users`**: Akun dan autentikasi
- **`biodatas`**: Data lengkap anggota dengan nomor KTA
- **`roles`**: System roles dan permissions
- **`jurusans`**: Master data program studi
- **`kas_transaksis`**: Transaksi keuangan organisasi
- **`acaras`**: Data kegiatan/acara
- **`acara_absens`**: Kehadiran per acara
- **`acara_photos`**: Galeri foto kegiatan
- **`member_status_logs`**: Audit trail perubahan status

## Seeder Opsional

Untuk development/testing tambahan:
```bash
php artisan db:seed --class=TestMembersSeeder
php artisan db:seed --class=InitialStatusLogsSeeder
```

## Development Tools

### Testing
```bash
composer test          # PHPUnit tests
php artisan test       # Artisan test runner
```

### Code Quality
```bash
./vendor/bin/pint      # Laravel Pint code formatter
php artisan insights   # PHP Insights (jika terinstall)
```

### Debugging
```bash
php artisan tinker     # Interactive shell
php artisan route:list # List semua routes
php artisan config:clear # Clear config cache
```

## File Structure Penting

```
app/
├── Http/Controllers/
│   ├── Admin/           # Admin panel controllers
│   ├── AcaraController  # Event management
│   ├── KasController    # Financial management
│   └── AbsenController  # Attendance system
├── Models/
│   ├── User, Biodata    # User & profile models
│   ├── Acara, AcaraAbsen, AcaraPhoto # Event models
│   └── KasTransaksi     # Financial models
└── Http/Middleware/     # Custom middlewares

resources/views/
├── admin/               # Admin panel views
├── layouts/             # Layout templates
└── components/          # Reusable components
```

## API & Extensions

Sistem ini dapat dikembangkan lebih lanjut dengan:
- **REST API**: Untuk mobile app atau integrasi
- **Real-time Notifications**: Laravel Broadcasting
- **Export Features**: PDF reports, Excel exports
- **Advanced Analytics**: Dashboard analytics
- **Mobile App**: React Native/Flutter integration

## Contributing

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Security

Jika menemukan vulnerability, silakan laporkan ke email maintainer.

## License

MIT License. Lihat file `LICENSE` untuk detail lengkap.

---

**Dikembangkan dengan ❤️ menggunakan Laravel & Bootstrap**