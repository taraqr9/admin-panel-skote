<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class OrganizationSettingStoreRequest extends FormRequest
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
            'created_by' => ['nullable', 'exists:users,id'],
            'updated_by' => ['nullable', 'exists:users,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active') ? 1 : 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ((int) $this->input('is_active') !== 1) {
                return;
            }

            $activeOrganizationExists = Organization::query()
                ->where('is_active', 1)
                ->exists();

            if ($activeOrganizationExists) {
                $validator->errors()->add(
                    'is_active',
                    'Only one organization can be active at a time. Please inactive the current active organization first.'
                );
            }
        });
    }
}
