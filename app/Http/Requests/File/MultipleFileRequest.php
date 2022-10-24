<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class MultipleFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'multiple_files'                => ['required', 'array', 'min:1', 'max:20'],
            'multiple_files.*'              => ['required', 'array'],
            'multiple_files.*.file'         => ['required', 'file', 'max:500'],
            'multiple_files.*.file_name'    => ['required', 'string', 'max:100']
        ];
    }

}
