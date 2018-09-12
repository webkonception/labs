<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models;
use App\Http\Requests\CreateModelsRequest;
use App\Http\Requests\UpdateModelsRequest;
use Illuminate\Http\Request;

use App\Manufacturers;

class ModelsController extends Controller {

	/**
	 * Display a listing of models
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request)
	{
		$countModels = [];

		$manufacturerId = '';
        if ($manufacturerId = $request->input('manufacturers_id')) {
            $manufacturers = Manufacturers::/*where('id', $manufacturerId)
                ->*/orderBy('name', 'asc')
                ->lists('name','id')
				->prepend('Please select', '');

			$countModels = Models::where('manufacturers_id', $manufacturerId)->count();

			$models = Models::where('manufacturers_id', $manufacturerId)
				->select(
					'id',
					'manufacturers_id',
					'name',
					'rewrite_url',
					'equivalent',
					'referrer',
					'description',
					'position'
				)
				->orderBy('name', 'asc')
				->get();

        } else if ($referrer = $request->input('referrer')) {
			$manufacturers = Manufacturers::where('referrer', $referrer)
                ->orderBy('name', 'asc')
				->lists('name','id')
				->prepend('Please select', '');

			$countModels = Models::where('referrer', $referrer)->count();

            $start = 0;
            $end = 100;
			$models = Models::where('referrer', $referrer)
				->select(
					'manufacturers_id',
					'name',
					'rewrite_url',
					'equivalent',
					'referrer',
					'description',
					'position'
                )
				->orderBy('name', 'asc')
				->skip($start)
				->take($end)
				->get();
		} else {
			$manufacturers = Manufacturers::orderBy('name', 'asc')
				->lists("name", "id")
				->prepend('Please select', '');
		}
		return view(config('quickadmin.route') . '.models.index', compact('manufacturerId','manufacturers', 'models', 'countModels'));
	}

	/**
	 * Show the form for creating a new models
	 *
	 * @return \Illuminate\View\View
	 */
	public function create(Request $request)
	{
		$countModels = [];
		if ($referrer = $request->input('referrer')) {
			$manufacturers = Manufacturers::where('referrer', $referrer)
				->orderBy('name', 'asc')
				->lists('name','id')
				->prepend('Please select', '');

			$countModels = Models::where('referrer', $referrer)->count();

			$start = 0;
			$end = 100;
			$models = Models::where('referrer', $referrer)
				->select(
					'manufacturers_id',
					'name',
					'rewrite_url',
					'equivalent',
					'referrer',
					'description',
					'position'
				)
				->orderBy('name', 'asc')
				->skip($start)
				->take($end)
				->get();
		} else {
			$manufacturers = Manufacturers::orderBy('name', 'asc')
				->lists("name", "id")
				->prepend('Please select', '');
		}
		return view(config('quickadmin.route') . '.models.create', compact('manufacturers', 'models', 'countModels'));
	}

	/**
	 * Store a newly created models in storage.
	 *
	 * @param CreateModelsRequest|Request $request
	 */
	public function store(CreateModelsRequest $request)
	{
		Models::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.models.index');
	}

    /**
     * Show the form for editing the specified models.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $models = Models::find($id);

        return view(config('quickadmin.route') . '.models.edit', compact('models'));
    }

    /**
     * Show the form for listing the specified models.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($manufacturerId)
    {
        debug('ModelsController@show');
        $manufacturers = Manufacturers::orderBy('name', 'asc')
            ->lists('name','id')
            ->prepend('Please select', '');

        $countModels = Models::where('manufacturers_id', $manufacturerId)->count();

        $models = Models::where('manufacturers_id', $manufacturerId)
			->select(
				'id',
				'manufacturers_id',
				'name',
				'rewrite_url',
				'equivalent',
				'referrer',
				'description',
				'position'
			)
			->orderBy('name', 'asc')
			->get();
        return view(config('quickadmin.route') . '.models.index', compact('manufacturerId', 'manufacturers', 'models', 'countModels'));
    }

	/**
	 * Update the specified models in storage.
	 * @param UpdateModelsRequest|Request $request
	 *
	 * @param  int  $id
	 */
	public function update($id, UpdateModelsRequest $request)
	{
		$models = Models::findOrFail($id);
		$models->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.models.index')->withMessage('Model was successfully updated!');
	}

	/**
	 * Remove the specified models from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Models::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.models.index');
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
			Models::destroy($toDelete);
		} else {
			Models::whereNotNull('id')->delete();
		}

		return redirect()->route(config('quickadmin.route') . '.models.index');
	}

}
