<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
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
            'route_id' => ['required', 'exists:routes,id'],
            'bus_id' => ['required', 'exists:buses,id'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'departure_time' => ['required', 'date_format:H:i'],
            'price' => ['required', 'numeric', 'min:0'],
            'total_seats' => ['required', 'integer', 'min:1', 'max:60'],
            'status' => ['nullable', 'in:scheduled,running,completed,cancelled'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'route_id.required' => 'Pilih rute terlebih dahulu.',
            'route_id.exists' => 'Rute yang dipilih tidak valid.',
            'bus_id.required' => 'Pilih bus terlebih dahulu.',
            'bus_id.exists' => 'Bus yang dipilih tidak valid.',
            'departure_date.required' => 'Tanggal keberangkatan wajib diisi.',
            'departure_date.after_or_equal' => 'Tanggal keberangkatan tidak boleh di masa lalu.',
            'departure_time.required' => 'Jam keberangkatan wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'price.min' => 'Harga tidak boleh negatif.',
            'total_seats.required' => 'Jumlah kursi wajib diisi.',
            'total_seats.min' => 'Minimal 1 kursi.',
            'total_seats.max' => 'Maksimal 60 kursi.',
        ];
    }
}
