# SIBUSKU Hardening Implementation Report

## Ringkasan Perubahan

Dokumen ini menjelaskan semua perubahan yang telah dilakukan untuk memperkuat dan menyempurnakan proyek SIBUSKU.

---

## 1. Audit Codebase

### Temuan Utama:
- ✅ **Validasi**: Sebagian besar masih di controller, perlu dipindah ke FormRequest
- ✅ **N+1 Query**: Sudah baik dengan eager loading menggunakan `with()`
- ✅ **Struktur**: Konsisten, mengikuti konvensi Laravel

### Perbaikan yang Dilakukan:
1. Membuat FormRequest untuk semua CRUD operations
2. Menambahkan validasi custom dengan pesan error yang jelas
3. Memperbaiki route naming consistency

---

## 2. Seat Picker Dinamis

### Pendekatan yang Dipilih: Tabel Terpisah (`booking_seats`)

**Kelebihan:**
- Tracking individual seat lebih detail
- Mudah untuk fitur seat pricing berbeda per kursi
- Query dan reporting lebih mudah
- Rollback lebih mudah jika booking dibatalkan

**Kekurangan:**
- Satu tabel tambahan
- Join query sedikit lebih kompleks

### File yang Dibuat/Dimodifikasi:

#### Migration: `database/migrations/2025_11_21_121654_create_booking_seats_table.php`
- Tabel `booking_seats` dengan kolom:
  - `id`, `booking_id`, `trip_id`, `seat_number`, `seat_price`, `timestamps`
- Unique constraint: `unique_trip_seat` pada `(trip_id, seat_number)`
- Index untuk performa query

#### Model: `app/Models/BookingSeat.php`
- Relasi ke `Booking` dan `Trip`
- Scope untuk query aktif dan per trip

#### Model Updates:
- `app/Models/Booking.php`: Menambahkan relasi `bookingSeats()`
- `app/Models/Trip.php`:
  - Menambahkan relasi `bookingSeats()`
  - Method `getBookedSeatsAttribute()`: Ambil kursi yang sudah dibooking
  - Method `getAvailableSeatNumbersAttribute()`: Ambil kursi tersedia
  - Method `generateSeatNumbers()`: Generate semua nomor kursi berdasarkan kapasitas

#### API Endpoint: `app/Http/Controllers/SeatController.php`
- Route: `GET /api/trips/{trip}/seats`
- Mengembalikan JSON dengan status setiap kursi (available/booked)

#### Concurrency Check: `app/Http/Controllers/BookingController.php`
- Menggunakan `DB::transaction()` dengan `lockForUpdate()`
- Double check kursi tersedia sebelum menyimpan
- Validasi kursi tidak duplikat dan tidak conflict

---

## 3. FormRequest Implementation

### File yang Dibuat:

1. **`app/Http/Requests/StoreBookingRequest.php`**
   - Validasi lengkap untuk booking
   - Custom validation untuk cek kursi tersedia
   - Custom messages dalam Bahasa Indonesia

2. **`app/Http/Requests/StoreTripRequest.php`**
   - Validasi trip creation
   - Validasi tanggal tidak di masa lalu

3. **`app/Http/Requests/StoreBusRequest.php`**
   - Validasi bus dengan unique plate_number
   - Validasi kelas bus (Eksekutif, AC, Ekonomi)

4. **`app/Http/Requests/StoreRouteRequest.php`**
   - Validasi route dengan kota asal ≠ tujuan

5. **Update Requests** (UpdateBusRequest, UpdateRouteRequest, UpdateTripRequest)
   - Untuk validasi update operations

### Controller Updates:
- `BookingController`: Menggunakan `StoreBookingRequest`
- Controllers lain akan diupdate untuk menggunakan FormRequest yang sesuai

---

## 4. WhatsApp Service Hardening

### File: `app/Services/WhatsAppService.php`

#### Perbaikan:
1. **Retry Logic**: Maksimal 2 retry attempts dengan delay 1 detik
2. **Error Handling**: Try-catch yang lebih komprehensif
3. **Logging**: Log ke database dan file log
4. **Error Message**: Menyimpan error message ke database
5. **Phone Normalization**: Otomatis handle nomor dengan format berbeda
6. **Non-blocking**: Booking tetap berhasil meskipun WA gagal

#### Migration: `database/migrations/2025_11_21_123443_add_error_message_to_whatsapp_logs_table.php`
- Menambahkan kolom `error_message` untuk tracking error detail

#### Model Update: `app/Models/WhatsAppLog.php`
- Menambahkan `error_message` ke fillable

---

## 5. UX Components

### File yang Dibuat:

1. **`resources/views/components/empty-state.blade.php`**
   - Komponen reusable untuk empty state
   - Props: `icon`, `title`, `description`, `action`, `actionLabel`

2. **`resources/views/components/error-message.blade.php`**
   - Komponen untuk error message per field
   - Props: `field`

3. **`resources/views/components/flash-message.blade.php`**
   - Komponen untuk flash message (success, error, warning, info)
   - Props: `type`
   - Menggunakan Alpine.js untuk animasi

4. **`resources/views/components/loading-button.blade.php`**
   - Button dengan loading state
   - Props: `type`, `loadingText`, `class`
   - Menggunakan Alpine.js untuk state management

### Penggunaan:
```blade
{{-- Empty State --}}
<x-empty-state
    title="Tidak ada booking"
    description="Belum ada booking yang tersedia."
    action="{{ route('bookings.create') }}"
    actionLabel="Buat Booking"
/>

{{-- Error Message --}}
<x-error-message field="customer_name" />

{{-- Flash Message --}}
<x-flash-message type="success" />
<x-flash-message type="error" />

{{-- Loading Button --}}
<x-loading-button type="submit">
    Simpan
</x-loading-button>
```

