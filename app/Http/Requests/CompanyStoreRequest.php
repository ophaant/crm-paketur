<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $isUpdate = $this->method() === 'PUT' || $this->method() === 'PATCH';  // Cek metode request

        return [
            'name' => $isUpdate ? 'nullable|string|max:50|unique:companies,name' : 'required|string|max:50|unique:companies,name',
            'email' => $isUpdate ? 'nullable|email:rfc,dns|unique:companies,email,' . $this->route('id') : 'required|email:rfc,dns|unique:companies,email',
            'phone' => $isUpdate ? 'nullable|string|max:15|unique:companies,phone,' . $this->route('id') : 'required|string|max:15|unique:companies,phone',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
