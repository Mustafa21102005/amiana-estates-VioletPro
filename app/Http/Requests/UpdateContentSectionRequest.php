<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContentSectionRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:200'],
            // The hero has no text field, so only the other sections must have text
            'body' => [Rule::requiredIf($this->route('section')->slug !== 'hero'), 'string', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'This section needs some text.',
        ];
    }
}
