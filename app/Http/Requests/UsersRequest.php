<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
    public function messages()
    {
        return [
            'email' => 'email không đúng định dạng',
            'required' => ':attribute không được bỏ trống',
        ];
        
    }

    public function attributes()
    {
    }
}
