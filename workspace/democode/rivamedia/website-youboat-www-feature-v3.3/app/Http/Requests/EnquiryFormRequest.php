<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EnquiryFormRequest extends Request
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
    public static function rules()
    {
        $rules = [
            'country_code' => 'required',
            'ad_id' => 'required',
            //'ad_url' => 'required',
            //'ad_title' => 'required',
            //'ci_firstname' => 'required',
            'ci_last_name' => 'required|min:2|max:255',
            'ci_email' => 'required|email|max:255',
            //'ci_phone' => 'required|min:10|numeric',
            'ci_phone' => 'required|numeric',
            //'reference' => 'required|unique:enquiry,reference',

            //'ci_description' => 'required',
            //'ci_countries_id' => 'required'
        ];
        /*if (!app()->isLocal() || ) {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }*/
        return $rules;
    }

    /*public function messages()
    {
        return [
            'reference.unique' => 'You have already requested an enquiry for this ad.',
        ];
    }*/
}
