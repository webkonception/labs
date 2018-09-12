<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateDealersCaractsRequest extends Request {

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
		return [
			'user_id' => 'required',
			'denomination' => 'required',
			//'emails' => 'required',
			//'phone_1' => 'required|numeric',
			'phone_1' => 'required',
			'country_id' => 'required',
		];
	}
}
