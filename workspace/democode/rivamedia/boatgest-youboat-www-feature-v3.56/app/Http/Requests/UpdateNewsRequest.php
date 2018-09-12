<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateNewsRequest extends Request {

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
			'author_name' => 'required',
			'author_email' => 'required',
			//'author_phone' => 'required',
			//'author_url' => 'required',
			//'url' => 'required',
			'title' => 'required',
			'intro' => 'required',
			'description' => 'required',
			//'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
			'photo' =>  ['required', 'regex:/(?i:^.*\.(jpe?g|png|gif)$)/i'],
			'date' => 'required',
			//'category' => 'required',
			//'rewrite_url' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
			//'status' => 'required',
		];
		return $rules;
	}
}
