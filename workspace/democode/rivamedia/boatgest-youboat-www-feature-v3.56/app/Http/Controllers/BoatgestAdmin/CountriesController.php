<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Countries;
use App\Http\Requests\CreateCountriesRequest;
use App\Http\Requests\UpdateCountriesRequest;
use Illuminate\Http\Request;

class CountriesController extends Controller {

	/**
	 * Display a listing of countries
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $countries = Countries::all();

		return view(config('quickadmin.route') . '.countries.index', compact('countries'));
	}

	/**
	 * Show the form for creating a new countries
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    return view(config('quickadmin.route') . '.countries.create');
	}

	/**
	 * Store a newly created countries in storage.
	 *
     * @param CreateCountriesRequest|Request $request
	 */
	public function store(CreateCountriesRequest $request)
	{
		Countries::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.countries.index');
	}

	/**
	 * Show the form for editing the specified countries.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$countries = Countries::find($id);
	    
		return view(config('quickadmin.route') . '.countries.edit', compact('countries'));
	}

	/**
	 * Update the specified countries in storage.
     * @param UpdateCountriesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateCountriesRequest $request)
	{
		$countries = Countries::findOrFail($id);
		$countries->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.countries.index')->withMessage('Country was successfully updated!');
	}

	/**
	 * Remove the specified countries from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Countries::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.countries.index');
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
            Countries::destroy($toDelete);
        } else {
            Countries::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.countries.index');
    }

}
