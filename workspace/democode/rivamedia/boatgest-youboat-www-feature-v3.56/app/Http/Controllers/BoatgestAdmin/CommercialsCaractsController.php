<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchController;
use Redirect;
use Schema;
use App\CommercialsCaracts;
use App\Http\Requests\CreateCommercialsCaractsRequest;
use App\Http\Requests\UpdateCommercialsCaractsRequest;
use Illuminate\Http\Request;

use App\User;
use App\Countries;

use Auth;

class CommercialsCaractsController extends Controller {

	/**
	 * Display a listing of commercialscaracts
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
		if('admin' != Auth::user()->type) {
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
		$commercialscaracts = CommercialsCaracts::all();
		//$commercialsusernames = User::where('role_id', 5)->lists('username','id');
		$commercialsusernames = User::where('type', 'commercial')
			//->where('status', 'active')
			//->whereNotNull('created_at')
			//->whereNotNull('updated_at')
			//->whereNotNull('deleted_at')
			->orderBy('username', 'asc')
			->lists('username','id');

		if(isset($commercialsusernames)) {
			$array = json_decode(json_encode($commercialsusernames), true);
			if (is_array($array) && isset($array)) {
				$commercialsusernames = $array;
			}
		}
		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		return view(config('quickadmin.route') . '.commercialscaracts.index', compact('commercialscaracts', 'commercialsusernames', 'countries'));
	}

	/**
	 * Show the form for creating a new commercialscaracts
	 *
     * @return \Illuminate\View\View
	 */
	public function create(Request $request)
	{
		$useremail = '';
		$username = '';
		$input = $request->all();
		$user_id = !empty($input['user_id']) ? $input['user_id'] : null;

		if('commercial' == Auth::user()->type && Auth::user()->id == $user_id) {
			$commercialsusernames = User::where('type', 'commercial')
				//->where('status', 'active')
				//->whereNotNull('created_at')
				//->whereNotNull('updated_at')
				//->whereNotNull('deleted_at')
				->where('id',$user_id)
				->orderBy('username', 'asc')
				->lists('username','id');
			if(isset($commercialsusernames)) {
				$array = json_decode(json_encode($commercialsusernames), true);
				if (is_array($array) && isset($array)) {
					$commercialsusernames = $array;
				}
			}
			$user  = User::findOrFail($user_id);
			$useremail = $user->email;
			$username = $user->username;
			//$username = Auth::user()->username;
			//$useremail = Auth::user()->email;
		} else if('admin' == Auth::user()->type) {
			//$commercialsusernames = User::where('role_id', 5)->lists('username','id');
			//SELECT users.username, users.id FROM users
			// LEFT JOIN commercialscaracts ON commercialscaracts.user_id = users.id
			// WHERE commercialscaracts.user_id IS NULL AND users.type = 'commercial'
			$commercialsusernames = User::leftJoin('commercialscaracts', 'users.id', '=', 'commercialscaracts.user_id')
				->whereNull('commercialscaracts.user_id')
				->where('users.type', '=', 'commercial')
				->orderBy('users.username', 'asc')
				->lists('users.username','users.id')
				->prepend('Please select', '');
			//$user  = User::findOrFail($user_id);
			//$useremail = $user->email;
			//$username = $user->username;
		} else if('admin' != Auth::user()->type ||
			('commercial' == Auth::user()->type && $user_id != Auth::user()->id ))
		{
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		return view(config('quickadmin.route') . '.commercialscaracts.create', compact('commercialsusernames', 'user_id', 'username', 'useremail', 'countries'));
	}

	/**
	 * Store a newly created commercialscaracts in storage.
	 *
     * @param CreateCommercialsCaractsRequest|Request $request
	 */
	public function store(CreateCommercialsCaractsRequest $request)
	{
		CommercialsCaracts::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.commercialscaracts.index');
	}

	/**
	 * Show the form for editing the specified commercialscaracts.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$commercialscaracts = CustomersCaracts::find($id);
		if(empty($commercialscaracts)) {
			//return redirect()->route(config('quickadmin.route') . '.commercialscaracts.create', [])->withInput(['user_id' => $id]);
			return redirect()->route(config('quickadmin.route') . '.commercialscaracts.index');
		}

		if('admin' == Auth::user()->type ||
			('commercial' == Auth::user()->type && Auth::user()->id == $commercialscaracts->user_id)) {
			//$user = User::where('id', $commercialscaracts->user_id)->pluck('username')->all();
			//$username = $user[0];
			$user  = User::findOrFail($commercialscaracts->user_id);
			$useremail = $user->email;
			$username = $user->username;
			$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();
			$country_id = !empty($commercialscaracts->country_id) ? $commercialscaracts->country_id : '';
			$country_code = !empty($country_id) ? SearchController::getCountryById($country_id, false)['code'] : null;
			$commercialscaracts->country_id = $country_code;

			//$status = getEnumValues('users', 'status');

			return view(config('quickadmin.route') . '.commercialscaracts.edit', compact('user', 'useremail', 'username', 'commercialscaracts', 'countries'));
		} else if('admin' != Auth::user()->type ||
			('commercial' == Auth::user()->type && Auth::user()->id != $commercialscaracts->user_id )) {
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
	}

	/**
	 * Update the specified commercialscaracts in storage.
     * @param UpdateCommercialsCaractsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateCommercialsCaractsRequest $request)
	{
		$commercialscaracts = CommercialsCaracts::findOrFail($id);

		$input = $request->all();

		$country_code = !empty($input['country_id']) ? $input['country_id'] : '';
		$country_id = !empty($country_code) ? SearchController::getCountry($country_code)['id'] : null;
		$input['country_id'] = $country_id;

		$commercialscaracts->update($input);

		return redirect()->route(config('quickadmin.route') . '.commercialscaracts.index')->withMessage('Commercial\'s caracts was successfully updated!');
	}

	/**
	 * Remove the specified commercialscaracts from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		CommercialsCaracts::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.commercialscaracts.index');
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
            CommercialsCaracts::destroy($toDelete);
        } else {
            CommercialsCaracts::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.commercialscaracts.index');
    }

}
