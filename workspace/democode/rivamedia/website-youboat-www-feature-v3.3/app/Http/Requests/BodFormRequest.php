<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BodFormRequest extends Request
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
     * Get the validation rules that apply to the request for create.
     *
     * @return array
     */
    public static function rules()
    {
        $rules = [
            'country_code' => 'required',

            'adstypes_id' => 'required',

            'budget' => 'required|numeric',

            'ci_last_name' => 'required|min:2|max:255',
            'ci_email' => 'required|email|max:255',
            'ci_password' => 'required|min:6|max:255',
            'ci_phone' => 'required|numeric',
            'agree_cgv' => 'required',
        ];
        return $rules;
    }
    /**
     * Get the validation rules that apply to the request for update.
     *
     * @return array
     */
    public static function rulesUpdate()
    {
        $rules = [
            'country_code' => 'required',

            'adstypes_id' => 'required',

            'budget' => 'required|numeric',

            'ci_last_name' => 'required|min:2|max:255',
            'ci_email' => 'required|email|max:255',
            //'ci_password' => 'required|min:6|max:255',
            'ci_phone' => 'required|numeric',
            'agree_cgv' => 'required',
        ];
        return $rules;
    }
}
