<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && ! $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'flight_id' => ['required', 'integer', 'exists:flights,id'],
            'seats_booked' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }
}
