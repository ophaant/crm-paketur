<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:users,id'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
