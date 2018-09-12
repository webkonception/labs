<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DashboardRequest extends Request
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
            'username'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ];
        /*if (!app()->isLocal() && config('youboat.' . session()->get('country_code') . '.recaptcha')) {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }*/
        return $rules;
    }
}
