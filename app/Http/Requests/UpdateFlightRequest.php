<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'flight_number' => [
                'required', 'string', 'max:20', 'regex:/^[A-Za-z0-9-]+$/',
                Rule::unique('flights', 'flight_number')->ignore($this->route('flight')),
            ],
            'airline_id' => ['required', 'exists:airlines,id'],
            'departure_airport_id' => ['required', 'exists:airports,id', 'different:arrival_airport_id'],
            'arrival_airport_id' => ['required', 'exists:airports,id'],
            'departure_time' => ['required', 'date'],
            'arrival_time' => ['required', 'date', 'after:departure_time'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'total_seats' => ['required', 'integer', 'min:1', 'max:1000'],
            'status' => ['required', Rule::in(['scheduled', 'delayed', 'cancelled', 'completed'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['flight_number' => strtoupper((string) $this->flight_number)]);
    }
}
