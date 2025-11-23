# 🚌 SIBUSKU - Sistem Booking Tiket Bus Online

Sistem booking tiket bus online yang dibangun dengan **Laravel 11**, menggunakan arsitektur modern dengan Service Layer, Events & Listeners, dan Policy-based authorization.

---

## 📋 Ringkasan Fitur

### ✨ Fitur Utama
- **Pencarian Trip** - Cari trip berdasarkan asal, tujuan, dan tanggal
- **Seat Picker Dinamis** - Pilih kursi dengan layout real-time dari database
- **Rekomendasi Kursi Otomatis** - Sistem cerdas untuk rekomendasi kursi terbaik
- **Booking dengan Validasi** - Mencegah double booking dengan database transaction
- **Notifikasi WhatsApp** - Integrasi dengan Fonnte untuk notifikasi otomatis
- **E-Ticket PDF** - Download tiket dalam format PDF
- **Cancel Booking** - User bisa membatalkan booking sendiri (status pending)
- **Audit Log** - Log lengkap setiap perubahan status booking

### 🔐 Fitur Keamanan
- **Role-based Access Control** - Admin dan User dengan permission berbeda
- **Policy-based Authorization** - Fine-grained access control
- **Rate Limiting** - Mencegah spam booking
- **Input Validation** - Validasi dan sanitasi input
- **Concurrency Control** - Mencegah race condition saat booking

### 📊 Fitur Admin
- **CRUD Bus, Route, Trip, Booking**
- **Manage Seat Layout** - Atur layout kursi per bus
- **WhatsApp Logs** - Monitor notifikasi WhatsApp
- **Export CSV** - Export data booking dan trip
- **Dashboard Statistik** - Overview sistem

---

## 🛠️ Requirement

- **PHP** >= 8.2
- **Composer** >= 2.0
- **MySQL** >= 8.0 atau **PostgreSQL** >= 13
- **Node.js** >= 18 (untuk build assets)
- **NPM** atau **Yarn**

### Ekstensi PHP yang Diperlukan
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

---

## 📦 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/ShinZeleo/SiBusku.git
cd SiBusku
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Setup Environment
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sibusku
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Konfigurasi WhatsApp (Fonnte)

**📖 Untuk tutorial lengkap, lihat: [TUTORIAL_FONNTE_API.md](TUTORIAL_FONNTE_API.md)**

**Ringkasan cepat:**
1. Daftar akun di https://fonnte.com
2. Buat device dan dapatkan token API
3. Hubungkan device dengan scan QR Code
4. Tambahkan konfigurasi di `.env`:
```env
FONNTE_API_URL=https://api.fonnte.com/send
FONNTE_API_TOKEN=your_token_here
FONNTE_ADMIN_PHONE=6281234567890
FONNTE_DEFAULT_COUNTRY=62
WHATSAPP_ENABLED=true
WHATSAPP_TIMEOUT=3
```

### 6. Jalankan Migration & Seeder
```bash
# Buat database (jika belum ada)
# mysql -u root -p
# CREATE DATABASE sibusku;

# Jalankan migration dan seeder
php artisan migrate --seed
```

### 7. Build Assets
```bash
npm run build
# atau untuk development
npm run dev
```

### 8. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

---

## 👤 Kredensial Demo

Setelah menjalankan seeder, gunakan kredensial berikut:

### Admin
- **Email:** `admin@sibusku.com`
- **Password:** `password`

### User Demo
- **Email:** `budi@example.com`
- **Password:** `password`
- **Email:** `siti@example.com`
- **Password:** `password`

---

## 🎯 Cara Menggunakan Fitur Utama

### 1. Pencarian Trip
1. Buka halaman home (`/`)
2. Isi form pencarian:
   - Kota Asal
   - Kota Tujuan
   - Tanggal Keberangkatan
3. Klik "Cari Trip"
4. Pilih trip dari hasil pencarian

### 2. Booking Tiket
1. Klik "Lihat Detail" pada trip yang dipilih
2. Klik "Pilih Kursi dan Booking"
3. Isi data pemesan (nama, WhatsApp)
4. Klik "PILIH KURSI"
5. Pilih kursi atau klik "Pilih Kursi Terbaik untuk Saya"
6. Klik "GUNAKAN KURSI"
7. Klik "KONFIRMASI BOOKING"
8. Akan diarahkan ke halaman success

### 3. Download E-Ticket
1. Login sebagai user
2. Buka "Riwayat Booking"
3. Klik "Lihat Detail" pada booking yang sudah confirmed
4. Klik "Download E-Ticket PDF"

