<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Vat;
use App\Http\Requests\CreateVatRequest;
use App\Http\Requests\UpdateVatRequest;
use Illuminate\Http\Request;

class VatController extends Controller {

	/**
	 * Display a listing of vat
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $vat = Vat::all();

		return view(config('quickadmin.route') . '.vat.index', compact('vat'));
	}

	/**
	 * Show the form for creating a new vat
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    return view(config('quickadmin.route') . '.vat.create');
	}

	/**
	 * Store a newly created vat in storage.
	 *
     * @param CreateVatRequest|Request $request
	 */
	public function store(CreateVatRequest $request)
	{
		Vat::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.vat.index');
	}

	/**
	 * Show the form for editing the specified vat.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$vat = Vat::find($id);
	    
		return view(config('quickadmin.route') . '.vat.edit', compact('vat'));
	}

	/**
	 * Update the specified vat in storage.
     * @param UpdateVatRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateVatRequest $request)
	{
		$vat = Vat::findOrFail($id);
		$vat->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.vat.index')->withMessage('Vat was successfully updated!');
	}

	/**
	 * Remove the specified vat from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Vat::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.vat.index');
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
            Vat::destroy($toDelete);
        } else {
            Vat::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.vat.index');
    }

}
