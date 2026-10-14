# Panduan Instalasi Project Laravel

Dokumentasi ini berisi langkah-langkah untuk menginstal dan menjalankan project Laravel di lingkungan lokal menggunakan Laragon.

## Persyaratan

Pastikan aplikasi berikut sudah terinstal di komputer:

- PHP
- Composer
- MySQL/MariaDB
- Laragon
- Node.js dan npm (jika project menggunakan Vite/Inertia)

## Langkah-langkah Instalasi

### 1. Masuk ke Folder Project

Buka terminal atau CMD, kemudian masuk ke folder project Laravel.

```bash
cd nama-folder-project
```

Sesuaikan `nama-folder-project` dengan nama folder project kamu.

### 2. Install Dependensi PHP

Jalankan perintah berikut untuk menginstal dependensi PHP berdasarkan file `composer.json`.

```bash
composer install
```

### 3. Salin File Environment

Buat file `.env` berdasarkan file `.env.example`.

```bash
copy .env.example .env
```

Perintah tersebut digunakan untuk membuat konfigurasi environment lokal.

### 4. Generate Application Key

Jalankan perintah berikut untuk membuat application key Laravel.

```bash
php artisan key:generate
```

Application key digunakan Laravel untuk kebutuhan enkripsi aplikasi.

### 5. Konfigurasi Database

Buka file `.env`, kemudian sesuaikan konfigurasi database dengan pengaturan MySQL di Laragon.

Contoh konfigurasi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:** Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan konfigurasi MySQL yang digunakan.

Selanjutnya, buat database dengan nama yang sama seperti nilai `DB_DATABASE` melalui HeidiSQL atau phpMyAdmin.

### 6. Jalankan Migrasi Database

Jalankan perintah berikut untuk membuat tabel berdasarkan file migration dan menjalankan seeder jika tersedia.

```bash
php artisan migrate --seed
```

Jika project tidak memiliki seeder atau tidak ingin menjalankannya, gunakan:

```bash
php artisan migrate
```

**Perhatian:** Jika database sudah berisi data penting, periksa terlebih dahulu file migration dan seeder sebelum menjalankan perintah.

### 7. Install Dependensi Frontend

Langkah ini diperlukan jika project memiliki file `package.json`, misalnya project yang menggunakan Vite, Vue, React, atau Inertia.js.

Install dependensi frontend:

```bash
npm install
```

Jalankan Vite dalam mode development:

```bash
npm run dev
```

Biarkan terminal tersebut tetap berjalan selama aplikasi digunakan.

### 8. Jalankan Server Laravel

Buka terminal baru pada folder project yang sama, kemudian jalankan:

```bash
php artisan serve
```

Jika berhasil, Laravel biasanya menampilkan alamat server lokal:

```text
http://127.0.0.1:8000
```

Buka alamat tersebut di browser untuk mengakses aplikasi.

## Ringkasan Perintah

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

**Catatan:** Konfigurasi database dilakukan melalui file `.env` sebelum menjalankan migrasi. Perintah `npm install` dan `npm run dev` hanya diperlukan jika project menggunakan frontend berbasis Node.js/Vite.

## Troubleshooting

- **Database connection error:** Periksa konfigurasi database di `.env` dan pastikan MySQL Laragon sedang berjalan.
- **Application key missing:** Jalankan `php artisan key:generate`.
- **Tabel belum tersedia:** Jalankan `php artisan migrate`.
- **CSS atau JavaScript tidak tampil:** Pastikan `npm install` sudah selesai dan `npm run dev` masih berjalan.
- **Dependensi PHP bermasalah:** Periksa versi PHP dan Composer yang digunakan, kemudian jalankan kembali `composer install`.

## Selesai

Jika semua langkah berhasil, aplikasi Laravel sudah dapat dijalankan secara lokal melalui browser.