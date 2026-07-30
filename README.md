<div align="center">
    <img src="icon/diskop.svg" alt="Logo Dinas Koperasi Provinsi Jawa Tengah" width="120">
    <h1>Sistem Absensi Peserta Magang</h1>
    <p><strong>Dinas Koperasi Usaha Kecil dan Menengah Provinsi Jawa Tengah</strong></p>
    <p>Aplikasi manajemen absensi dan izin untuk peserta magang</p>
</div>

---

## 📋 Daftar Isi

- [Tentang Aplikasi](#-tentang-aplikasi)
- [Fitur Utama](#-fitur-utama)
- [Peran Pengguna](#-peran-pengguna)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Instalasi](#-instalasi)
- [Struktur Database](#-struktur-database)
- [Hak Akses](#-hak-akses)
- [Screenshot](#-screenshot)
- [Kontak](#-kontak)

---

## 🏢 Tentang Aplikasi

**Sistem Absensi Peserta Magang** adalah aplikasi berbasis web yang dikembangkan untuk mengelola kehadiran dan pengajuan izin peserta magang di **Dinas Koperasi Usaha Kecil dan Menengah Provinsi Jawa Tengah**.

Aplikasi ini dirancang untuk:
- Mencatat absensi masuk dan pulang peserta magang
- Mengelola pengajuan izin (sakit, cuti, izin pribadi, dll)
- Memantau rekapitulasi kehadiran
- Mengelola data unit, peserta, dan jenis izin

---

## 🚀 Fitur Utama

### 👤 Sisi Peserta Magang
| Fitur | Deskripsi |
|-------|-----------|
| ✅ **Absensi Harian** | Absen masuk dan pulang dengan deteksi lokasi berbasis GPS |
| ✅ **Riwayat Absensi** | Melihat histori kehadiran harian |
| ✅ **Pengajuan Izin** | Mengajukan izin (sakit, cuti, izin pribadi, dll) |
| ✅ **Riwayat Izin** | Melihat status pengajuan izin |
| ✅ **Verifikasi Izin** | (Admin Unit) Menyetujui/menolak pengajuan izin |

### 👑 Sisi Super Admin
| Modul | Deskripsi |
|-------|-----------|
| 📊 **Dashboard** | Ringkasan data absensi harian, grafik kehadiran, statistik |
| 📁 **Data Master** | Kelola Unit, Jenis Izin, Peserta Magang, Jam Kerja |
| 📅 **Data Absensi** | Lihat semua absensi, absensi hari ini, rekap bulanan |
| 📄 **Pengajuan Izin** | Kelola semua pengajuan dan yang menunggu persetujuan |
| 📍 **Lokasi Kantor** | Atur titik lokasi absensi dengan radius |
| 👥 **Manajemen Akses** | Kelola akun Super Admin dan role pengguna |

---

## 👥 Peran Pengguna

| Role | Deskripsi |
|------|-----------|
| **Super Admin** | Mengelola semua data, verifikasi izin, dan konfigurasi sistem |
| **Admin Unit** | Memverifikasi pengajuan izin di unitnya |
| **Peserta** | Melakukan absensi dan mengajukan izin |

---

## 🛠 Teknologi yang Digunakan

### Backend
- **Framework**: Laravel 11
- **PHP**: ^8.2
- **Database**: MySQL / MariaDB
- **Authentication**: Laravel Breeze / Jetstream

### Frontend
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js 3.x
- **Chart**: Chart.js 4.x
- **Maps**: Leaflet.js + OpenStreetMap
- **Icons**: Custom SVG

### Tools
- **Version Control**: Git & GitHub
- **Package Manager**: Composer & NPM
- **Development**: Vite

---

## 💻 Instalasi

### Prasyarat
- PHP ^8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/username/sistem-absensi-magang.git
cd sistem-absensi-magang

# 2. Install dependencies PHP
composer install

# 3. Install dependencies JavaScript
npm install

# 4. Copy file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_magang
DB_USERNAME=root
DB_PASSWORD=

# 7. Jalankan migrasi database
php artisan migrate

# 8. Jalankan seeder (opsional)
php artisan db:seed

# 9. Build assets
npm run build

# 10. Jalankan server
php artisan serve
