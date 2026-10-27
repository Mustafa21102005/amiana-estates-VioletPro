<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
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
            'owner_type' => ['required', 'string', Rule::in(array_keys(Relation::morphMap()))],
            'owner_id' => ['required', 'integer'],
            'files' => ['required', 'array', 'min:1', 'max:20'],
            'files.*' => [
                'bail',
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,webm',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (! $value instanceof UploadedFile || ! $value->isValid()) {
                        return;
                    }

                    $isVideo = str_starts_with((string) $value->getMimeType(), 'video/');
                    $limit = config($isVideo ? 'media.video_kb' : 'media.image_kb') * 1024;

                    if ($value->getSize() > $limit) {
                        $fail(
                            $value->getClientOriginalName() . ' is ' . Number::fileSize($value->getSize(), 1) . '. '
                                . ($isVideo ? 'Videos' : 'Photos') . ' can be ' . Number::fileSize($limit) . ' at most.'
                        );
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'files.required' => 'Choose at least one photo or video.',
            'files.max' => 'Upload up to 20 files at a time.',
            'files.*.uploaded' => 'A file could not be uploaded. It is probably larger than the server allows.',
            'files.*.mimes' => 'Only JPG, PNG, WebP, MP4 and WebM files are allowed.',
        ];
    }

    /** After an error, land back on the gallery card instead of the top of the page. */
    protected function getRedirectUrl()
    {
        return url()->previous() . '#gallery';
    }
}
