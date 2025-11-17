# SIBUSKU - Sistem Booking Tiket Bus Antar Kota

SIBUSKU adalah sistem booking tiket bus antar kota yang dibangun dengan Laravel. Sistem ini dirancang untuk memudahkan pengguna dalam mencari dan memesan tiket bus antar kota, serta memberikan kemudahan administrasi bagi pihak manajemen.

## Fitur Utama

### 1. Sistem Otentikasi
- Registrasi dan login pengguna
- Verifikasi email
- Role-based access control (User/Admin)
- Password reset

### 2. Manajemen Data
- CRUD Bus (nama, plat nomor, kapasitas, kelas, status)
- CRUD Rute (kota asal, kota tujuan, durasi, status)
- CRUD Trip (jadwal keberangkatan, harga, jumlah kursi)

### 3. Sistem Booking
- Pencarian jadwal trip
- Booking tiket bus
- Manajemen status booking
- Riwayat booking pengguna

### 4. Dashboard
- Dashboard admin untuk mengelola semua fitur
- Dashboard user untuk melihat riwayat booking

### 5. Notifikasi
- WhatsApp notification service
- Status booking (pending, confirmed, cancelled, completed)

## Struktur Database

### Tabel-tabel Utama
- `users`: Data pengguna (dengan role: admin/user)
- `buses`: Informasi bus (nama, plat nomor, kapasitas, dll)
- `routes`: Rute perjalanan (asal, tujuan, durasi)
- `trips`: Jadwal perjalanan (tanggal, waktu, harga, kursi)
- `bookings`: Data booking (user, trip, jumlah kursi, status)
- `whatsapp_logs`: Log pengiriman notifikasi WhatsApp

## Cara Menjalankan Aplikasi

1. Clone repository
2. Install dependencies:
   ```bash
   composer install
   npm install && npm run build
   ```
3. Konfigurasi database dan Fonnte di `.env`:
   ```env
   FONNTE_API_URL=https://api.fonnte.com/send
   FONNTE_API_TOKEN=ISI_TOKEN_FONNTE_MU
   FONNTE_ADMIN_PHONE=6285157770208
   FONNTE_DEFAULT_COUNTRY=62
   ```
4. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```
5. Jalankan aplikasi:
   ```bash
   php artisan serve
   ```

## Testing

Sistem memiliki test untuk:
- Memastikan user biasa tidak bisa mengakses halaman admin
- Memastikan admin bisa membuat trip baru
- Memastikan scope Trip::upcoming bekerja dengan benar

Jalankan test:
```bash
php artisan test
```

## Integrasi WhatsApp dengan Fonnte

Sistem menggunakan layanan Fonnte untuk mengirim notifikasi WhatsApp otomatis ke pengguna dan admin. Konfigurasi terletak di `config/services.php` dan `.env`.

Fungsi notifikasi:
- `notifyBookingCreated()`: Kirim notifikasi saat booking baru dibuat
- `notifyBookingConfirmed()`: Kirim notifikasi saat booking dikonfirmasi oleh admin
- `send()`: Metode umum untuk mengirim pesan WhatsApp

Untuk testing, Anda bisa mengakses endpoint `/tes-wa` untuk menguji koneksi ke Fonnte.

## Teknologi yang Digunakan

- Laravel 11
- PHP 8.2+
- MySQL
- Tailwind CSS
- Laravel Breeze
- Blade Templates
- Fonnte API (untuk notifikasi WhatsApp)

## Struktur Proyek

Proyek ini dibangun mengikuti konvensi Laravel dengan struktur direktori standar:
- `app/Models` - Model Eloquent
- `app/Http/Controllers` - Controller
- `app/Http/Middleware` - Middleware (admin)
- `app/Services` - Service class (WhatsApp service)
- `resources/views` - Blade templates
- `database/migrations` - Migrasi database
- `tests/Feature` - Feature tests