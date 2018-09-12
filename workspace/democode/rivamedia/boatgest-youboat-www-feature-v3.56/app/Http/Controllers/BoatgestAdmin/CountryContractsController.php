<?php namespace App\Http\Controllers\boatgestAdmin;

use App\CommercialsCaracts;
use App\DealersCaracts;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchController as Search;

use Redirect;
use Schema;
use DB;
use App\CountryContracts;
use App\Http\Requests\CreateCountryContractsRequest;
use App\Http\Requests\UpdateCountryContractsRequest;
use App\Http\Requests\UpdateAdsCaractsRequest;
use Illuminate\Http\Request;

use App\User;
use App\Countries;
use App\AdsCaracts;
use Auth;

class CountryContractsController extends Controller {

	/**
	 * Display a listing of countrycontracts
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $countrycontracts = CountryContracts::all();

		$dealersusernames =
			//User::where('role_id', 4)
			User::where('type', 'dealer')
				//->where('status', 'active')
				->orderBy('username', 'asc')
				->lists('username','id')->toArray();

		$commercialsusernames =
			//User::where('role_id', 5)
			User::where('type', 'commercial')
				->where('status', 'active')
				->orderBy('username', 'asc')
				->lists('username','id')->toArray();

		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();
		return view(config('quickadmin.route') . '.countrycontracts.index', compact('countrycontracts', 'dealersusernames', 'commercialsusernames', 'countries'));
	}

	/**
	 * Show the form for creating a new countrycontracts
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
		$dealersusernames =
			//User::where('role_id', 4)
			User::where('type', 'dealer')
				//->where('status', 'active')
				->orderBy('username', 'asc')
				->lists('username','id')
				->prepend('Please select', '')->toArray();
		if('commercial' == Auth::user()->type) {
			$commercialsusernames = User::where('type', 'commercial')
				//->whereNotNull('created_at')
				//->whereNotNull('updated_at')
				//->whereNotNull('deleted_at')
				->where('id', Auth::user()->id)
				->orderBy('username', 'asc')
				->lists('username','id')->toArray();
			$username = Auth::user()->username;
			$email = Auth::user()->email;
		} else if('admin' == Auth::user()->type) {$commercialsusernames = User::where('type', 'commercial')
			->orderBy('username', 'asc')
			->lists('username','id')
			->prepend('Please select', '');
		} else if('admin' != Auth::user()->type || 'commercial' != Auth::user()->type)
		{
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		//$references = array_prepend(getEnumValues('countrycontracts', 'reference'),'Please select','');
		$references = CountryContracts::orderBy('reference', 'asc')->lists('reference','id');

		//$status = CountryContracts::$status;
		$status = getEnumValues('countrycontracts', 'status');

	    return view(config('quickadmin.route') . '.countrycontracts.create', compact('dealersusernames', 'commercialsusernames', 'countries', 'references', 'status'));
	}

	/**
	 * Store a newly created countrycontracts in storage.
	 *
     * @param CreateCountryContractsRequest|Request $request
	 */
	public function store(CreateCountryContractsRequest $request)
	{
		$input = $request->all();
		$countries_ids= [];
		$array = $input['countries_ids'];
		foreach($array as $key => $country_id) {
			$getCountry = Search::getCountry($country_id);
			if(is_array($getCountry) && array_key_exists('id', $getCountry)) {
				$countries_ids[] =  $getCountry['id'];
			}
		}
		$input['countries_ids'] = implode(';',$countries_ids);

		if($countrycontracts = CountryContracts::create($input)) {
			// Update countrycontracts_id on dealers's ads
			$dealerAds = AdsCaracts::where('dealerscaracts_id', '=', $input['dealerscaracts_id'])->update(['countrycontracts_id'=>$countrycontracts->id]);
		} else {
			// return error
		}

		return redirect()->route(config('quickadmin.route') . '.countrycontracts.index');
	}

