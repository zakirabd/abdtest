<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialtiesRequests extends FormRequest
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
        $validate = [
            'name' => 'required',
            'user_id' => 'required',
            'lang_id' => 'required',
            'active' => 'required'
        ];
        return $validate;
    }
}
