<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\ProductsModels;
use App\Http\Requests\CreateProductsModelsRequest;
use App\Http\Requests\UpdateProductsModelsRequest;
use Illuminate\Http\Request;

use Manufacturers;

class ProductsModelsController extends Controller {

	/**
	 * Display a listing of productsmodels
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        //$productsmodels = ProductsModels::all();

        /*$productsmodels = ProductsModels::lists('manufacturers_id',
			'name',
			'rewrite_url',
			'equivalent',
			'referrer',
			'description',
			'position');*/
		/*$productsmodels = ProductsModels::where('referrer', 'Rivamedia')->lists('manufacturers_id',
			'name',
			'rewrite_url',
			'equivalent',
			'referrer',
			'description',
			'position');*/
		if (array_key_exists('manufacturer_id', $request)) {
			$manufacturers = Manufacturers::where('id', $request['manufacturer_id'])
				->orderBy('name', 'asc')
				->lists('name','id')
				->prepend('Please select', '');
		}

		$productsmodels = ProductsModels::where('referrer', 'Rivamedia')
			->select('manufacturers_id',
			'name',
			'rewrite_url',
			'equivalent',
			'referrer',
			'description',
			'position')
			->orderBy('name', 'asc')
			->get();
		/*$product = [];
		$productsmodels = ProductsModels::chunk(200, function ($productsmodels) {
			foreach ($productsmodels as $models) {
				//
				$product[] = $models;
				debug($models);
			}
		});*/

		return view(config('quickadmin.route') . '.productsmodels.index', compact('productsmodels'));
	}

	/**
	 * Show the form for creating a new productsmodels
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    return view(config('quickadmin.route') . '.productsmodels.create');
	}

	/**
	 * Store a newly created productsmodels in storage.
	 *
     * @param CreateProductsModelsRequest|Request $request
	 */
	public function store(CreateProductsModelsRequest $request)
	{
		ProductsModels::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.productsmodels.index');
	}

	/**
	 * Show the form for editing the specified productsmodels.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$productsmodels = ProductsModels::find($id);
	    
		return view(config('quickadmin.route') . '.productsmodels.edit', compact('productsmodels'));
	}

	/**
	 * Update the specified productsmodels in storage.
     * @param UpdateProductsModelsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateProductsModelsRequest $request)
	{
		$productsmodels = ProductsModels::findOrFail($id);
		$productsmodels->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.productsmodels.index')->withMessage('Product Model was successfully updated!');
	}

	/**
	 * Remove the specified productsmodels from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		ProductsModels::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.productsmodels.index');
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
            ProductsModels::destroy($toDelete);
        } else {
            ProductsModels::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.productsmodels.index');
    }

}
