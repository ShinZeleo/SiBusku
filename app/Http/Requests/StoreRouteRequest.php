<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
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
            'origin_city' => ['required', 'string', 'max:255'],
            'destination_city' => [
                'required',
                'string',
                'max:255',
                'different:origin_city',
            ],
            'duration_estimate' => ['required', 'numeric', 'min:0.1', 'max:24'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'origin_city.required' => 'Kota asal wajib diisi.',
            'destination_city.required' => 'Kota tujuan wajib diisi.',
            'destination_city.different' => 'Kota tujuan harus berbeda dengan kota asal.',
            'duration_estimate.required' => 'Estimasi durasi wajib diisi.',
            'duration_estimate.min' => 'Estimasi durasi minimal 0.1 jam.',
            'duration_estimate.max' => 'Estimasi durasi maksimal 24 jam.',
        ];
    }
}
