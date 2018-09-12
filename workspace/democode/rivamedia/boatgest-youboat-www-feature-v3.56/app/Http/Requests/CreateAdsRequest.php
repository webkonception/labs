<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateAdsRequest extends Request {

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
            //'dealer_id' => 'required',
            'dealerscaracts_id' => 'required',
			'adstypes_id' => 'required',
			//'start_date' => 'required',
			//'end_date' => 'required',
			'status' => 'required',
		];
	}
}
