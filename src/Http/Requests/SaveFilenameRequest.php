<?php

namespace Itstudioat\Mediamanager\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveFilenameRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.path' => ['nullable', 'string'],
            'data.current_filename' => ['required', 'string', 'regex:/^[A-Za-z0-9_\-\.]+$/'],
            'data.filename' => ['required', 'string', 'regex:/^[A-Za-z0-9_\-\.]+$/'],
            'data.extension' => ['required', 'string'],
        ];
    }
}
