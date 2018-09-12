<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Options;
use App\Http\Requests\CreateOptionsRequest;
use App\Http\Requests\UpdateOptionsRequest;
use Illuminate\Http\Request;

class OptionsController extends Controller {

	/**
	 * Display a listing of options
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $options = Options::all();

		return view(config('quickadmin.route') . '.options.index', compact('options'));
	}

	/**
	 * Show the form for creating a new options
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view(config('quickadmin.route') . '.options.create');
	}

	/**
	 * Store a newly created options in storage.
	 *
     * @param CreateOptionsRequest|Request $request
	 */
	public function store(CreateOptionsRequest $request)
	{
	    
		Options::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.options.index');
	}

	/**
	 * Show the form for editing the specified options.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$options = Options::find($id);
	    
	    
		return view(config('quickadmin.route') . '.options.edit', compact('options'));
	}

	/**
	 * Update the specified options in storage.
     * @param UpdateOptionsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateOptionsRequest $request)
	{
		$options = Options::findOrFail($id);

        

		$options->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.options.index')->withMessage('Options was successfully updated!');
	}

	/**
	 * Remove the specified options from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Options::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.options.index');
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
            Options::destroy($toDelete);
        } else {
            Options::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.options.index');
    }

}
