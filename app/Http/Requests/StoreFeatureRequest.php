<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeatureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /** The textarea split into clean, unique feature titles. */
    public function titles(): array
    {
        $lines = preg_split('/\R/', (string) $this->input('titles'));

        // Strip pasted bullet symbols and whitespace from the start of each line
        $lines = array_map(fn($line) => trim(preg_replace('/^[\s\-*•·]+/u', '', $line)), $lines);

        return array_values(array_unique(array_filter($lines, fn($line) => $line !== '')));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'titles' => [
                'required',
                'string',
                'max:6000',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $titles = $this->titles();

                    if ($titles === []) {
                        $fail('Enter at least one feature.');
                    } elseif (count($titles) > 50) {
                        $fail('Add up to 50 features at a time.');
                    } elseif (collect($titles)->contains(fn($title) => mb_strlen($title) > 150)) {
                        $fail('Each feature must be 150 characters or fewer.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'titles.required' => 'Enter at least one feature.',
            'category_id.required' => 'Choose a category.',
        ];
    }

    /** After an error, land back on the features card instead of the top of the page. */
    protected function getRedirectUrl()
    {
        return url()->previous() . '#features';
    }
}
