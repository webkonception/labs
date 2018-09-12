<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ForsaleController;
use App\Http\Controllers\SearchController;

use Redirect;
use Schema;
use App\BodCaracts;
use App\Http\Requests\CreateBodCaractsRequest;
use App\Http\Requests\UpdateBodCaractsRequest;
use Illuminate\Http\Request;

use App\Countries;

use View;

use Auth;
use App\CustomersCaracts;

class BodCaractsController extends Controller {

	/**
	 * Display a listing of bodcaracts
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request)
	{
		// customer
		if('customer' == Auth::user()->type){
			$user_id = Auth::user()->id;
			$customer_id = CustomersCaracts::where('user_id', $user_id)->pluck('id')->all();
			$bodcaracts = BodCaracts::where('customer_id',$customer_id)->orderBy("updated_at", "desc")->get();
		}
		// admin or commercial
		else if('admin' == Auth::user()->type || 'commercial' == Auth::user()->type) {
			$bodcaracts = BodCaracts::all()->sortByDesc("updated_at");
		}
		// dealer or private
		else {
			$bodcaracts = BodCaracts::where('status','valid')->orderBy("updated_at", "desc")->get();
		}
		$sell_type = getEnumValues('bod', 'sell_type');

		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		return view(config('quickadmin.route') . '.bodcaracts.index', compact('bodcaracts', 'sell_type', 'countries'));
	}

	/**
	 * Show the form for creating a new bodcaracts
	 *
	 * @return \Illuminate\View\View
	 */
	public function create(Request $request)
	{
		$sell_type = getEnumValues('bod', 'sell_type');

		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		$datasRequest = $request->all();
		$getDefaults = ForsaleController::getDefaults($datasRequest);

		return view(config('quickadmin.route') . '.bodcaracts.create', $getDefaults)->withInput($request->all());
		//return view(config('quickadmin.route') . '.bodcaracts.create', compact('getDefaults', 'sell_type', 'countries'))->withInput($request->all());

		//return view(config('quickadmin.route') . '.bodcaracts.create', compact('sell_type', 'countries'));
	}

	/**
	 * Store a newly created bodcaracts in storage.
	 *
	 * @param CreateBodCaractsRequest|Request $request
	 */
	public function store(CreateBodCaractsRequest $request)
	{
		$input = $request->all();

		/*$countries_ids= [];
		$array = $input['countries_id'];
		foreach($array as $key => $value) {
			$countries_ids[] = SearchController::getCountry($value)['id'];
		}
		$country_contracts_ids = serialize($countries_ids);
		$input['countries_id'] = $country_contracts_ids;*/

		BodCaracts::create($input);

		return redirect()->route(config('quickadmin.route') . '.bodcaracts.index');
	}

	/**
	 * Show the form for editing the specified bodcaracts.
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function edit($id, $action = 'edit')
	{
		$bodcaracts = BodCaracts::find($id);

		$sell_type = getEnumValues('bod', 'sell_type');

		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		$getDefaults = ForsaleController::getDefaults($bodcaracts);

		$ci_countries_id = !empty($bodcaracts->ci_countries_id) ? $bodcaracts->ci_countries_id : '';
		//$ci_countries_code = SearchController::getCountryById($ci_countries_id, false)['code'];
		$getCountryById = SearchController::getCountryById($ci_countries_id, false);
		if(is_array($getCountryById) && array_key_exists('code', $getCountryById)) {
				$ci_countries_code = $getCountryById['code'];
				$bodcaracts->ci_countries_id = $ci_countries_code;
		}

		$status = getEnumValues('bod', 'status');

		$datas = [
			'bodcaracts' => $bodcaracts,
			'sell_type' => $sell_type,
			'countries' => $countries,
			'status' => $status
		];
		$return = $datas + $getDefaults;

		if('edit' != $action) {
			return $return;
		} else {
			//return view(config('quickadmin.route') . '.bodcaracts.' . $action, compact('bodcaracts', 'getDefaults', 'sell_type', 'status', 'countries'));
			return view(config('quickadmin.route') . '.bodcaracts.' . $action, $return);
		}
	}

	/**
	 * Show the specified bodcaracts.
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function show($id)
	{
		$action = 'show';
		$return = $this->edit($id, $action);
		return view(config('quickadmin.route') . '.bodcaracts.' . $action, $return);
	}

	/**
	 * Update the specified bodcaracts in storage.
	 * @param UpdateBodCaractsRequest|Request $request
	 *
	 * @param  int  $id
	 */
	public function update($id, UpdateBodCaractsRequest $request)
	{
		try {
			$input = $request->all();
			$countries_code = !empty($input['countries_id']) ? $input['countries_id'] : '';
			//$countries_id = SearchController::getCountry($countries_code)['id'];
			$getCountry = SearchController::getCountry($countries_code);
			if(is_array($getCountry) && array_key_exists('id', $getCountry)) {
				$countries_id = $getCountry['id'];
				$input['countries_id'] = $countries_id;
			}

			$ci_countries_code = !empty($input['ci_countries_id']) ? $input['ci_countries_id'] : '';
			//$ci_countries_id = SearchController::getCountry($ci_countries_code)['id'];
			$getCountry = SearchController::getCountry($ci_countries_code);

			if(is_array($getCountry) && array_key_exists('id', $getCountry)) {
					$ci_countries_id = $getCountry['id'];
					$input['ci_countries_id'] = $ci_countries_id;
			}

			$request = new Request($input);

			$bodcaracts = BodCaracts::findOrFail($id);

			//$request = $this->saveFiles($request);

			$datasRequest = $request->all();
			if($bodcaracts->update($datasRequest)) {
				//return redirect()->route(config('quickadmin.route') . '.bodcaracts.index');
				return redirect()->route(config('quickadmin.route') . '.bodcaracts.index')->withMessage('Bod\'s caracts was successfully updated!');
			}

		} catch(\Exception $e) {
			return redirect()->back()->withErrors($e->getMessage());
		}
	}

	/**
	 * Remove the specified bodcaracts from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		BodCaracts::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.bodcaracts.index');
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
			BodCaracts::destroy($toDelete);
		} else {
			BodCaracts::whereNotNull('id')->delete();
		}

		return redirect()->route(config('quickadmin.route') . '.bodcaracts.index');
	}

}