<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBusRequest extends FormRequest
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
        $busId = $this->route('bus'); // Untuk update

        return [
            'name' => ['required', 'string', 'max:255'],
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('buses', 'plate_number')->ignore($busId),
            ],
            'capacity' => ['required', 'integer', 'min:1', 'max:60'],
            'bus_class' => ['required', 'string', 'in:Eksekutif,AC,Ekonomi'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama bus wajib diisi.',
            'plate_number.required' => 'Nomor plat wajib diisi.',
            'plate_number.unique' => 'Nomor plat sudah terdaftar.',
            'capacity.required' => 'Kapasitas wajib diisi.',
            'capacity.min' => 'Minimal 1 kursi.',
            'capacity.max' => 'Maksimal 60 kursi.',
            'bus_class.required' => 'Kelas bus wajib diisi.',
            'bus_class.in' => 'Kelas bus tidak valid.',
        ];
    }
}
