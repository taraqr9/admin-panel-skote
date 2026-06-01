<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationSettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'fiscal_start_month' => ['required', 'integer', 'between:1,12'],
            'fiscal_end_month' => ['required', 'integer', 'between:1,12'],
            'fiscal_year' => ['required', 'integer', 'digits:4', 'min:2015'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'updated_by' => ['nullable', 'exists:users,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('is_active') == null) {
            $this->merge([
                'is_active' => 0,
            ]);
        }

        $this->merge([
            'updated_by' => auth()->id(),
        ]);
    }
}
