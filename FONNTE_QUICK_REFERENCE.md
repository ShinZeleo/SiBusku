# 🚀 Fonnte API - Quick Reference Guide

Panduan cepat untuk setup dan troubleshooting API Fonnte di SIBUSKU.

---

## ⚡ Setup Cepat (5 Menit)

### 1. Daftar & Login
- Website: https://fonnte.com
- Buat akun → Verifikasi email → Login

### 2. Buat Device
- Menu: **Device** → **+ Tambah Device**
- Isi: Nama device + Nomor WhatsApp (08xxxxxxxxxx)
- Simpan

### 3. Dapatkan Token
- Buka device → **Settings** → **API Token**
- **Copy token** (hanya muncul sekali!)

### 4. Hubungkan Device
- Klik **"Hubungkan"** → Scan QR Code dengan WhatsApp
- Pastikan status: **Connected/Online**

### 5. Konfigurasi `.env`
```env
FONNTE_API_TOKEN=PASTE_TOKEN_DISINI
FONNTE_ADMIN_PHONE=6281234567890
WHATSAPP_ENABLED=true
```

### 6. Test
```bash
php artisan tinker
```
```php
use App\Services\WhatsAppService;
WhatsAppService::send('6281234567890', 'Test pesan');
```

---

## 📝 Format Nomor Telepon

| Format | Contoh | Status |
|--------|--------|--------|
| `62xxxxxxxxxxx` | `6281234567890` | ✅ **Benar** |
| `08xxxxxxxxxx` | `081234567890` | ✅ Auto-convert ke 62 |
| `+6281234567890` | `+6281234567890` | ❌ Jangan pakai + |
| `0812-3456-7890` | `0812-3456-7890` | ❌ Jangan pakai - |

**Aturan:**
- Untuk Indonesia: `62` + nomor tanpa `0` di depan
- Contoh: `081234567890` → `6281234567890`

---

## 🔧 Konfigurasi `.env` Lengkap

```env
# URL API (default, tidak perlu diubah)
FONNTE_API_URL=https://api.fonnte.com/send

# Token API (WAJIB - dari dashboard Fonnte)
FONNTE_API_TOKEN=SdPaBABamBanDYiukDVY

# Nomor Admin (opsional - untuk notifikasi booking)
FONNTE_ADMIN_PHONE=6281234567890

# Kode negara (default: 62 untuk Indonesia)
FONNTE_DEFAULT_COUNTRY=62

# Enable/Disable service (true/false)
WHATSAPP_ENABLED=true

# Timeout request (detik)
WHATSAPP_TIMEOUT=3
```

---

## 🧪 Testing

### Test via Tinker
```bash
php artisan tinker
```
```php
use App\Services\WhatsAppService;

// Test kirim pesan
WhatsAppService::send(
    '6281234567890',  // Nomor tujuan
    'Test pesan dari SIBUSKU'
);
```

### Test via Booking
1. Login sebagai user
2. Buat booking baru
3. Cek WhatsApp Anda
4. Cek log di: Admin Panel → WhatsApp Logs

---

## 🔍 Troubleshooting

### ❌ Error: Unauthorized (401)
**Penyebab:** Token tidak valid atau salah
**Solusi:**
- ✅ Cek token di `.env` (tidak ada spasi)
- ✅ Generate token baru di dashboard Fonnte
- ✅ Restart server: `php artisan config:clear`

### ❌ Error: Device not connected
**Penyebab:** Device offline atau tidak terhubung
**Solusi:**
- ✅ Scan ulang QR Code di dashboard Fonnte
- ✅ Cek WhatsApp → Linked Devices
- ✅ Pastikan device online

### ❌ Pesan tidak terkirim
**Penyebab:** Nomor salah atau device offline
**Solusi:**
- ✅ Cek format nomor (62xxxxxxxxxxx)
- ✅ Pastikan nomor sudah terdaftar WhatsApp
- ✅ Cek log di dashboard Fonnte
- ✅ Cek database: `whatsapp_logs` table

### ❌ Timeout
**Penyebab:** Koneksi lambat atau API down
**Solusi:**
- ✅ Increase timeout: `WHATSAPP_TIMEOUT=10`
- ✅ Cek koneksi internet
- ✅ Coba lagi setelah beberapa saat

### ❌ Service disabled
**Penyebab:** `WHATSAPP_ENABLED=false`
**Solusi:**
- ✅ Set `WHATSAPP_ENABLED=true` di `.env`
- ✅ Clear cache: `php artisan config:clear`

---

## 📊 Cek Status & Log

### Dashboard Fonnte
- **URL**: https://fonnte.com/dashboard
- **Menu**: Device → Log
- **Info**: Status pengiriman, error, dll

### Database Logs
- **Table**: `whatsapp_logs`
- **Kolom penting**:
  - `status`: `sent`, `pending`, `failed`
  - `error_message`: Detail error jika gagal
  - `sent_at`: Waktu pengiriman

### Laravel Logs
- **File**: `storage/logs/laravel.log`
- **Cari**: `WhatsApp sent successfully` atau `WhatsApp send failed`

---

## 🎯 Fitur yang Tersedia

| Fitur | Status | Keterangan |
|-------|--------|------------|
| Notifikasi Booking Baru | ✅ | Otomatis saat booking dibuat |
| Notifikasi Konfirmasi | ✅ | Otomatis saat booking dikonfirmasi |
| Notifikasi ke Admin | ✅ | Jika `FONNTE_ADMIN_PHONE` diisi |
| Logging ke Database | ✅ | Semua pengiriman di-log |
| Retry Mechanism | ✅ | Auto retry jika gagal (2x) |
| Duplicate Prevention | ✅ | Mencegah double send (5 detik) |

---

## 🔗 Link Penting

- **Website**: https://fonnte.com
- **Dashboard**: https://fonnte.com/dashboard
- **Dokumentasi**: https://fonnte.com/docs
- **Tutorial Lengkap**: [TUTORIAL_FONNTE_API.md](TUTORIAL_FONNTE_API.md)

---

## ✅ Checklist Setup

- [ ] Akun Fonnte dibuat
- [ ] Device dibuat
- [ ] Token di-copy
- [ ] Device terhubung (Connected)
- [ ] Konfigurasi di `.env` sudah benar
- [ ] Testing berhasil
- [ ] Notifikasi booking berfungsi

---

**💡 Tips:**
- Simpan token di tempat aman (password manager)
- Jangan commit `.env` ke Git
- Test dulu sebelum production
- Monitor log secara berkala

---

**Versi**: 1.0
**Update**: 2025

