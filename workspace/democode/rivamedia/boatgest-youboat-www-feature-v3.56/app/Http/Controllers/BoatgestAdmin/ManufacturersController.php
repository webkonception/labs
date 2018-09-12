<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Manufacturers;
use App\Http\Requests\CreateManufacturersRequest;
use App\Http\Requests\UpdateManufacturersRequest;
use Illuminate\Http\Request;
use DB;
class ManufacturersController extends Controller {

	/**
	 * Display a listing of manufacturers
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
		if ($referrer = $request->input('referrer')) {
			$manufacturers = Manufacturers::where('referrer', $referrer)
				->select(DB::raw("id, name, rewrite_url, equivalent, referrer, position, (SELECT count(*) from models WHERE manufacturers.id = models.manufacturers_id) models_count"))
				->orderBy('name', 'asc')
				->get();
		} else {
			//$manufacturers = Manufacturers::all();
			$manufacturers = Manufacturers::select(DB::raw("id, name, rewrite_url, equivalent, referrer, position, (SELECT count(*) from models WHERE manufacturers.id = models.manufacturers_id) models_count"))
				->orderBy('name', 'asc')
				->get();
		}

		return view(config('quickadmin.route') . '.manufacturers.index', compact('manufacturers' ,'request'));
	}

	/**
	 * Show the form for creating a new manufacturers
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    return view(config('quickadmin.route') . '.manufacturers.create');
	}

	/**
	 * Store a newly created manufacturers in storage.
	 *
     * @param CreateManufacturersRequest|Request $request
	 */
	public function store(CreateManufacturersRequest $request)
	{
		Manufacturers::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.manufacturers.index');
	}

	/**
	 * Show the form for editing the specified manufacturers.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$manufacturers = Manufacturers::find($id);

		return view(config('quickadmin.route') . '.manufacturers.edit', compact('manufacturers'));
	}

	/**
	 * Update the specified manufacturers in storage.
     * @param UpdateManufacturersRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateManufacturersRequest $request)
	{
		$manufacturers = Manufacturers::findOrFail($id);
		$manufacturers->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.manufacturers.index')->withMessage('Manufacturer was successfully updated!');
	}

	/**
	 * Remove the specified manufacturers from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Manufacturers::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.manufacturers.index');
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
            Manufacturers::destroy($toDelete);
        } else {
            Manufacturers::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.manufacturers.index');
    }

}
