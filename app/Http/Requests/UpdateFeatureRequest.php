<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureRequest extends FormRequest
{
    /** A separate error bag, so these errors don't appear on the "add features" form. */
    protected $errorBag = 'featureUpdate';

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
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sort_order' => ['required', 'integer', 'min:1', 'max:65535'],
        ];
    }

    protected function getRedirectUrl()
    {
        return url()->previous() . '#features';
    }
}
