<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAirlineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required', 'string', 'min:2', 'max:3', 'alpha',
                Rule::unique('airlines', 'code')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('airline')),
            ],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['code' => strtoupper((string) $this->code)]);
    }
}
