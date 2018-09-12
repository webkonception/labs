<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Ads;
use App\Http\Requests\CreateAdsRequest;
use App\Http\Requests\UpdateAdsRequest;
use Illuminate\Http\Request;

use App\DealersCaracts;
use App\User;
use App\CountryContracts;
use App\AdsTypes;
use App\Categories;
use App\Subcategories;

class AdsController extends Controller {

	/**
	 * Display a listing of ads
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        //$ads = Ads::all();
        $ads = Ads::with("dealerscaracts")->get();
        $dealers = User::lists('username','id');

        return view(config('quickadmin.route') . '.ads.index', compact('ads', 'dealers'));
	}

	/**
	 * Show the form for creating a new ads
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
		//$dealerscaracts = DealersCaracts::lists("dealer_id", "id")->prepend('Please select', '');
        $dealers =
			//User::where('role_id', 4)
			User::where('type', 'dealer')
			->where('status', 'active')
			//->whereNotNull('created_at')
			//->whereNotNull('updated_at')
			//->whereNotNull('deleted_at')
			->lists('username','id')
			->prepend('Please select', '');

        $country_contracts = CountryContracts::orderBy("reference", "asc")->pluck('reference','id')->prepend('Please select', '')->all();

        $adstypes = AdsTypes::where('status', 'active')->orderBy('name', 'asc')->lists("name", "id")->prepend('Please select', '');
        $categories = Categories::orderBy('name', 'asc')->lists("name", "id")->prepend('Please select', '');
        $subcategories = Subcategories::orderBy('name', 'asc')->lists("name", "id")->prepend('Please select', '');

        //$status = Ads::$status;
        $status = getEnumValues('ads', 'status');

        //return view(config('quickadmin.route') . '.ads.create');
	    //return view(config('quickadmin.route') . '.ads.create', compact('dealers','dealerscaracts', 'country_contracts', 'adstypes, 'categories', 'status'));
	    return view(config('quickadmin.route') . '.ads.create', compact('dealers', 'country_contracts', 'adstypes', 'categories', 'subcategories', 'status'));
	}

	/**
	 * Store a newly created ads in storage.
	 *
     * @param CreateAdsRequest|Request $request
	 */
	public function store(CreateAdsRequest $request)
	{
        $ads = Ads::create($request->all());
		return redirect()->route(config('quickadmin.route') . '.ads.index');
	}

	/**
	 * Show the form for editing the specified ads.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$ads = Ads::find($id);

        $dealer = User::where('id', $ads->dealerscaracts_id)->orderBy('username', 'asc')->lists('username','id');
        $dealer_name = User::where('id', $ads->dealerscaracts_id)->orderBy('username', 'asc')->lists('username');

        $country_contracts = CountryContracts::orderBy("reference", "asc")->pluck('reference','id')->prepend('Please select', '')->all();

        $adstypes = AdsTypes::orderBy('name', 'asc')->lists("name", "id");
        $categories = Categories::orderBy('name', 'asc')->lists("name", "id");
        $subcategories = Subcategories::orderBy('name', 'asc')->lists("name", "id");

        //$status = Ads::$status;
        $status = getEnumValues('ads', 'status');

        return view(config('quickadmin.route') . '.ads.edit', compact('ads', 'dealer', 'dealer_name', 'country_contracts', 'adstypes', 'categories', 'subcategories', 'options', 'status'));
	}

	/**
	 * Update the specified ads in storage.
     * @param UpdateAdsRequest|Request $request
     *
	 * @param  int  $id
	 */
	//public function update($id, UpdateAdsRequest $request, Categories $categories)
	public function update($id, UpdateAdsRequest $request)
	{
		$ads = Ads::findOrFail($id);

        $ads->update($request->all());

        //$ads->categories()->sync($request->input('country_contracts_ids'));
        //$ads->syncCategories($categories, $request->input('country_contracts_ids'));

		return redirect()->route(config('quickadmin.route') . '.ads.index')->withMessage('Ad was successfully updated!');
	}

    /*
     * Sync up the list of categories in the database
     * @param AdsRequest $request
     * @param Categories $categories
     */
    /*private function syncCategories(Categories $categories, array $categories) {
        $ads->categories()->sync($categories);
    }*/

	/**
	 * Remove the specified ads from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Ads::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.ads.index');
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
            Ads::destroy($toDelete);
        } else {
            Ads::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.ads.index');
    }

}
