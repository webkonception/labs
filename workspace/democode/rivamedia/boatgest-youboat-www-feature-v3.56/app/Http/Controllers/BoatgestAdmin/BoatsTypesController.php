<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\BoatsTypes;
use App\Http\Requests\CreateBoatsTypesRequest;
use App\Http\Requests\UpdateBoatsTypesRequest;
use Illuminate\Http\Request;

class BoatsTypesController extends Controller {

	/**
	 * Display a listing of boatstypes
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $boatstypes = BoatsTypes::all();

		return view(config('quickadmin.route') . '.boatstypes.index', compact('boatstypes'));
	}

	/**
	 * Show the form for creating a new boatstypes
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
		//$status = BoatsTypes::$status;
		$status = getEnumValues('boatstypes', 'status');

		return view(config('quickadmin.route') . '.boatstypes.create', compact('status'));
	}

	/**
	 * Store a newly created boatstypes in storage.
	 *
     * @param CreateBoatsTypesRequest|Request $request
	 */
	public function store(CreateBoatsTypesRequest $request)
	{
		BoatsTypes::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.boatstypes.index');
	}

	/**
	 * Show the form for editing the specified boatstypes.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$boatstypes = BoatsTypes::find($id);
		//$status = BoatsTypes::$status;
		$status = getEnumValues('boatstypes', 'status');

		return view(config('quickadmin.route') . '.boatstypes.edit', compact('boatstypes', 'status'));
	}

	/**
	 * Update the specified boatstypes in storage.
     * @param UpdateBoatsTypesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateBoatsTypesRequest $request)
	{
		$boatstypes = BoatsTypes::findOrFail($id);

		$boatstypes->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.boatstypes.index')->withMessage('Boat\'s type was successfully updated!');
	}

	/**
	 * Remove the specified boatstypes from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		BoatsTypes::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.boatstypes.index');
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
            BoatsTypes::destroy($toDelete);
        } else {
            BoatsTypes::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.boatstypes.index');
    }

}