	/**
	 * Show the form for editing the specified countrycontracts.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$countrycontracts = CountryContracts::find($id);

		$countrycontracts_user_id = $countrycontracts->user_id;

		/*$dealersusernames =
			//User::where('role_id', 4)
			User::where('type', 'dealer')
				//->where('status', 'active')
				->orderBy('username', 'asc')
				->lists('username','id')
				->prepend('Please select', '')->toArray();*/

		$dealersusernames = User::join('dealerscaracts', 'users.id', '=', 'dealerscaracts.user_id')
			->where('type', 'dealer')
			->orderBy('denomination', 'asc')
			->lists('dealerscaracts.denomination','dealerscaracts.id')
			->prepend('Please select', '')
			->toArray();

		//$dealerscaracts_id = $countrycontracts->dealerscaracts_id;
		//$dealerName = Search::getDealerCaractsById([$countrycontracts->dealerscaracts_id]);
		//$DealerCaracts = Search::getDealerCaractsById($dealerscaracts_id);
		//$dealer_user_id = $DealerCaracts['user_id'];
		$dealer_user_id = $countrycontracts_user_id;
		//$dealer_user_id = $dealerscaracts_id;

		/*$commercialsusernames =
			//User::where('role_id', 5)
			User::where('type', 'commercial')
				//->where('status', 'active')
				->orderBy('username', 'asc')
				->lists('username','id')
				->prepend('Please select', '')->toArray();*/

		$commercialsusernames = User::join('commercialscaracts', 'users.id', '=', 'commercialscaracts.user_id')
				->where('type', 'commercial')
				->select('commercialscaracts.id AS id', DB::raw('CONCAT(commercialscaracts.firstname, " ", commercialscaracts.name) AS full_name'))
				->orderBy('full_name', 'asc')
				->lists('full_name','id')
				->prepend('Please select', '')->toArray();

		//$commercialscaracts_id = $countrycontracts->commercialscaracts_id;
		//$CommercialCaracts = Search::getCommercialCaractsById($commercialscaracts_id);
		//$commercial_user_id = is_array($CommercialCaracts) && array_key_exists('user_id', $CommercialCaracts) ? $CommercialCaracts['user_id'] : '';

		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		//$references = array_prepend(getEnumValues('countrycontracts', 'reference'),'Please select','');
		$references = CountryContracts::orderBy('reference', 'asc')->lists('reference','id');

		//$status = CountryContracts::$status;
		$status = getEnumValues('countrycontracts', 'status');

		return view(config('quickadmin.route') . '.countrycontracts.edit', compact('countrycontracts', 'dealersusernames', 'dealer_user_id',
        'commercialsusernames', 'commercial_user_id', 'countries', 'references', 'status'));
	}

	/**
	 * Update the specified countrycontracts in storage.
     * @param UpdateCountryContractsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateCountryContractsRequest $request)
	{
		$input = $request->all();
		$countries_ids= [];
		$array = $input['countries_ids'];
		foreach($array as $key => $country_id) {
			$getCountry = Search::getCountry($country_id);
			if(is_array($getCountry) && array_key_exists('id', $getCountry)) {
				$countries_ids[] =  $getCountry['id'];
			}
		}
		$input['countries_ids'] = implode(';',$countries_ids);

		$countrycontracts = CountryContracts::findOrFail($id);
		if($countrycontracts->update($input)) {
			// Update countrycontracts_id on dealers's ads
			$dealerAds = AdsCaracts::where('dealerscaracts_id', '=', $input['dealerscaracts_id'])->update(['countrycontracts_id' => $countrycontracts->id]);
		} else {
			// return error
		}

		return redirect()->route(config('quickadmin.route') . '.countrycontracts.index')->withMessage('Country\'s contracts was successfully updated!');
	}

	/**
	 * Remove the specified countrycontracts from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		CountryContracts::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.countrycontracts.index');
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
            CountryContracts::destroy($toDelete);
        } else {
            CountryContracts::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.countrycontracts.index');
    }

}
