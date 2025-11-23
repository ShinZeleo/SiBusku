<?php

namespace App\Http\Requests;

use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi data booking
 *
 * Form Request ini menangani validasi input saat user membuat booking baru.
 * Validasi dilakukan di 2 level:
 * 1. Basic validation (rules): Validasi format dan tipe data
 * 2. Custom validation (withValidator): Validasi business logic (kursi tersedia, dll)
 *
 * @package App\Http\Requests
 */
class StoreBookingRequest extends FormRequest
{
    /**
     * Menentukan apakah user berhak membuat request ini
     *
     * Authorization sebenarnya ditangani oleh middleware 'auth' di route,
     * jadi fungsi ini selalu return true.
     *
     * @return bool Selalu true (authorization di route)
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Mendapatkan aturan validasi untuk request
     *
     * Aturan validasi:
     * - trip_id: Harus ada dan valid (exists di tabel trips)
     * - customer_name: Wajib, string, max 255 karakter
     * - customer_phone: Wajib, string, max 20 karakter, format nomor (0-9, +, -, spasi, kurung)
     * - seats_count: Wajib, integer, min 1, max = available_seats trip
     * - selected_seats: Wajib, string (format: "A1, A2, B3")
     *
     * Catatan: max untuk seats_count dinamis berdasarkan available_seats trip.
     *
     * @return array Array aturan validasi Laravel
     */
    public function rules(): array
    {
        // Get trip to determine max seats dynamically
        $trip = Trip::find($this->trip_id);
        $maxSeats = $trip ? $trip->available_seats : 60; // Default to 60 if trip not found

        return [
            'trip_id' => ['required', 'exists:trips,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'seats_count' => ['required', 'integer', 'min:1', 'max:' . $maxSeats],
            'selected_seats' => ['required', 'string'],
        ];
    }

    /**
     * Mendapatkan pesan error custom untuk validasi
     *
     * Pesan error ini akan ditampilkan ke user jika validasi gagal.
     * Pesan dibuat dalam bahasa Indonesia agar user-friendly.
     *
     * @return array Array pesan error custom
     */
    public function messages(): array
    {
        return [
            'trip_id.required' => 'Pilih trip terlebih dahulu.',
            'trip_id.exists' => 'Trip yang dipilih tidak valid.',
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'customer_phone.regex' => 'Format nomor WhatsApp tidak valid.',
            'seats_count.required' => 'Jumlah kursi wajib diisi.',
            'seats_count.min' => 'Minimal pilih 1 kursi.',
            'seats_count.max' => 'Jumlah kursi melebihi kapasitas yang tersedia.',
            'selected_seats.required' => 'Pilih kursi terlebih dahulu.',
        ];
    }

    /**
     * Konfigurasi validator dengan custom validation logic
     *
     * Fungsi ini menambahkan validasi business logic setelah validasi dasar:
     * 1. Validasi jumlah kursi tidak melebihi available_seats
     * 2. Validasi jumlah kursi yang dipilih sesuai dengan seats_count
     * 3. Validasi tidak ada kursi duplikat
     * 4. Validasi kursi yang dipilih tidak sudah dibooking oleh user lain
     *
     * Validasi ini menggunakan after() callback, yang berarti akan dijalankan
     * setelah semua validasi dasar selesai.
     *
     * @param \Illuminate\Validation\Validator $validator Validator instance
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $trip = Trip::find($this->trip_id);

            if (!$trip) {
                return;
            }

            // Validasi kursi tersedia
            if ($trip->available_seats < $this->seats_count) {
                $validator->errors()->add(
                    'seats_count',
                    "Hanya tersedia {$trip->available_seats} kursi untuk trip ini."
                );
            }

            // Validasi kursi yang dipilih
            $selectedSeats = explode(',', $this->selected_seats);
            $selectedSeats = array_map('trim', $selectedSeats);
            $selectedSeats = array_filter($selectedSeats);

            if (count($selectedSeats) !== (int) $this->seats_count) {
                $validator->errors()->add(
                    'selected_seats',
                    'Jumlah kursi yang dipilih tidak sesuai dengan jumlah kursi yang diminta.'
                );
            }

            // Validasi kursi tidak duplikat
            if (count($selectedSeats) !== count(array_unique($selectedSeats))) {
                $validator->errors()->add(
                    'selected_seats',
                    'Terdapat kursi duplikat dalam pilihan.'
                );
            }

            // Validasi kursi tidak sudah dibooking
            $bookedSeats = $trip->booked_seats;
            $conflictingSeats = array_intersect($selectedSeats, $bookedSeats);

            if (!empty($conflictingSeats)) {
                $validator->errors()->add(
                    'selected_seats',
                    'Kursi ' . implode(', ', $conflictingSeats) . ' sudah dibooking oleh pengguna lain.'
                );
            }
        });
    }
}