### 4. Cancel Booking
1. Login sebagai user
2. Buka "Riwayat Booking"
3. Klik "Batal" pada booking dengan status pending
4. Konfirmasi pembatalan

### 5. Manage Seat Layout (Admin)
1. Login sebagai admin
2. Buka "Bus" di menu admin
3. Klik "Layout Kursi" pada bus yang ingin dikelola
4. Atur nomor kursi, baris, kolom, dan section
5. Klik "Simpan Layout"

---

## 🧪 Testing

### Menjalankan Test
```bash
# Jalankan semua test
php artisan test

# Jalankan test spesifik
php artisan test --filter BookingSeatConflictTest
php artisan test --filter AdminRouteAccessTest
php artisan test --filter UserCancelBookingTest
```

### Test yang Tersedia
1. **BookingSeatConflictTest** - Test mencegah double booking kursi
2. **AdminRouteAccessTest** - Test akses route admin
3. **UserCancelBookingTest** - Test cancel booking oleh user

---

## 📊 Database Seeder

### Menjalankan Seeder
```bash
# Reset database dan seed ulang
php artisan migrate:fresh --seed

# Atau hanya seed tanpa reset
php artisan db:seed --class=DatabaseSeeder
```

### Data yang Disediakan
- 1 Admin user
- 2 User biasa
- 3 Bus dengan seat layout lengkap
- 4 Route (Jakarta-Bandung, Jakarta-Yogyakarta, dll)
- 10+ Trip aktif
- 2 Sample booking

---

## 🏗️ Arsitektur Sistem

Lihat dokumen lengkap: [ARSITEKTUR_SISTEM.md](ARSITEKTUR_SISTEM.md)

### Komponen Utama
- **Service Layer** - Business logic terpisah dari controller
- **Events & Listeners** - Event-driven architecture
- **Policies** - Policy-based authorization
- **API Endpoints** - RESTful API untuk frontend

---

## 📁 Struktur Project

```
sibusku/
├── app/
│   ├── Events/          # Laravel Events
│   ├── Http/
│   │   ├── Controllers/ # Controllers
│   │   ├── Middleware/  # Custom middleware
│   │   └── Requests/    # Form requests
│   ├── Listeners/       # Event listeners
│   ├── Models/          # Eloquent models
│   ├── Policies/        # Authorization policies
│   └── Services/        # Business logic services
├── database/
│   ├── factories/       # Model factories
│   ├── migrations/      # Database migrations
│   └── seeders/         # Database seeders
├── resources/
│   ├── views/           # Blade templates
│   ├── js/              # JavaScript (Alpine.js)
│   └── css/             # Tailwind CSS
├── routes/
│   └── web.php          # Web routes
└── tests/
    └── Feature/         # Feature tests
```

---

## 🔧 Konfigurasi Tambahan

### PDF Export (Opsional)
Untuk fitur export PDF, install package:
```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## 📝 Dokumentasi Lengkap

- **[Laporan Lengkap Sistem](LAPORAN_LENGKAP_SIBUSKU.md)** - Dokumentasi lengkap fitur, relasi, dan arsitektur
- **[Implementasi Lanjutan](ADVANCED_IMPLEMENTATION.md)** - Fitur lanjutan untuk nilai A++
- **[Arsitektur Sistem](ARSITEKTUR_SISTEM.md)** - Diagram arsitektur untuk presentasi
- **[Testing](TESTING.md)** - Dokumentasi testing
- **[Ringkasan Final](RINGKASAN_FINAL.md)** - Ringkasan semua implementasi

---

## 🐛 Troubleshooting

### Error: WhatsApp tidak terkirim
- Pastikan `FONNTE_API_TOKEN` sudah diisi di `.env`
- Pastikan nomor admin (`FONNTE_ADMIN_PHONE`) valid
- Cek log di `storage/logs/laravel.log`

### Error: Seat picker tidak muncul
- Pastikan JavaScript sudah di-build (`npm run build`)
- Cek browser console untuk error JavaScript
- Pastikan API endpoint `/api/trips/{trip}/seats` bisa diakses

### Error: PDF tidak bisa di-download
- Install package: `composer require barryvdh/laravel-dompdf`
- Pastikan booking status sudah `confirmed`

---

## 📄 License

MIT License

---

## 👥 Kontributor

- **Developer:** [ShinZeleo](https://github.com/ShinZeleo)

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Alpine.js
- Fonnte API

---

**Selamat menggunakan SIBUSKU!** 🚌✨
