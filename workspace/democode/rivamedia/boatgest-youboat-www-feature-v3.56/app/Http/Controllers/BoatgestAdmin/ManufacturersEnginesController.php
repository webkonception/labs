<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\ManufacturersEngines;
use App\Http\Requests\CreateManufacturersEnginesRequest;
use App\Http\Requests\UpdateManufacturersEnginesRequest;
use Illuminate\Http\Request;
use DB;
class ManufacturersEnginesController extends Controller {

	/**
	 * Display a listing of manufacturersengines
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request)
	{
		if ($referrer = $request->input('referrer')) {
			$manufacturersengines = ManufacturersEngines::where('referrer', $referrer)
				->select(DB::raw("id, name, rewrite_url, equivalent, referrer, position, (SELECT count(*) from modelsengines WHERE manufacturersengines.id = modelsengines.manufacturersengines_id) modelsengines_count"))
				->orderBy('name', 'asc')
				->get();
		} else {
			//$manufacturersengines = ManufacturersEngines::all();
			$manufacturersengines = ManufacturersEngines::select(DB::raw("id, name, rewrite_url, equivalent, referrer, position, (SELECT count(*) from modelsengines WHERE manufacturersengines.id = modelsengines.manufacturersengines_id) modelsengines_count"))
				->orderBy('name', 'asc')
				->get();
		}

		return view(config('quickadmin.route') . '.manufacturersengines.index', compact('manufacturersengines' ,'request'));
	}

	/**
	 * Show the form for creating a new manufacturersengines
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view(config('quickadmin.route') . '.manufacturersengines.create');
	}

	/**
	 * Store a newly created manufacturersengines in storage.
	 *
	 * @param CreateManufacturersEnginesRequest|Request $request
	 */
	public function store(CreateManufacturersEnginesRequest $request)
	{
		ManufacturersEngines::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.manufacturersengines.index');
	}

	/**
	 * Show the form for editing the specified manufacturersengines.
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$manufacturersengines = ManufacturersEngines::find($id);

		return view(config('quickadmin.route') . '.manufacturersengines.edit', compact('manufacturersengines'));
	}

	/**
	 * Update the specified manufacturersengines in storage.
	 * @param UpdateManufacturersEnginesRequest|Request $request
	 *
	 * @param  int  $id
	 */
	public function update($id, UpdateManufacturersEnginesRequest $request)
	{
		$manufacturersengines = ManufacturersEngines::findOrFail($id);
		$manufacturersengines->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.manufacturersengines.index')->withMessage('Manufacturer Engine was successfully updated!');
	}

	/**
	 * Remove the specified manufacturersengines from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		ManufacturersEngines::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.manufacturersengines.index');
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
			ManufacturersEngines::destroy($toDelete);
		} else {
			ManufacturersEngines::whereNotNull('id')->delete();
		}

		return redirect()->route(config('quickadmin.route') . '.manufacturersengines.index');
	}

}
