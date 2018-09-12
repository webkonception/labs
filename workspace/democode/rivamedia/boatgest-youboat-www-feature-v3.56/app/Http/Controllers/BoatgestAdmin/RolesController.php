<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Roles;
use App\Http\Requests\CreateRolesRequest;
use App\Http\Requests\UpdateRolesRequest;
use Illuminate\Http\Request;

class RolesController extends Controller {

	/**
	 * Display a listing of roles
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $roles = Roles::all();

		return view(config('quickadmin.route') . '.roles.index', compact('roles'));
	}

	/**
	 * Show the form for creating a new roles
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view(config('quickadmin.route') . '.roles.create');
	}

	/**
	 * Store a newly created roles in storage.
	 *
     * @param CreateRolesRequest|Request $request
	 */
	public function store(CreateRolesRequest $request)
	{
	    
		Roles::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.roles.index');
	}

	/**
	 * Show the form for editing the specified roles.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$roles = Roles::find($id);
	    
	    
		return view(config('quickadmin.route') . '.roles.edit', compact('roles'));
	}

	/**
	 * Update the specified roles in storage.
     * @param UpdateRolesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateRolesRequest $request)
	{
		$roles = Roles::findOrFail($id);

        

		$roles->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.roles.index')->withMessage('Role was successfully updated!');
	}

	/**
	 * Remove the specified roles from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Roles::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.roles.index');
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
            Roles::destroy($toDelete);
        } else {
            Roles::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.roles.index');
    }

}
