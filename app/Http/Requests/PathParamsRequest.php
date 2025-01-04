<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PathParamsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1'
        ];
    }

    public function prepareForValidation()
    {
        // Memastikan path parameter bisa divalidasi
        $this->merge([
            'id' => $this->route('id'), // Ambil parameter id dari route
        ]);
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
