<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class DeleteFileRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->mergeIfMissing(['type' => $this->type]); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'      => ['required', 'in:normal,logical,physical'],
        ];
    }

    public function messages()
    {
        return [
            'type.in'   => 'The type must be normal,logical,physical.',
        ];
    }

}
