<?php namespace App\Http\Controllers\BoatgestAdmin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\News;
use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use Illuminate\Http\Request;



class NewsController extends Controller {

	/**
	 * Display a listing of news
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $news = News::orderBy("updated_at", "desc")->get();

		return config('quickadmin.route') . '.news.index', compact('news'));
	}

	/**
	 * Show the specified enquiry.
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function show($id)
	{
		$action = 'show';
		$return = $this->edit($id, $action);
		return view(config('quickadmin.route') . '.news.' . $action, $return);
	}

	/**
	 * Show the form for editing the specified news.
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function edit($id, $action = 'edit')
	{
		$news = News::find($id);

		$datas = [
			'news' => $news
		];
		$return = $datas;

		if('edit' != $action) {
			return $return;
		} else {
			return view(config('quickadmin.route') . '.enquiry.' . $action, $return);
		}
	}

	/**
	 * Store a newly created news in storage.
	 *
     * @param CreateNewsRequest|Request $request
	 */
	public function store(CreateNewsRequest $request)
	{
	    
		News::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.news.index');
	}

	/**
	 * Update the specified news in storage.
     * @param UpdateNewsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateNewsRequest $request)
	{
		$news = News::findOrFail($id);

		$news->update($request->all());

		return redirect()->route(config('quickadmin.route') . '.news.index');
	}

	/**
	 * Remove the specified news from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		News::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.news.index');
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
            News::destroy($toDelete);
        } else {
            News::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.news.index');
    }

}
