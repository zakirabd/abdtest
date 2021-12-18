<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequests extends FormRequest
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
            'type' => 'required',
            'ranking' => 'nullable',
            'international_ranking' => 'nullable',
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'logo' => 'nullable',
            'image' => 'nullable',
            'city_id' => 'required',
            'user_id' => 'required',
            'lang_id' => 'required',
            'active' => 'required',
            'youtube_link' => 'nullable'
        ];
        return $validate;
    }
}
