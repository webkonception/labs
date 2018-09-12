<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\ScrappingAdsDetails;
use App\Http\Requests\CreateScrappingAdsDetailsRequest;
use App\Http\Requests\UpdateScrappingAdsDetailsRequest;
use Illuminate\Http\Request;

class ScrappingAdsDetailsController extends Controller {

	/**
	 * Display a listing of scrapping_ads_details
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        //$scrapping_ads_details = ScrappingAdsDetails::all();
		$scrapping_ads_details = ScrappingAdsDetails::
			//where('referrer', $referrer)->
			//select(DB::raw("id, name, rewrite_url, equivalent, referrer, position, (SELECT count(*) from models WHERE manufacturers.id = models.manufacturers_id) models_count"))
			select(
				'id',
				'ad_ref',
				'ad_title',
				'ad_pageUrl',
				'ad_referrer',
				'ad_type_cat_url',
				'ad_type_cat_name',

				'ad_manufacturer_name',
				//'ad_manufacturer_url',

				'ad_model_name',
				//'ad_model_url',

//				'ad_price',
//				'ad_price_descr',

				'ad_location',
				//'ad_mooring_country',

				'ad_dealer_name',
				//'ad_dealer_url',

//				'ad_sale',
				'ad_sale_type_condition',
				'ad_sales_status'
//				,'ad_year_built'
				)
			->orderBy('id', 'asc')
			->get();

		return view(config('quickadmin.route') . '.scrappingadsdetails.index', compact('scrapping_ads_details'));
	}

	/**
	 * Show the form for creating a new scrapping_ads_details
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    return view(config('quickadmin.route') . '.scrappingadsdetails.create');
	}

	/**
	 * Store a newly created scrapping_ads_details in storage.
	 *
     * @param CreateScrappingAdsDetailsRequest|Request $request
	 */
	public function store(CreateScrappingAdsDetailsRequest $request)
	{
		ScrappingAdsDetails::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.scrappingadsdetails.index');
	}

	/**
	 * Show the form for editing the specified scrappingadsdetails.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$scrapping_ads_details = ScrappingAdsDetails::find($id);
	    
		return view(config('quickadmin.route') . '.scrappingadsdetails.edit', compact('scrapping_ads_details'));
	}

	/**
	 * Update the specified scrapping_ads_details in storage.
     * @param UpdateScrappingAdsDetailsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateScrappingAdsDetailsRequest $request)
	{
		$scrapping_ads_details = ScrappingAdsDetails::findOrFail($id);
		$scrapping_ads_details->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.scrappingadsdetails.index');
	}

	/**
	 * Remove the specified scrapping_ads_details from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		ScrappingAdsDetails::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.scrappingadsdetails.index');
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
            ScrappingAdsDetails::destroy($toDelete);
        } else {
            ScrappingAdsDetails::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.scrappingadsdetails.index');
    }

}
