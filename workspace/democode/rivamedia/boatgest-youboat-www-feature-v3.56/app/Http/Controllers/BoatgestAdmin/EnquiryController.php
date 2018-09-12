<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ForsaleController;
use App\Http\Controllers\SearchController;

use Redirect;
use Schema;
use App\Enquiry;
use App\Http\Requests\EnquiryRequest;
use Illuminate\Http\Request;

use App\Countries;

use View;

use Auth;
use App\CustomersCaracts;

class EnquiryController extends Controller {

	/**
	 * Display a listing of enquiry
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request)
	{
		$enquiries = Enquiry::orderBy("updated_at", "desc")->get();

		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		return view(config('quickadmin.route') . '.enquiry.index', compact('enquiries', 'countries'));
	}

	/**
	 * Show the specified enquiry.
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function show($id)
	{
		$action = 'show';
		$return = $this->edit($id, $action);
		return view(config('quickadmin.route') . '.enquiry.' . $action, $return);
	}

	/**
	 * Show the form for editing the specified enquiry.
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function edit($id, $action = 'edit')
	{
		$enquiries = Enquiry::find($id);

		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		$ci_countries_id = !empty($enquiries->ci_countries_id) ? $enquiries->ci_countries_id : '';
		$getCountryById = SearchController::getCountryById($ci_countries_id, false);
		if(is_array($getCountryById) && array_key_exists('code', $getCountryById)) {
			$ci_countries_code = $getCountryById['code'];
			$enquiries->ci_countries_id = $ci_countries_code;
		}

		$datas = [
			'enquiries' => $enquiries,
			'countries' => $countries
		];
		$return = $datas;

		if('edit' != $action) {
			return $return;
		} else {
			return view(config('quickadmin.route') . '.enquiry.' . $action, $return);
		}
	}

	/**
	 * Store a newly created enquiry in storage.
	 *
	 * @param EnquiryRequest|Request $request
	 */
	public function store(EnquiryRequest $request)
	{

		Enquiry::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.news.index');
	}

	/**
	 * Update the specified enquiry in storage.
	 * @param EnquiryRequest|Request $request
	 *
	 * @param  int  $id
	 */
	public function update($id, EnquiryRequest $request)
	{
		$enquiry = Enquiry::findOrFail($id);

		$enquiry->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.enquiry.index');
	}

	/**
	 * Remove the specified enquiry from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Enquiry::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.enquiry.index');
	}

	/**
	 * Mass delete function from index page
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function massDelete(Request $request)
	{
		if ($request->get('toDelete') != 'mass') {
			$toDelete = json_decode($request->get('toDelete'));
			Enquiry::destroy($toDelete);
		} else {
			Enquiry::whereNotNull('id')->delete();
		}

		return redirect()->route(config('quickadmin.route') . '.enquiry.index');
	}

}