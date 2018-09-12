<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\AdsTypes;
use App\Http\Requests\CreateAdsTypesRequest;
use App\Http\Requests\UpdateAdsTypesRequest;
use Illuminate\Http\Request;

class AdsTypesController extends Controller {

	/**
	 * Display a listing of adstypes
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $adstypes = AdsTypes::all();

		return view(config('quickadmin.route') . '.adstypes.index', compact('adstypes'));
	}

	/**
	 * Show the form for creating a new adstypes
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
		//$status = AdsTypes::$status;
		$status = getEnumValues('adstypes', 'status');

		return view(config('quickadmin.route') . '.adstypes.create', compact('status'));
	}

	/**
	 * Store a newly created adstypes in storage.
	 *
     * @param CreateAdsTypesRequest|Request $request
	 */
	public function store(CreateAdsTypesRequest $request)
	{
		AdsTypes::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.adstypes.index');
	}

	/**
	 * Show the form for editing the specified adstypes.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$adstypes = AdsTypes::find($id);
		//$status = AdsTypes::$status;
		$status = getEnumValues('adstypes', 'status');

		return view(config('quickadmin.route') . '.adstypes.edit', compact('adstypes', 'status'));
	}

	/**
	 * Update the specified adstypes in storage.
     * @param UpdateAdsTypesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateAdsTypesRequest $request)
	{
		$adstypes = AdsTypes::findOrFail($id);

		$adstypes->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.adstypes.index')->withMessage('Ad\'s type was successfully updated!');
	}

	/**
	 * Remove the specified adstypes from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		AdsTypes::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.adstypes.index');
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
            AdsTypes::destroy($toDelete);
        } else {
            AdsTypes::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.adstypes.index');
    }

}
