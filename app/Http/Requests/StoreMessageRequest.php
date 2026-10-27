<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'phone' => [
                'bail',
                'required',
                'string',
                'max:30',
                'regex:/^\+[0-9\s\-().]+$/',
                function (string $attribute, mixed $value, Closure $fail) {
                    $digits = strlen(preg_replace('/\D+/', '', $value));

                    // Real international numbers have between 8 and 15 digits, country code included
                    if ($digits < 8 || $digits > 15) {
                        $fail('Enter the full number including your country code, for example +232 76 123 456.');
                    }
                },
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return ['body' => 'message'];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Start with your country code and a plus sign, for example +232 76 123 456.',
        ];
    }

    /** After an error, land back on the form instead of the top of the page. */
    protected function getRedirectUrl()
    {
        return url()->previous() . '#contact';
    }
}
