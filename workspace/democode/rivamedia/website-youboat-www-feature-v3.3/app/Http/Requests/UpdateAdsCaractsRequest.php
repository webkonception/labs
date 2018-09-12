<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateAdsCaractsRequest extends Request {

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
			//'id' => 'required|unique:adscaracts,id,' . $this->adscaracts,
			'ad_country_code' => 'required',
			'sell_type' => 'required',
			'status' => 'required',
			'ad_price' => 'required|numeric',
			//'ad_title' => 'required',
			'adstypes_id' => 'required',
			'manufacturers_id' => 'required',
			'models_id' => 'required',
			'ad_description' => 'required',
			'countries_id' => 'required',
			'ad_phones' => 'required',
			//'dealerscaracts_id' => 'required',
			//'ad_dealer_name' => 'required',
		];
	}
}
