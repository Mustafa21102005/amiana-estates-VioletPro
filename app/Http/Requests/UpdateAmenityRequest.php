<?php

namespace App\Http\Requests;

use App\Support\AmenityIcons;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAmenityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:120',
                Rule::unique('amenities', 'title')->ignore($this->route('amenity')),
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['required', 'string', Rule::in(AmenityIcons::names())],
            'sort_order' => ['required', 'integer', 'min:1', 'max:65535'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'An amenity with this title already exists.',
        ];
    }
}
