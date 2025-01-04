<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $isUpdate = $this->method() === 'PUT' || $this->method() === 'PATCH';  // Cek metode request

        return [
            'name' => $isUpdate ? 'nullable|string|max:25' : 'required|string|max:25',
            'email' => $isUpdate ? 'nullable|email:rfc,dns|unique:users,email,' . $this->route('id') : 'required|email:rfc,dns|unique:users,email',
            'phone' => $isUpdate ? 'nullable|string|max:15|unique:users,phone,' . $this->route('id') : 'required|string|max:15|unique:users,phone',
            'address' => $isUpdate ? 'nullable|string|max:255' : 'required|string|max:255',
            'password' => $isUpdate ? 'nullable|string|min:8' : 'required|string|min:8',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
         $this->merge(['password' => bcrypt('password')]);
    }
}
