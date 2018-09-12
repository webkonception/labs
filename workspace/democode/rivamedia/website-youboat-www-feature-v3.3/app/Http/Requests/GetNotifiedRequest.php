<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GetNotifiedRequest extends Request
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
        $rules = [
            'name'     => 'required|min:6|max:255',
            'email'    => 'required|email|unique:getnotified,email|max:255',
            'country_code' => 'required',
        ];
        if (!app()->isLocal() && config('youboat.' . session()->get('country_code') . '.recaptcha')) {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }
        return $rules;
    }
}
