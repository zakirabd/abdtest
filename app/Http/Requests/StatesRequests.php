<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatesRequests extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'image' => 'nullable',
            'active' => 'required',
            'lang_id' => 'required',
            'user_id' => 'required',
        ];
        return $validate;
    }
}
