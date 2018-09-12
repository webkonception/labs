<?php namespace App\Http\Controllers\boatgestAdmin;

use App\User;
use App\CustomersCaracts;
use App\Countries;

use Auth;
use File;
use Redirect;
use Schema;
use Session;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchController;

use Illuminate\Http\Request;
use App\Http\Requests\CreateCustomersCaractsRequest;
use App\Http\Requests\UpdateCustomersCaractsRequest;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Traits\FileUploadTrait;

class CustomersCaractsController extends Controller {

	/**
	 * Display a listing of customerscaracts
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
		if('admin' != Auth::user()->type && 'commercial' != Auth::user()->type) {
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
		//$customerscaracts = CustomersCaracts::all();
		$customerscaracts = CustomersCaracts::orderBy('updated_at', 'desc')->get();
		//$customersusernames = User::where('role_id', 6)->lists('username','id');
		$customersusernames = User::where('type', 'customer')
			//->where('status', 'active')
			//->whereNotNull('created_at')
			//->whereNotNull('updated_at')
			//->whereNotNull('deleted_at')
			->orderBy('username', 'asc')
			->lists('username','id');

		if(isset($customersusernames)) {
			$array = json_decode(json_encode($customersusernames), true);
			if (is_array($array) && isset($array)) {
				$customersusernames = $array;
			}
		}
		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		$status = getEnumValues('users', 'status');

		return view(config('quickadmin.route') . '.customerscaracts.index', compact('customerscaracts', 'customersusernames', 'countries', 'status'));
	}

	/**
	 * Show the form for creating a new customerscaracts
	 *
     * @return \Illuminate\View\View
	 */
	public function create(Request $request)
	{
		$useremail = '';
		$username = '';
		$input = $request->all();
		$user_id = !empty($input['user_id']) ? $input['user_id'] : null;
		$user_status = '';

		//if('customer' == Auth::user()->type && Auth::user()->id == $user_id) {
		if(
			(!empty($user_id) && ('admin' == Auth::user()->type || 'commercial' == Auth::user()->type))
			||
			(!empty($user_id) && 'customer' == Auth::user()->type && Auth::user()->id == $user_id)
		) {	$customersusernames = User::where('type', 'customer')
				//->where('status', 'active')
				//->whereNotNull('created_at')
				//->whereNotNull('updated_at')
				//->whereNotNull('deleted_at')
				->where('id', $user_id)
				->orderBy('username', 'asc')
				->lists('username', 'id','status');
			if(isset($dealersusernames)) {
				$array = json_decode(json_encode($dealersusernames), true);
				if (is_array($array) && isset($array)) {
					$dealersusernames = $array;
				}
			}
			$user  = User::findOrFail($user_id);
			$useremail = $user->email;
			$username = $user->username;
			$user_status = $user->status;
		} else if('admin' == Auth::user()->type || 'commercial' == Auth::user()->type) {
			//$customersusernames = User::where('role_id', 6)->lists('username','id');
			//SELECT users.username, users.id FROM users
			// LEFT JOIN customerscaracts ON customerscaracts.user_id = users.id
			// WHERE customerscaracts.user_id IS NULL AND users.type = 'customer'
			$customersusernames = User::leftJoin('customerscaracts', 'users.id', '=', 'customerscaracts.user_id')
				->whereNull('customerscaracts.user_id')
				->where('users.type', '=', 'customer')
				->orderBy('users.username', 'asc')
				->lists('users.username','users.id','users.status')
				->prepend('Please select', '');
			/*if(count($customersusernames)>1) {
				$customersusernames->prepend('Please select', '');
			}*/
			//$user  = User::findOrFail($user_id);
			//$useremail = $user->email;
			//$username = $user->username;
		} else if('admin' != Auth::user()->type && 'commercial' != Auth::user()->type ||
			('customer' == Auth::user()->type && $user_id != Auth::user()->id ))
		{
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		$status = getEnumValues('dealerscaracts', 'status');

		return view(config('quickadmin.route') . '.customerscaracts.create', compact('customersusernames', 'username', 'useremail', 'user_status', 'countries', 'status'));
	}

	/**
	 * Store a newly created customerscaracts in storage.
	 *
     * @param CreateCustomersCaractsRequest|Request $request
	 */
	public function store(CreateCustomersCaractsRequest $request)
	{
		CustomersCaracts::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.customerscaracts.index');
	}

	/**
	 * Show the form for editing the specified customerscaracts.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$customerscaracts = CustomersCaracts::find($id);

		if(empty($customerscaracts)) {
			return redirect()->route(config('quickadmin.route') . '.customerscaracts.create', ['user_id' => $id])->withInput(['user_id' => $id]);
		}
		if('admin' == Auth::user()->type || 'commercial' == Auth::user()->type ||
			('customer' == Auth::user()->type && Auth::user()->id == $customerscaracts->user_id)) {
			//$user = User::where('id', $customerscaracts->user_id)->pluck('username')->all();
			//$username = $user[0];
			$user  = User::findOrFail($customerscaracts->user_id);
			$useremail = $user->email;
			$username = $user->username;
			$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();
			$country_id = !empty($customerscaracts->country_id) ? $customerscaracts->country_id : '';
			$country_code = !empty($country_id) ? SearchController::getCountryById($country_id, false)['code'] : null;
			$customerscaracts->country_id = $country_code;

			$status = getEnumValues('users', 'status');

			return view(config('quickadmin.route') . '.customerscaracts.edit', compact('user', 'useremail', 'username', 'customerscaracts', 'countries', 'status'));
		} else if('admin' != Auth::user()->type && 'commercial' != Auth::user()->type ||
			('customer' == Auth::user()->type && Auth::user()->id != $customerscaracts->user_id )) {
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
	}

	/**
	 * Update the specified customerscaracts in storage.
     * @param UpdateCustomersCaractsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateCustomersCaractsRequest $request)
	{
		$customerscaracts = CustomersCaracts::findOrFail($id);

		$input = $request->all();

		$country_code = !empty($input['country_id']) ? $input['country_id'] : '';
		$country_id = !empty($country_code) ? SearchController::getCountry($country_code)['id'] : null;
		$input['country_id'] = $country_id;

		$customerscaracts->update($input);

		return redirect()->route(config('quickadmin.route') . '.customerscaracts.index')->withMessage('Customer\'s caracts was successfully updated!');
	}

	/**
	 * Remove the specified customerscaracts from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		CustomersCaracts::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.customerscaracts.index');
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
            CustomersCaracts::destroy($toDelete);
        } else {
            CustomersCaracts::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.customerscaracts.index');
    }

}
