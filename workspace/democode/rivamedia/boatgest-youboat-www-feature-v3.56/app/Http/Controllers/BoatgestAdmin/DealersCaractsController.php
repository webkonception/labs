<?php namespace App\Http\Controllers\boatgestAdmin;

use App\User;
use App\DealersCaracts;
use App\Countries;

use Auth;
use File;
use Redirect;
use Schema;
use Session;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchController;

use Illuminate\Http\Request;
use App\Http\Requests\CreateDealersCaractsRequest;
use App\Http\Requests\UpdateDealersCaractsRequest;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Traits\FileUploadTrait;

class DealersCaractsController extends Controller {

	/**
	 * Display a listing of dealerscaracts
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
		$dealerscaracts = DealersCaracts::orderBy('denomination', 'asc')->get();
		//$dealersusernames = User::where('role_id', 4)->lists('username','id');
		$dealersusernames = User::where('type', 'dealer')
				//->where('status', 'active')
				//->whereNotNull('created_at')
				//->whereNotNull('updated_at')
				//->whereNotNull('deleted_at')
				->orderBy('username', 'asc')
				->lists('username','id');

		if(isset($dealersusernames)) {
			$array = json_decode(json_encode($dealersusernames), true);
			if (is_array($array) && isset($array)) {
				$dealersusernames = $array;
			}
		}
		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		$status = getEnumValues('users', 'status');

		return view(config('quickadmin.route') . '.dealerscaracts.index', compact('dealerscaracts','dealersusernames', 'countries', 'status'));
	}

	/**
	 * Show the form for creating a new dealerscaracts
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

		//if('dealer' == Auth::user()->type && Auth::user()->id == $user_id) {
		if(
			(!empty($user_id) && ('admin' == Auth::user()->type || 'commercial' == Auth::user()->type))
			||
			(!empty($user_id) && 'dealer' == Auth::user()->type && Auth::user()->id == $user_id)
		) {
			$dealersusernames = User::where('type', 'dealer')
				//->where('status', 'active')
				//->whereNotNull('created_at')
				//->whereNotNull('updated_at')
				//->whereNotNull('deleted_at')
				->where('id',$user_id)
				->orderBy('username', 'asc')
				->lists('username','id','status');
			if(isset($dealersusernames)) {
				$array = json_decode(json_encode($dealersusernames), true);
				if (is_array($array) && isset($array)) {
					$dealersusernames = $array;
				}
			}
			$user  = User::findOrFail($user_id);
			$useremail = $user->email;
			$username = $user->username;
			$user_status = $user->status;
		} else if('admin' == Auth::user()->type || 'commercial' == Auth::user()->type) {
			//$dealersusernames = User::where('role_id', 4)->lists('username','id');
			//SELECT users.username, users.id FROM users
			// LEFT JOIN dealerscaracts ON dealerscaracts.user_id = users.id
			// WHERE dealerscaracts.user_id IS NULL AND users.type = 'dealer'
			$dealersusernames = User::leftJoin('dealerscaracts', 'users.id', '=', 'dealerscaracts.user_id')
				->whereNull('dealerscaracts.user_id')
				->where('users.type', '=', 'dealer')
				->orderBy('users.username', 'asc')
				->lists('users.username','users.id','users.status')
				->prepend('Please select', '');
			//$user  = User::findOrFail($user_id);
			//$useremail = $user->email;
			//$username = $user->username;
		//} else if('admin' != Auth::user()->type && 'dealer' != Auth::user()->type ||
		} else if('admin' != Auth::user()->type || 'commercial' != Auth::user()->type ||
			('dealer' == Auth::user()->type && $user_id != Auth::user()->id ))
		{
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

		$status = getEnumValues('dealerscaracts', 'status');

		return view(config('quickadmin.route') . '.dealerscaracts.create', compact('dealersusernames', 'user_id', 'username', 'useremail', 'user_status', 'countries', 'status'));
	}

	/**
	 * Store a newly created dealerscaracts in storage.
	 *
     * @param CreateDealersCaractsRequest|Request $request
	 */
	public function store(CreateDealersCaractsRequest $request)
	//public function store(Request $request)
	{
        try {
			$input = $request->all();

			$rulesDealersCaracts = CreateDealersCaractsRequest::rules();
			$validator = Validator::make($input, $rulesDealersCaracts);
			if($validator->fails()) {
				$errors = $validator->errors();
				$array = $input;
				$array['errors'] = $errors;

				$return = $array;
				//return view($viewName, $return)->withInput($request->input())->withErrors($errors, $this->errorBag());
				//return redirect()->route(config('quickadmin.route') . '.dealerscaracts.create')->withInput($request->input())->withErrors($errors, $this->errorBag());
				return redirect()->back()->withInput($request->input())->withErrors($errors, $this->errorBag());
			} else {

				$country_code = !empty($input['country_id']) ? $input['country_id'] : '';
				$currentLocale = !empty($country_code) ? mb_strtolower($country_code) : 'uk';
				$country_id = !empty($country_code) ? SearchController::getCountry($country_code)['id'] : null;
				$input['country_id'] = $country_id;

				if ($request->hasFile('photo')) {
					$file = $request->file('photo');
					if($file->isValid()) {
						$title = !empty($input['denomination']) ? $input['denomination'] : 'dealer';
						$title = str_slug(mb_strtolower($title), '-');

						$destinationPath = '/assets/photos/';
						$destinationPath .= 'dealers/';
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

				$DealersCaracts = DealersCaracts::Create($input);
				//$News = News::firstOrNew($input);
				if ($DealersCaracts->save()) {
					/*$message_referer = 'dealerscaracts_create';
					$message_title = trans('navigation.dealerscaracts_create');
					$message_text = trans('emails.thanks_news_create');
					$message_type = 'success';
					$request->session()->put('message.referer', $message_referer);
					$request->session()->put('message.title', $message_title);
					$request->session()->put('message.text', $message_text);
					$request->session()->put('message.type', $message_type);

					$message = session()->get('message');

					return redirect()->route(config('quickadmin.route') . '.dealerscaracts.index')->withMessage($message);*/

					$message = 'Dealer was successfully created!';
					Session::set('message.text', $message); //Session::flash
					Session::set('message.type', 'success'); //Session::flash
					return redirect()->route(config('quickadmin.route') . '.dealerscaracts.index')->withMessage($message);
				}
			}
		} catch(\Exception $e) {
			return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
		}
		//DealersCaracts::create($request->all());
		//return redirect()->route(config('quickadmin.route') . '.dealerscaracts.index');
	}

	/**
	 * Show the form for editing the specified dealerscaracts.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id, Request $request)
	{
		$dealerscaracts = DealersCaracts::find($id);

		if(empty($dealerscaracts)) {
			return redirect()->route(config('quickadmin.route') . '.dealerscaracts.index');
		}
		if('admin' == Auth::user()->type || 'commercial' == Auth::user()->type ||
			('dealer' == Auth::user()->type && Auth::user()->id == $dealerscaracts->user_id)) {
			//$user = User::where('id', $dealerscaracts->user_id)->pluck('username')->all();
			//$username = $user[0];
			$user  = User::findOrFail($dealerscaracts->user_id);
			$useremail = $user->email;
			$username = $user->username;
			$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();
			$country_id = !empty($dealerscaracts->country_id) ? $dealerscaracts->country_id : '';
			$country_code = !empty($country_id) ? SearchController::getCountryById($country_id, false)['code'] : null;
			$dealerscaracts->country_id = $country_code;
			$dealerscaracts->status = $user->status;
			$status = getEnumValues('users', 'status');

			return view(config('quickadmin.route') . '.dealerscaracts.edit', compact('user', 'useremail', 'username', 'dealerscaracts', 'countries', 'status'));
		} else if('admin' != Auth::user()->type && 'commercial' != Auth::user()->type ||
			('dealer' == Auth::user()->type && Auth::user()->id != $dealerscaracts->user_id )) {
			return redirect()->route(config('quickadmin.route') . '.dashboard.index');
		}
	}

	/**
	 * Update the specified dealerscaracts in storage.
     * @param UpdateDealersCaractsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateDealersCaractsRequest $request)
	{
		$dealerscaracts = DealersCaracts::findOrFail($id);

		$input = $request->all();

		$country_code = !empty($input['country_id']) ? $input['country_id'] : '';
		$currentLocale = !empty($country_code) ? mb_strtolower($country_code) : 'uk';
		$country_id = !empty($country_code) ? SearchController::getCountry($country_code)['id'] : null;
		$input['country_id'] = $country_id;

		if ($request->hasFile('photo')) {
			$file = $request->file('photo');
			if($file->isValid()) {
				$title = !empty($input['denomination']) ? $input['denomination'] : 'dealer';
				$title = str_slug(mb_strtolower($title), '-');

				$destinationPath = '/assets/photos/';
				$destinationPath .= 'dealers/';
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
		$dealerscaracts->update($input);

		return redirect()->route(config('quickadmin.route') . '.dealerscaracts.index')->withMessage('Dealer\'s caracts was successfully updated!');
	}

	/**
	 * Remove the specified dealerscaracts from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		DealersCaracts::destroy($id);

		return redirect()->route(config('quickadmin.route') . '.dealerscaracts.index');
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
            DealersCaracts::destroy($toDelete);
        } else {
            DealersCaracts::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.dealerscaracts.index');
    }

}
