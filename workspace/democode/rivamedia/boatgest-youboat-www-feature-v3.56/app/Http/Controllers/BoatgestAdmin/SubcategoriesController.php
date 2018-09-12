<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Subcategories;
use App\Http\Requests\CreateSubcategoriesRequest;
use App\Http\Requests\UpdateSubcategoriesRequest;
use Illuminate\Http\Request;

use App\Categories;

class SubcategoriesController extends Controller {

	/**
	 * Display a listing of subcategories
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $subcategories = Subcategories::with("categories")->get();

		return view(config('quickadmin.route') . '.subcategories.index', compact('subcategories'));
	}

	/**
	 * Show the form for creating a new subcategories
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $categories = Categories::orderBy('name', 'asc')->lists("name", "id")->prepend('Please select', '');
	    
	    return view(config('quickadmin.route') . '.subcategories.create', compact("categories"));
	}

	/**
	 * Store a newly created subcategories in storage.
	 *
     * @param CreateSubcategoriesRequest|Request $request
	 */
	public function store(CreateSubcategoriesRequest $request)
	{
		Subcategories::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.subcategories.index');
	}

	/**
	 * Show the form for editing the specified subcategories.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$subcategories = Subcategories::find($id);
	    $categories = Categories::orderBy('name', 'asc')->lists("name", "id")->prepend('Please select', '');
	    
		return view(config('quickadmin.route') . '.subcategories.edit', compact('subcategories', 'categories'));
	}

	/**
	 * Update the specified subcategories in storage.
     * @param UpdateSubcategoriesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateSubcategoriesRequest $request)
	{
		$subcategories = Subcategories::findOrFail($id);
		$subcategories->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.subcategories.index')->withMessage('Subcategory was successfully updated!');
	}

	/**
	 * Remove the specified subcategories from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Subcategories::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.subcategories.index');
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
            Subcategories::destroy($toDelete);
        } else {
            Subcategories::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.subcategories.index');
    }

}
