<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\ModelsEngines;
use App\Http\Requests\CreateModelsEnginesRequest;
use App\Http\Requests\UpdateModelsEnginesRequest;
use Illuminate\Http\Request;

use App\ManufacturersEngines;

class ModelsEnginesController extends Controller {

	/**
	 * Display a listing of modelsengines
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request)
	{
		//$modelsengines = ModelsEngines::all();
		/*$modelsengines = ModelsEngines::select(
				'id',
				'manufacturersengines_id',
				'name',
				'rewrite_url',
				'equivalent',
				'referrer',
				'description',
				'position'
			)->get();*/

		//return view(config('quickadmin.route') . '.modelsengines.index', compact('modelsengines'));
		$manufacturersenginesId = '';
        if ($manufacturersenginesId = $request->input('manufacturersengines_id')) {
            $manufacturersengines = ManufacturersEngines::/*where('id', $manufacturersenginesId)
                ->*/orderBy('name', 'asc')
                ->lists('name','id')
				->prepend('Please select', '');

			$countModelsEngines = ModelsEngines::where('manufacturersengines_id', $manufacturersenginesId)->count();

			$modelsengines = ModelsEngines::where('manufacturersengines_id', $manufacturersenginesId)
				->select(
					'id',
					'manufacturersengines_id',
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
			$manufacturersengines = ManufacturersEngines::where('referrer', $referrer)
                ->orderBy('name', 'asc')
				->lists('name','id')
				->prepend('Please select', '');

			$countModelsEngines = ModelsEngines::where('referrer', $referrer)->count();

            $start = 0;
            $end = 100;
			$modelsengines = ModelsEngines::where('referrer', $referrer)
				->select(
					'manufacturersengines_id',
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
            /*$modelsengines = ModelsEngines::where('referrer', $referrer)
                    ->pluck(
                        'manufacturersengines_id',
                        'name',
                        'rewrite_url',
                        'equivalent',
                        'referrer',
                        'description',
                        'position'
                    )
                    ->skip($start)->take($end)->get();*/
            /*$modelsengines[] = ModelsEngines::where('referrer', $referrer)
                ->pluck(
                    'id'
                );
            $modelsengines[] = ModelsEngines::where('referrer', $referrer)
                ->pluck(
                    'manufacturersengines_id'
                );
            $modelsengines[] = ModelsEngines::where('referrer', $referrer)
                ->pluck(
                    'name'
                );
            $modelsengines[] = ModelsEngines::where('referrer', $referrer)
                ->pluck(
                    'rewrite_url'
                );
            $modelsengines[] = ModelsEngines::where('referrer', $referrer)
                ->pluck(
                    'equivalent'
                );
            $modelsengines[] = ModelsEngines::where('referrer', $referrer)
                ->pluck(
                    'referrer'
                );
            $modelsengines[] = ModelsEngines::where('referrer', $referrer)
                ->pluck(
                    'description'
                );
            $modelsengines[] = ModelsEngines::where('referrer', $referrer)
                ->pluck(
                    'position'
                );*/

			/*$modelsengines = [];

			$ModelsEngines = ModelsEngines::where('referrer', $referrer)
				->select(
					'manufacturersengines_id',
					'name',
					'rewrite_url',
					'equivalent',
					'referrer',
					'description',
					'position'
				)
				->chunk(20, function ($Items) {
					//die($Items);

					foreach ($Items as $item) {
						$product['manufacturersengines_id'] = $item->manufacturersengines_id;
						$product['name'] = $item->name;
						$product['rewrite_url'] = $item->rewrite_url;
						$product['equivalent'] = $item->equivalent;
						$product['referrer'] = $item->referrer;
						$product['description'] = $item->description;
						$product['position'] = $item->position;
						$modelsengines[] = $product;
					}
				});*/

		} else {
			$manufacturersengines = ManufacturersEngines::orderBy('name', 'asc')
				->lists("name", "id")
				->prepend('Please select', '');
		}
		return view(config('quickadmin.route') . '.modelsengines.index', compact('manufacturersenginesId','manufacturersengines', 'modelsengines', 'countModelsEngines'));
	}

	/**
	 * Show the form for creating a new modelsengines
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view(config('quickadmin.route') . '.modelsengines.create');
	}

	/**
	 * Store a newly created modelsengines in storage.
	 *
	 * @param CreateModelsEnginesRequest|Request $request
	 */
	public function store(CreateModelsEnginesRequest $request)
	{
		ModelsEngines::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.modelsengines.index');
	}

    /**
     * Show the form for editing the specified modelsengines.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $modelsengines = ModelsEngines::find($id);

        return view(config('quickadmin.route') . '.modelsengines.edit', compact('modelsengines'));
    }

    /**
     * Show the form for listing the specified modelsengines.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($manufacturersenginesId)
    {
        debug('ModelsEnginesController@show');
        $manufacturersengines = ManufacturersEngines::orderBy('name', 'asc')
            ->lists('name','id')
            ->prepend('Please select', '');

        $countModelsEngines = ModelsEngines::where('manufacturersengines_id', $manufacturersenginesId)->count();

        $modelsengines = ModelsEngines::where('manufacturersengines_id', $manufacturersenginesId)
			->select(
				'id',
				'manufacturersengines_id',
				'name',
				'rewrite_url',
				'equivalent',
				'referrer',
				'description',
				'position'
			)
			->orderBy('name', 'asc')
			->get();
        return view(config('quickadmin.route') . '.modelsengines.index', compact('manufacturersenginesId', 'manufacturersengines', 'modelsengines', 'countModelsEngines'));
    }

	/**
	 * Update the specified modelsengines in storage.
	 * @param UpdateModelsEnginesRequest|Request $request
	 *
	 * @param  int  $id
	 */
	public function update($id, UpdateModelsEnginesRequest $request)
	{
		$modelsengines = ModelsEngines::findOrFail($id);
		$modelsengines->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.modelsengines.index')->withMessage('Model Engine was successfully updated!');
	}

	/**
	 * Remove the specified modelsengines from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		ModelsEngines::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.modelsengines.index');
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
			ModelsEngines::destroy($toDelete);
		} else {
			ModelsEngines::whereNotNull('id')->delete();
		}

		return redirect()->route(config('quickadmin.route') . '.modelsengines.index');
	}

}
