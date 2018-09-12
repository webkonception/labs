<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProspectiveCustomersRequest extends Request
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
        /*return [
            'ci_last_name' => 'required|min:6|max:255',
            'ci_email' => 'required|email|max:255',
            'ci_phone' => 'required|numeric',
            'country_code' => 'required'
        ];*/
        return [
            'ci_last_name' => 'required|min:6|max:255',
            'ci_email' => 'required|email|max:255',
            'country_code' => 'required'
        ];
    }
}
