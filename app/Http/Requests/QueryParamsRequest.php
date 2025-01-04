<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueryParamsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sort_by' => 'string',
            'sort_order' => 'string|in:asc,desc',
            'search' => 'string',
            'per_page' => 'integer',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
