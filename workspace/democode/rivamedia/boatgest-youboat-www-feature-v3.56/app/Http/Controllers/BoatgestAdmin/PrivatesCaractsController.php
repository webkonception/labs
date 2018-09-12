<?php namespace App\Http\Controllers\boatgestAdmin;

use App\User;
use App\PrivatesCaracts;
use App\Countries;

use Auth;
use File;
use Redirect;
use Schema;
use Session;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchController;

use Illuminate\Http\Request;
use App\Http\Requests\CreatePrivatesCaractsRequest;
use App\Http\Requests\UpdatePrivatesCaractsRequest;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Traits\FileUploadTrait;

class PrivatesCaractsController extends Controller {

	/**
	 * Display a listing of privatescaracts
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
		if('admin' != Auth::user()->type && 'commercial' != Auth::user()->type) {
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
		$privatescaracts = PrivatesCaracts::orderBy('denomination', 'asc')->get();
		//$privatesusernames = User::where('role_id', 3)->lists('username','id');
		$privatesusernames = User::where('type', 'private')
				//->where('status', 'active')
				//->whereNotNull('created_at')
				//->whereNotNull('updated_at')
				//->whereNotNull('deleted_at')
				->orderBy('username', 'asc')
				->lists('username','id');

		if(isset($privatesusernames)) {
			$array = json_decode(json_encode($privatesusernames), true);
			if (is_array($array) && isset($array)) {
				$privatesusernames = $array;
			}
		}
		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		$status = getEnumValues('users', 'status');

		return view(config('quickadmin.route') . '.privatescaracts.index', compact('privatescaracts','privatesusernames', 'countries','status'));
	}

	/**
	 * Show the form for creating a new privatescaracts
	 *
     * @return \Illuminate\View\View
	 */
	public function create(Request $request)
	{
		$useremail = '';
		$username = '';
		$input = $request->all();
		$user_id = !empty($input['user_id']) ? $input['user_id'] : null;
		$user_status = '';

		//if('private' == Auth::user()->type && Auth::user()->id == $user_id) {
		if(
			(!empty($user_id) && ('admin' == Auth::user()->type || 'commercial' == Auth::user()->type))
			||
			(!empty($user_id) && 'private' == Auth::user()->type && Auth::user()->id == $user_id)
		) {
			$privatesusernames = User::where('type', 'private')
				//->where('status', 'active')
				//->whereNotNull('created_at')
				//->whereNotNull('updated_at')
				//->whereNotNull('deleted_at')
				->where('id', $user_id)
				->orderBy('username', 'asc')
				->lists('username','id','status');
			if(isset($privatesusernames)) {
				$array = json_decode(json_encode($privatesusernames), true);
				if (is_array($array) && isset($array)) {
					$privatesusernames = $array;
				}
			}
			$user  = User::findOrFail($user_id);
			$useremail = $user->email;
			$username = $user->username;
			$user_status = $user->status;
		} else if('admin' == Auth::user()->type || 'commercial' == Auth::user()->type) {
			//$privatesusernames = User::where('role_id', 3)->lists('username','id');
			//$privatesusernames = User::where('role_id', 4)->lists('username','id');
			//SELECT users.username, users.id FROM users
			// LEFT JOIN privatescaracts ON privatescaracts.user_id = users.id
			// WHERE privatescaracts.user_id IS NULL AND users.type = 'private'
			$privatesusernames = User::leftJoin('privatescaracts', 'users.id', '=', 'privatescaracts.user_id')
				->whereNull('privatescaracts.user_id')
				->where('users.type', '=', 'private')
				->orderBy('users.username', 'asc')
				->lists('users.username','users.id','users.status')
				->prepend('Please select', '');
			//$user  = User::findOrFail($user_id);
			//$useremail = $user->email;
			//$username = $user->username;
		} else if('admin' != Auth::user()->type && 'commercial' != Auth::user()->type ||
			('private' == Auth::user()->type && $user_id != Auth::user()->id ))
		{
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		$status = getEnumValues('privatescaracts', 'status');

		return view(config('quickadmin.route') . '.privatescaracts.create', compact('privatesusernames', 'user_id', 'username', 'useremail', 'user_status', 'countries', 'status'));
	}

	/**
	 * Store a newly created privatescaracts in storage.
	 *
     * @param CreatePrivatesCaractsRequest|Request $request
	 */
	public function store(CreatePrivatesCaractsRequest $request)
	{
		PrivatesCaracts::create($request->all());

		return redirect()->route(config('quickadmin.route') . '.privatescaracts.index');

		try {
			$input = $request->all();

			$rulesPrivatesCaracts = CreatePrivatesCaractsRequest::rules();
			$validator = Validator::make($input, $rulesPrivatesCaracts);
			if($validator->fails()) {
				$errors = $validator->errors();
				$array = $input;
				$array['errors'] = $errors;

				$return = $array;
				//return view($viewName, $return)->withInput($request->input())->withErrors($errors, $this->errorBag());
				//return redirect()->route(config('quickadmin.route') . '.privatescaracts.create')->withInput($request->input())->withErrors($errors, $this->errorBag());
				return redirect()->back()->withInput($request->input())->withErrors($errors, $this->errorBag());
			} else {

				$country_code = !empty($input['country_id']) ? $input['country_id'] : '';
				$currentLocale = !empty($country_code) ? mb_strtolower($country_code) : 'uk';
				$country_id = !empty($country_code) ? SearchController::getCountry($country_code)['id'] : null;
				$input['country_id'] = $country_id;

				if ($request->hasFile('photo')) {
					$file = $request->file('photo');
					if($file->isValid()) {
						$title = !empty($input['denomination']) ? $input['denomination'] : 'private';
						$title = str_slug(mb_strtolower($title), '-');

						$destinationPath = '/assets/photos/';
						$destinationPath .= 'privates/';
						$destinationPath .= $country_code . '/';
						$destinationPath .= $title . '/';
						$extension = $file->getClientOriginalExtension(); // getting file extension
						$fileName = $title;
						$fileName .= '.' . $extension;

						/*
                        $pathinfo = pathinfo($fileName);
                        $z = 1;
                        while(file_exists(public_path() . $destinationPath . $fileName)) {
                            $fileName = $pathinfo['filename'] . '_' . $z . '.' . $extension;
                            $z++;
                        }*/

						// move file photo to news directory localized and rename it
						if($file->move(public_path() . $destinationPath, $fileName)) {
							$sourceDir = public_path() . $destinationPath;
							$targetDir = str_replace(['youboat-www_boatgest', 'boatgest-youboat'] , ['youboat-www_website', 'youboat-www_website'], public_path()) . $destinationPath;
							while(!File::isDirectory($targetDir)) {
								File::makeDirectory($targetDir, 0775, true, true);
							}
							File::copy($sourceDir . $fileName, $targetDir . $fileName);
						}
						//$input['photo'] = config('youboat.'. $currentLocale .'.website_url') . $destinationPath . $fileName;
						$input['photo'] = $destinationPath . $fileName;
					}
				} else if (!empty($input['url_image_ext'])){
					$pathinfo = pathinfo($input['url_image_ext']);
					$destinationPath = $pathinfo['dirname'] . '/';
					$fileName = $pathinfo['basename'];

					/*
                    $pathinfo = pathinfo($fileName);
                    $z = 1;
                    while(file_exists(public_path() . $destinationPath . $fileName)) {
                        $fileName = $pathinfo['filename'] . '_' . $z . '.' . $extension;
                        $z++;
                    }*/

					$sourceDir = public_path() . $destinationPath;
					$targetDir = str_replace(['youboat-www_boatgest', 'boatgest-youboat'] , ['youboat-www_website', 'youboat-www_website'], public_path()) . $destinationPath;
					while(!File::isDirectory($targetDir)) {
						File::makeDirectory($targetDir, 0775, true, true);
					}
					File::copy($sourceDir . $fileName, $targetDir . $fileName);

					//$input['photo'] = config('youboat.'. $currentLocale .'.website_url') . $destinationPath . $fileName;
					$input['photo'] = $destinationPath . $fileName;
				}

				$PrivatesCaracts = PrivatesCaracts::Create($input);
				//$News = News::firstOrNew($input);
				if ($PrivatesCaracts->save()) {
					/*$message_referer = 'privatescaracts_create';
					$message_title = trans('navigation.privatescaracts_create');
					$message_text = trans('emails.thanks_news_create');
					$message_type = 'success';
					$request->session()->put('message.referer', $message_referer);
					$request->session()->put('message.title', $message_title);
					$request->session()->put('message.text', $message_text);
					$request->session()->put('message.type', $message_type);

					$message = session()->get('message');

					return redirect()->route(config('quickadmin.route') . '.privatescaracts.index')->withMessage($message);*/

					$message = 'Private was successfully created!';
					Session::set('message.text', $message); //Session::flash
					Session::set('message.type', 'success'); //Session::flash
					return redirect()->route(config('quickadmin.route') . '.privatescaracts.index')->withMessage($message);
				}
			}
		} catch(\Exception $e) {
			return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
		}
		//PrivatesCaracts::create($request->all());
		//return redirect()->route(config('quickadmin.route') . '.privatescaracts.index');
	}

	/**
	 * Show the form for editing the specified privatescaracts.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$privatescaracts = PrivatesCaracts::find($id);

		if(empty($privatescaracts)) {
			return redirect()->route(config('quickadmin.route') . '.privatescaracts.index');
		}
		if('admin' == Auth::user()->type || 'commercial' == Auth::user()->type ||
			('private' == Auth::user()->type && Auth::user()->id == $privatescaracts->user_id)) {
			//$user = User::where('id', $privatescaracts->user_id)->pluck('username')->all();
			//$username = $user[0];
			$user  = User::findOrFail($privatescaracts->user_id);
			$useremail = $user->email;
			$username = $user->username;
			$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();
			$country_id = !empty($privatescaracts->country_id) ? $privatescaracts->country_id : '';
			$country_code = !empty($country_id) ? SearchController::getCountryById($country_id, false)['code'] : null;
			$privatescaracts->country_id = $country_code;

			$status = getEnumValues('users', 'status');

			return view(config('quickadmin.route') . '.privatescaracts.edit', compact('user', 'useremail', 'username', 'privatescaracts', 'countries', 'status'));
		} else if('admin' != Auth::user()->type && 'commercial' != Auth::user()->type ||
			('private' == Auth::user()->type && Auth::user()->id != $privatescaracts->user_id )) {
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
	}

	/**
	 * Update the specified privatescaracts in storage.
     * @param UpdatePrivatesCaractsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdatePrivatesCaractsRequest $request)
	{
		$privatescaracts = PrivatesCaracts::findOrFail($id);

		$input = $request->all();

		$country_code = !empty($input['country_id']) ? $input['country_id'] : '';
		$currentLocale = !empty($country_code) ? mb_strtolower($country_code) : 'uk';
		$country_id = !empty($country_code) ? SearchController::getCountry($country_code)['id'] : null;
		$input['country_id'] = $country_id;

		if ($request->hasFile('photo')) {
			$file = $request->file('photo');
			if($file->isValid()) {
				$title = !empty($input['denomination']) ? $input['denomination'] : 'private';
				$title = str_slug(mb_strtolower($title), '-');

				$destinationPath = '/assets/photos/';
				$destinationPath .= 'privates/';
				$destinationPath .= $country_code . '/';
				$destinationPath .= $title . '/';
				$extension = $file->getClientOriginalExtension(); // getting file extension
				$fileName = $title;
				$fileName .= '.' . $extension;

				/*
				$pathinfo = pathinfo($fileName);
				$z = 1;
				while(file_exists(public_path() . $destinationPath . $fileName)) {
					$fileName = $pathinfo['filename'] . '_' . $z . '.' . $extension;
					$z++;
				}*/

				// move file photo to news directory localized and rename it
				if($file->move(public_path() . $destinationPath, $fileName)) {
					$sourceDir = public_path() . $destinationPath;
					$targetDir = str_replace(['youboat-www_boatgest', 'boatgest-youboat'] , ['youboat-www_website', 'youboat-www_website'], public_path()) . $destinationPath;
					while(!File::isDirectory($targetDir)) {
						File::makeDirectory($targetDir, 0775, true, true);
					}
					File::copy($sourceDir . $fileName, $targetDir . $fileName);
				}
				//$input['photo'] = config('youboat.'. $currentLocale .'.website_url') . $destinationPath . $fileName;
				$input['photo'] = $destinationPath . $fileName;
			}
		} else if (!empty($input['url_image_ext'])){
			$pathinfo = pathinfo($input['url_image_ext']);
			$destinationPath = $pathinfo['dirname'] . '/';
			$fileName = $pathinfo['basename'];

			/*
			$pathinfo = pathinfo($fileName);
			$z = 1;
            while(file_exists(public_path() . $destinationPath . $fileName)) {
                $fileName = $pathinfo['filename'] . '_' . $z . '.' . $extension;
                $z++;
            }*/

			$sourceDir = public_path() . $destinationPath;
			$targetDir = str_replace(['youboat-www_boatgest', 'boatgest-youboat'] , ['youboat-www_website', 'youboat-www_website'], public_path()) . $destinationPath;
			while(!File::isDirectory($targetDir)) {
				File::makeDirectory($targetDir, 0775, true, true);
			}
			File::copy($sourceDir . $fileName, $targetDir . $fileName);

			//$input['photo'] = config('youboat.'. $currentLocale .'.website_url') . $destinationPath . $fileName;
			$input['photo'] = $destinationPath . $fileName;
		}
		$privatescaracts->update($input);

		return redirect()->route(config('quickadmin.route') . '.privatescaracts.index')->withMessage('Private\'s caracts was successfully updated!');
	}

	/**
	 * Remove the specified privatescaracts from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		PrivatesCaracts::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.privatescaracts.index');
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
            PrivatesCaracts::destroy($toDelete);
        } else {
            PrivatesCaracts::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.privatescaracts.index');
    }

}
