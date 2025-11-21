<?php

namespace App\Http\Requests;

use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'trip_id' => ['required', 'exists:trips,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'seats_count' => ['required', 'integer', 'min:1', 'max:4'],
            'selected_seats' => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
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
            'seats_count.max' => 'Maksimal 4 kursi per booking.',
            'selected_seats.required' => 'Pilih kursi terlebih dahulu.',
        ];
    }

    /**
     * Configure the validator instance.
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
