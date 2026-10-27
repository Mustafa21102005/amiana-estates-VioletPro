<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    /** A separate error bag, so these errors don't appear on the upload form. */
    protected $errorBag = 'mediaUpdate';

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
            'alt_text' => ['nullable', 'string', 'max:200'],
            'caption' => ['nullable', 'string', 'max:200'],
            'sort_order' => ['required', 'integer', 'min:1', 'max:65535'],
        ];
    }

    protected function getRedirectUrl()
    {
        return url()->previous() . '#gallery';
    }
}
