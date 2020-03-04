<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderFileUpload extends FormRequest
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
            'file' => 'required|image|max:' . strval(1024 * 10)
        ];
    }

    public function messages()
    {
        return [
            'file.image' => "Файл должен быть изображением",
            'file.max' => "Размер файла не должен превышать 10 МБ",
        ];
    }
}
