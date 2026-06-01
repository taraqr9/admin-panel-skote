<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:150'],

            'fiscal_year' => [
                'nullable',
                'integer',
                'digits:4',
                'min:2017',
                'max:'.(now()->year + 2),
            ],

            'fiscal_start_month' => [
                'nullable',
                'integer',
                'between:1,12',
            ],

            'fiscal_end_month' => [
                'nullable',
                'integer',
                'between:1,12',
            ],
        ];
    }
}
