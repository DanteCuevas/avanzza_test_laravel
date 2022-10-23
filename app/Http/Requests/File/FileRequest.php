<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'file'          => ['required', 'file', 'max:500'],
            'file_name'     => ['required', 'string', 'max:100'],
        ];
    }

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function () {
            $this->merge([
                'user_id' => auth()->user()->id,
            ]);
        });
    }

}
