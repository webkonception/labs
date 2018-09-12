<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Categories;
use App\Http\Requests\CreateCategoriesRequest;
use App\Http\Requests\UpdateCategoriesRequest;
use Illuminate\Http\Request;

use App\AdsTypes;

class CategoriesController extends Controller {

	/**
	 * Display a listing of categories
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
		// SELECT `categories`.`id`,`categories`.`adstypes_id`,`categories`.`name`,`categories`.`description`,`categories`.`rewrite_url`,`categories`.`position` FROM `categories`
		// JOIN `adstypes` where `categories`.`adstypes_id` = `adstypes`.`id`
		// AND WHERE `adstypes`.`status` = 'active'

		//$categories = Categories::all();
		$categories = Categories::with("adstypes")
			->join('adstypes', 'categories.adstypes_id', '=', 'adstypes.id')
			->where('adstypes.status', '=', 'active')
			->select('categories.id','categories.adstypes_id','categories.name','categories.description','categories.rewrite_url','categories.position')
			->get();

		return view(config('quickadmin.route') . '.categories.index', compact('categories'));
	}

	/**
	 * Show the form for creating a new categories
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
		$adstypes = AdsTypes::orderBy('name', 'asc')->lists("name", "id")->prepend('Please select', '');
	    
	    return view(config('quickadmin.route') . '.categories.create', compact("adstypes"));
	}

	/**
	 * Store a newly created categories in storage.
	 *
     * @param CreateCategoriesRequest|Request $request
	 */
	public function store(CreateCategoriesRequest $request)
	{
		Categories::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.categories.index');
	}

	/**
	 * Show the form for editing the specified categories.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$categories = Categories::find($id);
		$adstypes = AdsTypes::orderBy('name', 'asc')->lists("name", "id")->prepend('Please select', '');
	    
		return view(config('quickadmin.route') . '.categories.edit', compact('categories', 'adstypes'));
	}

	/**
	 * Update the specified categories in storage.
     * @param UpdateCategoriesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateCategoriesRequest $request)
	{
		$categories = Categories::findOrFail($id);
		$categories->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.categories.index')->withMessage('Category was successfully updated!');
	}

	/**
	 * Remove the specified categories from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Categories::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.categories.index');
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
            Categories::destroy($toDelete);
        } else {
            Categories::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.categories.index');
    }

}
