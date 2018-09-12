<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateVatRequest extends Request {

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
            'country_code' => 'required',
			'country_name' => 'required',
			'standard_rate' => 'required',
			//'abbreviation' => 'required',

		];
	}
}
