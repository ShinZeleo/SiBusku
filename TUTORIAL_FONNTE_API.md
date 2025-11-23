# 📱 Tutorial Registrasi Kredensial API WhatsApp (Fonnte)

Tutorial lengkap untuk mendaftar dan mengkonfigurasi API Fonnte di aplikasi SIBUSKU.

---

## 📋 Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Persyaratan](#persyaratan)
3. [Langkah 1: Membuat Akun Fonnte](#langkah-1-membuat-akun-fonnte)
4. [Langkah 2: Membuat Device](#langkah-2-membuat-device)
5. [Langkah 3: Mendapatkan Token API](#langkah-3-mendapatkan-token-api)
6. [Langkah 4: Menghubungkan Device](#langkah-4-menghubungkan-device)
7. [Langkah 5: Konfigurasi di Aplikasi Laravel](#langkah-5-konfigurasi-di-aplikasi-laravel)
8. [Langkah 6: Testing API](#langkah-6-testing-api)
9. [Troubleshooting](#troubleshooting)
10. [Referensi](#referensi)

---

## 🎯 Pendahuluan

Fonnte adalah layanan API untuk mengirim pesan WhatsApp secara otomatis. Di aplikasi SIBUSKU, API ini digunakan untuk:
- ✅ Mengirim notifikasi booking baru ke user
- ✅ Mengirim notifikasi konfirmasi booking ke user
- ✅ Mengirim notifikasi ke admin saat ada booking baru
- ✅ Logging semua pengiriman WhatsApp ke database

**Catatan Penting:**
- API Fonnte memerlukan device (WhatsApp) yang terhubung
- Device harus online dan terhubung ke internet
- Token API bersifat rahasia, jangan bagikan ke orang lain

---

## ✅ Persyaratan

Sebelum memulai, pastikan Anda memiliki:
- ✅ Akun WhatsApp aktif (untuk device)
- ✅ Koneksi internet stabil
- ✅ Email untuk registrasi akun Fonnte
- ✅ Akses ke file `.env` di aplikasi Laravel

---

## 📝 Langkah 1: Membuat Akun Fonnte

### 1.1. Kunjungi Website Fonnte

1. Buka browser dan kunjungi: **https://fonnte.com**
2. Klik tombol **"Daftar"** atau **"Sign Up"** di pojok kanan atas

### 1.2. Isi Form Registrasi

Isi form registrasi dengan data berikut:
- **Nama Lengkap**: Masukkan nama Anda
- **Email**: Masukkan email aktif (untuk verifikasi)
- **Password**: Buat password yang kuat
- **Konfirmasi Password**: Ketik ulang password

### 1.3. Verifikasi Email

1. Setelah submit, cek email Anda
2. Buka email dari Fonnte dan klik link verifikasi
3. Jika tidak ada di inbox, cek folder **Spam/Junk**

### 1.4. Login ke Dashboard

1. Setelah email terverifikasi, kembali ke **https://fonnte.com**
2. Login dengan email dan password yang sudah dibuat
3. Anda akan diarahkan ke **Dashboard Fonnte**

---

## 🔧 Langkah 2: Membuat Device

Device adalah WhatsApp yang akan digunakan untuk mengirim pesan. Setiap device memerlukan nomor WhatsApp aktif.

### 2.1. Akses Menu Device

1. Setelah login, di dashboard klik menu **"Device"** atau **"Devices"**
2. Klik tombol **"+ Tambah Device"** atau **"Add Device"**

### 2.2. Isi Data Device

Isi form dengan informasi berikut:
- **Nama Device**: Beri nama yang mudah diingat (contoh: "SIBUSKU Main Device")
- **Nomor WhatsApp**: Masukkan nomor WhatsApp yang akan digunakan
  - Format: **08xxxxxxxxxx** (tanpa +62)
  - Contoh: `081234567890`
- **Deskripsi** (opsional): Tambahkan deskripsi jika perlu

### 2.3. Simpan Device

1. Klik tombol **"Simpan"** atau **"Save"**
2. Device akan muncul di daftar dengan status **"Belum Terhubung"** atau **"Disconnected"**

---

## 🔑 Langkah 3: Mendapatkan Token API

Token API adalah kunci untuk mengakses API Fonnte. Setiap device memiliki token unik.

### 3.1. Buka Halaman Device

1. Di menu **"Device"**, klik device yang baru dibuat
2. Atau klik ikon **"Detail"** / **"Settings"** pada device

### 3.2. Salin Token

1. Di halaman detail device, cari bagian **"API Token"** atau **"Token"**
2. Token biasanya berbentuk string panjang (contoh: `SdPaBABamBanDYiukDVY`)
3. Klik tombol **"Salin"** atau **"Copy"** di sebelah token
4. **PENTING**: Simpan token di tempat yang aman, karena hanya ditampilkan sekali

**Contoh Token:**
```
SdPaBABamBanDYiukDVY
```

**⚠️ PERINGATAN:**
- Token bersifat rahasia, jangan bagikan ke siapa pun
- Jika token ter-expose, segera generate token baru di dashboard Fonnte
- Token hanya bisa dilihat sekali saat pertama dibuat

---

## 📱 Langkah 4: Menghubungkan Device

Sebelum bisa mengirim pesan, device harus terhubung terlebih dahulu.

### 4.1. Scan QR Code

1. Di halaman detail device, cari tombol **"Hubungkan"** atau **"Connect"**
2. Klik tombol tersebut, akan muncul **QR Code**
3. Buka WhatsApp di smartphone Anda
4. Pergi ke **Settings** → **Linked Devices** → **Link a Device**
5. Scan QR Code yang muncul di dashboard Fonnte
6. Tunggu hingga muncul pesan **"Device Terhubung"** atau **"Connected"**

### 4.2. Verifikasi Koneksi

1. Setelah scan QR Code, status device akan berubah menjadi **"Terhubung"** atau **"Connected"**
2. Di dashboard, pastikan status menunjukkan **hijau** atau **online**
3. Jika masih **merah** atau **offline**, coba scan ulang QR Code

**Tips:**
- Pastikan smartphone dan komputer terhubung ke internet yang sama
- Jika QR Code expired, klik **"Refresh QR Code"**
- Device harus tetap online untuk bisa mengirim pesan

---

## ⚙️ Langkah 5: Konfigurasi di Aplikasi Laravel

Setelah mendapatkan token, langkah selanjutnya adalah mengkonfigurasi di aplikasi Laravel.

### 5.1. Buka File `.env`

1. Buka file `.env` di root folder aplikasi Laravel
2. Jika belum ada, copy dari `.env.example`:
   ```bash
   cp .env.example .env
   ```

### 5.2. Tambahkan Konfigurasi Fonnte

Tambahkan konfigurasi berikut di file `.env`:

```env
# ============================================
# WhatsApp API Configuration (Fonnte)
# ============================================

# URL API Fonnte (default, tidak perlu diubah)
FONNTE_API_URL=https://api.fonnte.com/send

# Token API dari dashboard Fonnte (WAJIB DIISI)
FONNTE_API_TOKEN=SdPaBABamBanDYiukDVY

# Nomor WhatsApp Admin (untuk notifikasi booking baru)
# Format: 62xxxxxxxxxxx (tanpa +, tanpa 0 di depan)
# Contoh: 6281234567890
FONNTE_ADMIN_PHONE=6281234567890

# Kode negara default (62 untuk Indonesia)
FONNTE_DEFAULT_COUNTRY=62

# Enable/Disable WhatsApp service (true/false)
# Set false untuk disable sementara (development)
WHATSAPP_ENABLED=true

# Timeout untuk request API (dalam detik)
WHATSAPP_TIMEOUT=3
```

### 5.3. Penjelasan Konfigurasi

| Variabel | Deskripsi | Contoh | Wajib? |
|----------|-----------|--------|--------|
| `FONNTE_API_URL` | URL endpoint API Fonnte | `https://api.fonnte.com/send` | ✅ Ya (default) |
| `FONNTE_API_TOKEN` | Token API dari dashboard Fonnte | `SdPaBABamBanDYiukDVY` | ✅ **WAJIB** |
| `FONNTE_ADMIN_PHONE` | Nomor admin untuk notifikasi | `6281234567890` | ⚠️ Opsional |
| `FONNTE_DEFAULT_COUNTRY` | Kode negara default | `62` | ⚠️ Opsional (default: 62) |
| `WHATSAPP_ENABLED` | Enable/disable service | `true` atau `false` | ⚠️ Opsional (default: true) |
| `WHATSAPP_TIMEOUT` | Timeout request (detik) | `3` | ⚠️ Opsional (default: 3) |

### 5.4. Format Nomor Telepon

**PENTING**: Format nomor telepon harus benar!

- ✅ **Benar**: `6281234567890` (62 + nomor tanpa 0 di depan)
- ✅ **Benar**: `081234567890` (akan otomatis dikonversi ke 62)
- ❌ **Salah**: `+6281234567890` (jangan pakai +)
- ❌ **Salah**: `0812-3456-7890` (jangan pakai tanda hubung)

**Contoh:**
- Nomor: `081234567890` → Di `.env`: `6281234567890`
- Nomor: `085712345678` → Di `.env`: `6285712345678`

### 5.5. Verifikasi Konfigurasi

Setelah menambahkan konfigurasi, pastikan:
1. ✅ Token sudah diisi (tidak kosong)
2. ✅ Format nomor telepon benar (62xxxxxxxxxxx)
3. ✅ Tidak ada spasi di awal/akhir nilai
4. ✅ File `.env` sudah disimpan

---

## 🧪 Langkah 6: Testing API

Setelah konfigurasi selesai, uji apakah API sudah berfungsi dengan baik.

### 6.1. Test via Tinker (Laravel)

Buka terminal dan jalankan:

```bash
php artisan tinker
```

Kemudian jalankan perintah berikut:

```php
use App\Services\WhatsAppService;

// Test kirim pesan ke nomor Anda sendiri
WhatsAppService::send(
    '6281234567890', // Ganti dengan nomor Anda
    'Test pesan dari SIBUSKU API'
);
```

**Hasil yang Diharapkan:**
- ✅ Jika berhasil: Pesan WhatsApp masuk ke nomor tujuan
- ✅ Cek di dashboard Fonnte → **"Log"** untuk melihat status pengiriman
- ✅ Cek di database → tabel `whatsapp_logs` untuk melihat log

### 6.2. Test via Browser (Manual)

Buat file test sederhana di `public/test-wa.php`:

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\WhatsAppService;

$result = WhatsAppService::send(
    '6281234567890', // Ganti dengan nomor Anda
    'Test pesan dari SIBUSKU API'
);

if ($result) {
    echo "✅ Pesan berhasil dikirim!";
} else {
    echo "❌ Gagal mengirim pesan. Cek log untuk detail.";
}
```

Akses via browser: `http://localhost:8000/test-wa.php`

**⚠️ PENTING**: Hapus file `test-wa.php` setelah testing selesai!

### 6.3. Test via Booking (Real Scenario)

1. Login sebagai user di aplikasi
2. Buat booking baru
3. Cek WhatsApp Anda, seharusnya ada notifikasi booking
4. Cek di admin panel → **"WhatsApp Logs"** untuk melihat log

---

## 🔍 Troubleshooting

### Masalah 1: Token tidak valid / Unauthorized

**Gejala:**
- Error: `401 Unauthorized`
- Pesan: `Invalid token` atau `Token not found`

**Solusi:**
1. ✅ Pastikan token sudah di-copy dengan benar (tidak ada spasi)
2. ✅ Cek di dashboard Fonnte, pastikan token masih aktif
3. ✅ Jika token ter-expose, generate token baru di dashboard
4. ✅ Pastikan file `.env` sudah disimpan dan di-reload (restart server)

**Cara Generate Token Baru:**
1. Login ke dashboard Fonnte
2. Buka device → Settings
3. Klik **"Generate New Token"**
4. Copy token baru dan update di `.env`

---

### Masalah 2: Device tidak terhubung / Offline

**Gejala:**
- Error: `Device not connected`
- Status device di dashboard: **Merah/Offline**

**Solusi:**
1. ✅ Buka WhatsApp di smartphone
2. ✅ Cek **Linked Devices**, pastikan device Fonnte masih terhubung
3. ✅ Jika tidak terhubung, scan ulang QR Code di dashboard Fonnte
4. ✅ Pastikan smartphone dan server terhubung ke internet
5. ✅ Restart device di dashboard Fonnte jika perlu

---

### Masalah 3: Pesan tidak terkirim

**Gejala:**
- Tidak ada error, tapi pesan tidak masuk ke WhatsApp
- Status di log: `failed`

**Solusi:**
1. ✅ Cek nomor tujuan, pastikan format benar (62xxxxxxxxxxx)
2. ✅ Pastikan nomor sudah terdaftar di WhatsApp (sudah verifikasi)
3. ✅ Cek di dashboard Fonnte → **"Log"** untuk detail error
4. ✅ Cek di database → tabel `whatsapp_logs` → kolom `error_message`
5. ✅ Pastikan device online dan terhubung

---

### Masalah 4: Timeout / Request terlalu lama

**Gejala:**
- Error: `Connection timeout`
- Request memakan waktu > 10 detik

**Solusi:**
1. ✅ Cek koneksi internet server
2. ✅ Increase timeout di `.env`: `WHATSAPP_TIMEOUT=10`
3. ✅ Cek apakah API Fonnte sedang maintenance
4. ✅ Coba kirim ulang setelah beberapa saat

---

### Masalah 5: Service disabled

**Gejala:**
- Pesan tidak terkirim
- Log: `Service disabled`

**Solusi:**
1. ✅ Cek di `.env`: `WHATSAPP_ENABLED=true`
2. ✅ Pastikan nilai adalah `true` (bukan string `"true"`)
3. ✅ Clear config cache: `php artisan config:clear`

---

### Masalah 6: Format nomor salah

**Gejala:**
- Error: `Invalid phone number`
- Pesan tidak terkirim ke nomor yang benar

**Solusi:**
1. ✅ Pastikan format: `62xxxxxxxxxxx` (tanpa +, tanpa 0 di depan)
2. ✅ Contoh benar: `6281234567890`
3. ✅ Contoh salah: `+6281234567890`, `081234567890` (akan otomatis dikonversi)
4. ✅ Untuk nomor Indonesia, selalu gunakan kode `62`

---

## 📚 Referensi

### Dokumentasi Resmi Fonnte

- **Website**: https://fonnte.com
- **Dokumentasi API**: https://fonnte.com/docs
- **Dashboard**: https://fonnte.com/dashboard
- **Postman Collection**: Tersedia di dashboard Fonnte

### File Konfigurasi di Aplikasi

- **Service Config**: `config/services.php`
- **Service Class**: `app/Services/WhatsAppService.php`
- **Environment**: `.env`

### Endpoint API Fonnte

- **Send Message**: `POST https://api.fonnte.com/send`
- **Get Groups**: `GET https://api.fonnte.com/get-groups`
- **Fetch Groups**: `POST https://api.fonnte.com/fetch-groups`

### Parameter API (Single Message)

```php
[
    'target' => '6281234567890',      // Nomor tujuan (wajib)
    'message' => 'Pesan Anda',        // Isi pesan (wajib)
    'countryCode' => '62',            // Kode negara (opsional)
    'delay' => '2',                   // Delay antar pesan (detik, opsional)
    'schedule' => 1667433600,         // Unix timestamp (opsional)
]
```

### Header API

```php
[
    'Authorization' => 'YOUR_TOKEN_HERE',
    'Content-Type' => 'application/json',
]
```

---

## ✅ Checklist Setup

Gunakan checklist ini untuk memastikan semua langkah sudah dilakukan:

- [ ] Akun Fonnte sudah dibuat dan terverifikasi
- [ ] Device sudah dibuat di dashboard Fonnte
- [ ] Token API sudah di-copy dan disimpan dengan aman
- [ ] Device sudah terhubung (status: Connected/Online)
- [ ] Konfigurasi sudah ditambahkan di file `.env`
- [ ] Format nomor telepon sudah benar (62xxxxxxxxxxx)
- [ ] Testing API sudah dilakukan dan berhasil
- [ ] Notifikasi booking sudah berfungsi
- [ ] Log WhatsApp sudah tersimpan di database

---

## 🎉 Selesai!

Jika semua langkah sudah dilakukan dan testing berhasil, berarti API Fonnte sudah siap digunakan di aplikasi SIBUSKU!

**Fitur yang Tersedia:**
- ✅ Notifikasi booking baru ke user
- ✅ Notifikasi konfirmasi booking ke user
- ✅ Notifikasi booking baru ke admin
- ✅ Logging semua pengiriman ke database
- ✅ Retry mechanism jika gagal
- ✅ Duplicate prevention

**Pertanyaan atau Masalah?**
- Cek dokumentasi resmi Fonnte: https://fonnte.com/docs
- Cek log di dashboard Fonnte
- Cek log di database (`whatsapp_logs` table)
- Cek Laravel log: `storage/logs/laravel.log`

---

**Dibuat untuk**: Aplikasi SIBUSKU
**Versi**: 1.0
**Terakhir Update**: 2025

