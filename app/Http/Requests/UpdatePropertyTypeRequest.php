<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropertyTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // An unticked checkbox is not sent at all, so turn "missing" into false
        $this->merge(['is_featured' => $this->boolean('is_featured')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('property_types', 'name')->ignore($this->route('property_type')),
            ],
            'tagline' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:5000'],
            'size_sqm' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'bedrooms' => ['nullable', 'integer', 'min:1', 'max:255'],
            'units_count' => ['required', 'integer', 'min:1', 'max:255'],
            'is_featured' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:1', 'max:65535'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A property type with this name already exists.',
        ];
    }
}
