<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $isUpdate = $this->method() === 'PUT' || $this->method() === 'PATCH';  // Cek metode request

        return [
            'name' => $isUpdate ? 'nullable|string|max:25' : 'required|string|max:25',
            'email' => $isUpdate ? 'nullable|email:rfc,dns|unique:users,email,' . $this->route('id') : 'required|email:rfc,dns|unique:users,email',
            'phone' => $isUpdate ? 'nullable|string|max:15|unique:users,phone,' . $this->route('id') : 'string|max:15|unique:users,phone',
            'address' => $isUpdate ? 'nullable|string|max:255' : 'string|max:255',
            'password' => $isUpdate ? 'nullable|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/' : 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

//    public function prepareForValidation()
//    {
//         $this->merge(['password' => bcrypt('password')]);
//    }
}