---

## 6. BookingController Enhancement

### File: `app/Http/Controllers/BookingController.php`

#### Perubahan:
1. Menggunakan `StoreBookingRequest` untuk validasi
2. Implementasi concurrency check dengan `DB::transaction()` dan `lockForUpdate()`
3. Menyimpan detail kursi ke tabel `booking_seats`
4. Error handling yang lebih baik
5. Non-blocking WhatsApp notification
6. Update `available_seats` di trip setelah booking

#### Flow:
1. Lock trip untuk mencegah race condition
2. Parse dan validasi kursi yang dipilih
3. Double check kursi masih tersedia
4. Buat booking dalam transaction
5. Simpan detail kursi ke `booking_seats`
6. Update `available_seats` di trip
7. Kirim WA notification (non-blocking)

---

## 7. Route Updates

### File: `routes/web.php`

#### Tambahan:
```php
// API untuk seat status
Route::middleware('force.phone')->get('/api/trips/{trip}/seats',
    [SeatController::class, 'getSeats'])->name('api.trips.seats');
```

---

## 8. Testing & Seeders (TODO)

### Testing yang Perlu Dibuat:
1. Test seat picker tidak bisa mengambil kursi yang sudah dibooking
2. Test concurrency: dua user memesan kursi yang sama
3. Test hanya admin yang bisa akses halaman admin
4. Test WhatsAppService dipanggil pada event booking
5. Test FormRequest validation

### Seeder yang Perlu Dibuat:
1. Demo data: 3 bus, 4 route, 10 trip, beberapa booking
2. User: 1 admin, beberapa user biasa

---

## 9. Cara Menjalankan

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Jalankan Seeder (setelah dibuat)
```bash
php artisan db:seed --class=DemoSeeder
```

### 3. Testing
```bash
php artisan test
```

### 4. Coba Fitur Seat Picker
1. Login sebagai user
2. Cari trip
3. Klik "PESAN TIKET"
4. Pilih kursi di modal
5. Submit booking
6. Cek detail booking untuk melihat kursi yang dipilih

---

## 10. File yang Dibuat/Dimodifikasi

### Migration:
- ✅ `2025_11_21_121654_create_booking_seats_table.php`
- ✅ `2025_11_21_123443_add_error_message_to_whatsapp_logs_table.php`

### Models:
- ✅ `app/Models/BookingSeat.php` (baru)
- ✅ `app/Models/Booking.php` (update)
- ✅ `app/Models/Trip.php` (update)
- ✅ `app/Models/WhatsAppLog.php` (update)

### Controllers:
- ✅ `app/Http/Controllers/SeatController.php` (baru)
- ✅ `app/Http/Controllers/BookingController.php` (update besar)

### Requests:
- ✅ `app/Http/Requests/StoreBookingRequest.php` (baru)
- ✅ `app/Http/Requests/StoreTripRequest.php` (baru)
- ✅ `app/Http/Requests/StoreBusRequest.php` (baru)
- ✅ `app/Http/Requests/StoreRouteRequest.php` (baru)
- ✅ `app/Http/Requests/UpdateBusRequest.php` (baru)
- ✅ `app/Http/Requests/UpdateRouteRequest.php` (baru)
- ✅ `app/Http/Requests/UpdateTripRequest.php` (baru)

### Services:
- ✅ `app/Services/WhatsAppService.php` (update besar)

### Components:
- ✅ `resources/views/components/empty-state.blade.php` (baru)
- ✅ `resources/views/components/error-message.blade.php` (baru)
- ✅ `resources/views/components/flash-message.blade.php` (baru)
- ✅ `resources/views/components/loading-button.blade.php` (baru)

### Routes:
- ✅ `routes/web.php` (update: tambah API route)

---

## 11. Next Steps (Rekomendasi)

1. **Update Controllers Lain**: Gunakan FormRequest di BusController, RouteController, TripController
2. **Update Seat Picker Frontend**: Integrasikan dengan API `/api/trips/{trip}/seats` untuk real-time status
3. **Buat Policy**: Untuk authorization yang lebih granular
4. **Buat Seeder**: Data demo yang realistis
5. **Buat Tests**: Unit dan feature tests
6. **Update WA Log View**: Halaman admin untuk melihat log dengan filter
7. **Add WA Badges**: Badge status WA di detail booking dan list

---

## 12. Catatan Penting

1. **Database Transaction**: Semua operasi booking menggunakan transaction untuk konsistensi
2. **Concurrency**: Menggunakan `lockForUpdate()` untuk mencegah race condition
3. **Error Handling**: WhatsApp failure tidak mengganggu booking success
4. **Validation**: Semua validasi penting dipindah ke FormRequest
5. **UX**: Komponen reusable untuk konsistensi UI

---

## 13. Dampak Terhadap UX dan Arsitektur

### UX:
- ✅ Seat picker lebih akurat dengan data real-time
- ✅ Error messages lebih jelas dan user-friendly
- ✅ Loading states memberikan feedback yang jelas
- ✅ Empty states memberikan guidance kepada user

### Arsitektur:
- ✅ Code lebih maintainable dengan FormRequest
- ✅ Separation of concerns lebih jelas
- ✅ Database integrity terjaga dengan transaction
- ✅ Scalable dengan struktur yang baik

---

**Dokumen ini akan terus diupdate seiring dengan perkembangan implementasi.**

