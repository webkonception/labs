<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    //use App\Http\Requests\DashboardRequest;
    use App\Http\Requests\CustomerCaractsRequest;
    use App\Http\Requests\PrivatesCaractsRequest;
    use App\Http\Requests\DealersCaractsRequest;
    use App\Http\Requests\CommercialsCaractsRequest;

    use App\Http\Requests\BodFormRequest;
    use App\Http\Requests\AdsCaractsFormRequest;

    use App\EnquiryForm;
    use App\BodCaracts;
    use App\AdsCaracts;
    use App\Countries;
    use App\CustomersCaracts;
    use App\CommercialsCaracts;
    use App\DealersCaracts;
    use App\PrivatesCaracts;
    use App\ProspectiveCustomers;

    use Auth;
    use Hash;
    use App\User;

    //use App\Http\Controllers\Auth;
    use Cache;
    use Artisan;
    use Session;

    use Illuminate\Support\Facades\Validator;

    class DashboardController extends ForsaleController
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            $this->middleware(['auth','clearcache']);
            //$this->middleware(['auth']);
            //$this->middleware(['auth', 'role']);

        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request)
        {
            $viewName = app('request')->route()->getName();
            $return = [];
            $result = [];
            if ($request) {
                $datasRequest = $request->all();
                $pageTitle = trans('navigation.' . $viewName);

                $return = compact('pageTitle', 'datasRequest');
                /*
                $name = !empty($customerscaracts['name']) ? !empty($customerscaracts['firstname']) ? ucfirst(mb_strtolower($customerscaracts['firstname'])) . ' ' . mb_strtoupper($customerscaracts['name']) : mb_strtoupper($customerscaracts['name']) : '';
                $message_referrer = 'dashboard';
                $message_title = trans('navigation.dashboard');
                $message_text = trans('navigation.welcome') . ' ' . $name;
                $message_type = 'info';
                $request->session()->put('message.referrer', $message_referrer);
                $request->session()->put('message.title', $message_title);
                $request->session()->put('message.text', $message_text);
                $request->session()->put('message.type', $message_type);
                */
            }
            //if(Auth::check() && 'customer' == Auth::user()->type) {
            if(Auth::check()) {
                // Get Bod Listing
                $result = $this->getBodListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                if(Auth::user()->type == 'private') {
                    $result += $this->getAdsListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                }
                // Get Enquiries
                $userenquiries = EnquiryForm::where('ci_email', Auth::user()->email)->get();
                if(isset($userenquiries)) {
                    $array = json_decode(json_encode($userenquiries), true);
                    if (is_array($array) && !empty($array)) {
                        $result['enquiries'] = $array;
                    }
                }
            } else {
                Auth::logout();
                $data['logout'] = true;
                Session::flush();
                Cache::flush();

                $currentLocale = config('app.locale');
                return redirect(trans_route($currentLocale, 'routes.login'))->with('data',$data);
            }

            $return +=  $result;
            return view($viewName, $return)->with(['controller' => $this]);
            //return view($viewName, $return)->with(['controller' => $this])->with('message');
        }

        /**
         * Show the form for editing the specified bodcaracts.
         *
         * @param  int  $id
         * @return \Illuminate\View\View
         */
        public function editBod(Request $request)
        {
            $viewName = app('request')->route()->getName();

            $return = [];

            if(Auth::check()) {
                $input = $request->all();
                $id = $input['id'];

                $user_id = Auth::user()->id;
                $user_email = Auth::user()->email;

                //$result = CustomersCaracts::where('user_id', $user_id)
                //->where('emails', $user_email)
                //->select('id', 'firstname', 'name', 'zip', 'city', 'province', 'region', 'subregion', 'country_id', 'phone_1' , 'agree_emails')
                //->get();
                /*$result = CustomersCaracts::where('user_id', $user_id)
                    // @TODO  : check if 'emails' check necessary
                    //->where('emails', $user_email)
                    ->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')
                    ->get();
                $result = json_decode(json_encode($result), true);*/

                ////
                ////
                $user_type = Auth::user()->type;
                $usercaracts = '';
                switch($user_type) {
                    case 'private':
                        $usercaracts = PrivatesCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                        break;
                    case 'dealer':
                        $usercaracts = DealersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                        break;
                    case 'commercial':
                        $usercaracts = CommercialsCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                        break;
                    case 'customer':
                        $usercaracts = CustomersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                        break;
                }

                $customerscaracts = [];
                $bodcaracts = [];
                if(isset($usercaracts)) {
                    $array = json_decode(json_encode($usercaracts), true);
                    if (is_array($array) && !empty($array[0])) {
                        $customerscaracts = $array[0];
                    }

                //$customerscaracts = [];
                //$bodcaracts = [];
                //if(!empty($result)) {
                    //$customerscaracts = $result[0];
                    $customerscaracts['email'] = $user_email;

                    //$result = BodCaracts::where('customer_id', $customerscaracts['id'])
                    $result = BodCaracts::where('user_id', $user_id)
                        ->where('ci_email', $user_email)
                        //->select('customer_id', 'ci_firstname', 'ci_last_name', 'ci_phone', 'ci_email', 'ci_password')->get();
                        //->select('customer_id', 'ci_password')->get(1);
                        ->select('user_id', 'ci_password')->get(1);
                    $result = json_decode(json_encode($result), true);
                    $user_infos = [];
                    if (is_array($result) && !empty($result[0])) {
                        $user_infos = $result[0];
                    }
                    //$user_infos = json_decode(json_encode($result), true)[0];

                    //$bodcaracts = BodCaracts::find($id);
                    //$bodcaracts = BodCaracts::where('customer_id', $user_infos['customer_id'])->findOrFail($id);
                    $bodcaracts = BodCaracts::where('user_id', $user_infos['user_id'])->findOrFail($id);

                    $getDefaults = $this->getDefaults($bodcaracts);

                    $countries_id = !empty($bodcaracts->countries_id) ? $bodcaracts->countries_id : '';
                    //$countries_code = SearchController::getCountryById($countries_id, false)['code'];
                    $countries_code = SearchController::getCountryById($countries_id)['id'];
                    $bodcaracts->countries_id = $countries_code;

                    $ci_countries_id = !empty($bodcaracts->ci_countries_id) ? $bodcaracts->ci_countries_id : '';
                    //$ci_countries_code = SearchController::getCountryById($ci_countries_id, false)['code'];
                    $ci_countries_code = SearchController::getCountryById($ci_countries_id)['id'];
                    $bodcaracts->ci_countries_id = $ci_countries_code;
                }

                //$bodcaracts['agree_cgv'] = null;
                $datas = [
                    'customerscaracts' => $customerscaracts,
                    'user_infos' => $user_infos,
                    'bodcaracts' => $bodcaracts
                ];
                $return = $datas + $getDefaults;
            }
            return view($viewName, $return);
        }

        /**
         * Update the specified bodcaracts in storage.
         * @param UpdateBodCaractsRequest|Request $request
         *
         * @param  int  $id
         */
        public function updateBod(Request $request)
        {
            $viewName = app('request')->route()->getName();

            try {
                $return = [];

                if(Auth::check()) {
                    $input = $request->all();
                    $id = $input['id'];

                    $countries_code = !empty($input['countries_id']) ? $input['countries_id'] : '';
                    $countries_id = SearchController::getCountry($countries_code)['id'];
                    $input['countries_id'] = $countries_id;

                    $ci_countries_code = !empty($input['ci_countries_id']) ? $input['ci_countries_id'] : '';
                    $ci_countries_id = SearchController::getCountry($ci_countries_code)['id'];
                    $input['ci_countries_id'] = $ci_countries_id;

                    $bodcaracts = BodCaracts::findOrFail($id);

                    $request = new Request($input);
                    $datasRequest = $request->all();

                    $datasRequest['with_marina_berth'] = (!empty($datasRequest['with_marina_berth']) && $datasRequest['with_marina_berth'] == 1) ? 1 : null;
                    $datasRequest['agree_similar'] = (!empty($datasRequest['agree_similar']) && $datasRequest['agree_similar'] == 1) ? 1 : null;
                    $datasRequest['agree_emails'] = (!empty($datasRequest['agree_emails']) && $datasRequest['agree_emails'] == 1) ? 1 : null;
                    $datasRequest['agree_cgv'] = (!empty($datasRequest['agree_cgv']) && $datasRequest['agree_cgv'] == 1) ? 1 : null;
                    $datasRequest['status'] = 'in_moderation';

                    $rulesBoatOnDemand = BodFormRequest::rulesUpdate();
                    $validator = Validator::make($datasRequest, $rulesBoatOnDemand);

                    $getDefaults = $this->getDefaults($bodcaracts);

                    if($validator->fails()) {
                        $errors = $validator->errors();
                        $array = json_decode(json_encode($this->getDefaults($datasRequest)), true);
                        $array['errors'] = $errors;

                        $user_email = Auth::user()->email;
                        $user_id = Auth::user()->id;
                        $user_type = Auth::user()->type;

                        $usercaracts = '';
                        switch($user_type) {
                            case 'private':
                                $usercaracts = PrivatesCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                                break;
                            case 'dealer':
                                $usercaracts = DealersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                                break;
                            case 'commercial':
                                $usercaracts = CommercialsCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                                break;
                            case 'customer':
                                $usercaracts = CustomersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                                break;
                        }

                        $customerscaracts = [];
                        if(isset($usercaracts)) {
                            $array = json_decode(json_encode($usercaracts), true);
                            if (is_array($array) && !empty($array[0])) {
                                $customerscaracts = $array[0];
                            }
                        }
                        $customerscaracts['email'] = $user_email;

                        //$result = BodCaracts::where('customer_id', $customerscaracts['id'])
                        $result = BodCaracts::where('user_id', $customerscaracts['id'])
                            ->where('ci_email', $user_email)
                            //->select('customer_id', 'ci_firstname', 'ci_last_name', 'ci_phone', 'ci_email', 'ci_password')->get();
                            //->select('customer_id', 'ci_password')->get(1);
                            ->select('user_id', 'ci_password')->get(1);
                        $result = json_decode(json_encode($result), true);
                        $user_infos = [];
                        if (is_array($result) && !empty($result[0])) {
                            $user_infos = $result[0];
                        }
                        $datas = [
                            'customerscaracts' => $customerscaracts,
                            'user_infos' => $user_infos,
                            'bodcaracts' => $bodcaracts,
                        ];

                        //$datasRequest['agree_cgv'] = null;
                        $return = $datas + $getDefaults + $array + compact('datasRequest');
                        return view($viewName, $return)->withErrors($errors, $this->errorBag());
                        //return view($viewName, $return)->withInput($request->input())->withErrors($errors, $this->errorBag());
                    } else {
                        $viewName = 'dashboard';
                        $currentLocale = config('app.locale');

                       // $input = $request->all();

                        //$input['status'] = 'in_moderation';
                        //$request = new Request($input);

                        //$request = $this->saveFiles($request);
                        $datasRequest = $request->all();

                        $bodcaracts->update($datasRequest);
                        $bodcaracts->status = 'in_moderation';
                        if($bodcaracts->save()) {
                            $message_referrer = 'dashboard_edit_bod';
                            $message_title = trans('navigation.dashboard_edit_bod');
                            $message_text = trans('dashboard.successfully_updated');
                            $message_type = 'success';
                            Session::put('dashboard_message.referrer', $message_referrer);
                            Session::put('dashboard_message.title', $message_title);
                            Session::put('dashboard_message.text', $message_text);
                            Session::put('dashboard_message.type', $message_type);
                            $message = Session::get('dashboard_message');
                            $result = $this->getBodListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                            $return +=  $result;
                            //$return['dashboard_message'] = $message_text;
                            $return['dashboard_message'] = $message;
                            //return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                            return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with($return)->withMessage($message);
                        }
                        //return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with('return',$return)->withMessage($message);
                        //return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with('return',$return);
                        return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                    }
                }
                return view($viewName, $return)->withInput($request->input());

            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }

        /**
         * Unpublish the specified bodcaracts in storage.
         * @param UpdateBodCaractsRequest|Request $request
         *
         * @param  int  $id
         */
        public function unpublishBod(Request $request)
        {
            //$viewName = app('request')->route()->getName();
            $viewName = 'dashboard';

            try {
                $return = [];

                if(Auth::check()) {
                    $input = $request->all();
                    $id = $input['id'];

                    $viewName = 'dashboard';
                    $currentLocale = config('app.locale');

                    $bodcaracts = BodCaracts::findOrFail($id);
                    $bodcaracts->status = 'unpublished';

                    if($bodcaracts->save()) {
                        $message_referrer = 'dashboard_unpublish_bod';
                        $message_title = trans('navigation.dashboard_unpublish_bod');
                        $message_text = trans('dashboard.successfully_updated');
                        $message_type = 'success';
                        Session::put('dashboard_message.referrer', $message_referrer);
                        Session::put('dashboard_message.title', $message_title);
                        Session::put('dashboard_message.text', $message_text);
                        Session::put('dashboard_message.type', $message_type);
                        $message = Session::get('dashboard_message');
                        $result = $this->getBodListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                        $return +=  $result;
                        //$return['dashboard_message'] = $message_text;
                        $return['dashboard_message'] = $message;
                        //return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                        return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with($return)->withMessage($message);
                    }
                }
                return view($viewName, $return)->withInput($request->input());
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }

        /**
         * Reactivate the specified bodcaracts in storage.
         * @param UpdateBodCaractsRequest|Request $request
         *
         * @param  int  $id
         */
        public function reactivateBod(Request $request)
        {
            //$viewName = app('request')->route()->getName();
            $viewName = 'dashboard';

            try {
                $return = [];

                if(Auth::check()) {
                    $input = $request->all();
                    $id = $input['id'];

                    $viewName = 'dashboard';
                    $currentLocale = config('app.locale');

                    $bodcaracts = BodCaracts::findOrFail($id);
                    $bodcaracts->status = 'in_moderation';

                    if($bodcaracts->save()) {
                        $message_referrer = 'dashboard_reactivate_bod';
                        $message_title = trans('navigation.dashboard_reactivate_bod');
                        $message_text = trans('dashboard.successfully_updated');
                        $message_type = 'success';
                        Session::put('dashboard_message.referrer', $message_referrer);
                        Session::put('dashboard_message.title', $message_title);
                        Session::put('dashboard_message.text', $message_text);
                        Session::put('dashboard_message.type', $message_type);
                        $message = Session::get('dashboard_message');
                        $result = $this->getBodListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                        $return +=  $result;
                        //$return['dashboard_message'] = $message_text;
                        $return['dashboard_message'] = $message;
                        //return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                        return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with($return)->withMessage($message);
                    }
                }
                return view($viewName, $return)->withInput($request->input());
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }


        /**
         * Show the form for editing the specified bodcaracts.
         *
         * @param  int  $id
         * @return \Illuminate\View\View
         */
        public function editAds(Request $request)
        {
            $viewName = app('request')->route()->getName();

            $return = [];

            if(Auth::check()) {
                $input = $request->all();
                $id = $input['id'];

                $user_id = Auth::user()->id;
                $user_email = Auth::user()->email;

                //$result = CustomersCaracts::where('user_id', $user_id)
                //->where('emails', $user_email)
                //->select('id', 'firstname', 'name', 'zip', 'city', 'province', 'region', 'subregion', 'country_id', 'phone_1' , 'agree_emails')
                //->get();
                /*$result = CustomersCaracts::where('user_id', $user_id)
                    // @TODO  : check if 'emails' check necessary
                    //->where('emails', $user_email)
                    ->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')
                    ->get();
                $result = json_decode(json_encode($result), true);*/

                ////
                ////
                $user_type = Auth::user()->type;
                $usercaracts = '';
                switch($user_type) {
                    case 'private':
                        $usercaracts = PrivatesCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                        break;
                    case 'dealer':
                        $usercaracts = DealersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                        break;
                    case 'commercial':
                        $usercaracts = CommercialsCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                        break;
                    case 'customer':
                        $usercaracts = CustomersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                        break;
                }

                $privatescaracts = [];
                $adscaracts = [];
                if(isset($usercaracts)) {
                    $array = json_decode(json_encode($usercaracts), true);
                    if (is_array($array) && !empty($array[0])) {
                        $privatescaracts = $array[0];
                    }

                    //$privatescaracts = [];
                    //$adscaracts = [];
                    //if(!empty($result)) {
                    //$privatescaracts = $result[0];
                    $privatescaracts['email'] = $user_email;

                    //$result = AdsCaracts::where('customer_id', $privatescaracts['id'])
                    $result = AdsCaracts::where('user_id', $user_id)
                        //->where('ci_email', $user_email)
                        //->select('customer_id', 'ci_firstname', 'ci_last_name', 'ci_phone', 'ci_email', 'ci_password')->get();
                        //->select('customer_id', 'ci_password')->get(1);
                        //->select('user_id', 'ci_password')->get(1);
                        ->select('user_id')->get(1);
                    $result = json_decode(json_encode($result), true);
                    $user_infos = [];
                    if (is_array($result) && !empty($result[0])) {
                        $user_infos = $result[0];
                    }
                    //$user_infos = json_decode(json_encode($result), true)[0];

                    //$adscaracts = AdsCaracts::find($id);
                    //$adscaracts = AdsCaracts::where('customer_id', $user_infos['customer_id'])->findOrFail($id);
                    $adscaracts = AdsCaracts::where('user_id', $user_infos['user_id'])->findOrFail($id);

                    $adscaracts = AdsCaracts::where('user_id', $user_id)->findOrFail($id);

                    $getDefaults = $this->getDefaults($adscaracts);

                    $countries_id = !empty($adscaracts->countries_id) ? $adscaracts->countries_id : '';
                    if(!is_numeric($countries_id) && !empty($countries_id)) {
                        $countries_code = SearchController::getCountryById($countries_id)['id'];
                        $adscaracts->countries_id = $countries_code;
                    }

                    $ad_country_code = '';
                    if(is_numeric($countries_id) && !empty($countries_id)) {
                        $getCountryById = SearchController::getCountryById($countries_id, false);
                        //$getCountryById = $this->getCountryById($country_id, false);
                        if (is_array($getCountryById) && array_key_exists('code', $getCountryById)) {
                            $ad_country_code = mb_strtolower($getCountryById['code']);
                        }
                        $from = ['gb'];
                        $to = ['uk'];
                        $ad_country_code = str_replace($from, $to, $ad_country_code);
                    }
                }

                //$adscaracts['agree_cgv'] = null;
                $datas = [
                    'privatescaracts' => $privatescaracts,
                    'user_infos' => $user_infos,
                    'adscaracts' => $adscaracts,
                    'ad_country_code' => $ad_country_code
                ];
                $return = $datas + $getDefaults;
            }
            return view($viewName, $return);
        }

        /**
         * Update the specified adscaracts in storage.
         * @param UpdateAdsCaractsRequest|Request $request
         *
         * @param  int  $id
         */
        public function updateAds(Request $request)
        {
            $viewName = app('request')->route()->getName();

            try {
                $return = [];

                if(Auth::check()) {
                    $input = $request->all();
                    $id = $input['id'];
                    $countries_code = !empty($input['countries_id']) ? $input['countries_id'] : '';
                    $countries_id = SearchController::getCountry($countries_code)['id'];
                    $input['countries_id'] = $countries_id;

                    $ad_photos = '';
                    if(array_key_exists('upload_photos', $input) && is_array($input['upload_photos']) && count($input['upload_photos']) > 0) {
                        $ad_photos = implode(';', $input['upload_photos']);
                        $input['ad_photo'] = $input['upload_photos'][0];
                    } else if(array_key_exists('upload_photos', $input)) {
                        $ad_photos = $input['upload_photos'];
                        $input['ad_photo'] = $input['upload_photos'][0];
                    }
                    $input['ad_photos'] = $ad_photos;

                    ////
                    /*$appCountryCode = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
                    $getCountry = Search::getCountry(config('youboat.' . $appCountryCode . '.country_code'));
                    $countries_id = 77;
                    if(is_array($getCountry)) {
                        if (array_key_exists('id', $getCountry)) {
                            $countries_id = !empty($getCountry['id']) ? $getCountry['id'] : 77;
                        }
                    }*/
                    ///

                    $adscaracts = AdsCaracts::findOrFail($id);

                    $request = new Request($input);
                    $datasRequest = $request->all();

                    $datasRequest['with_marina_berth'] = (!empty($datasRequest['with_marina_berth']) && $datasRequest['with_marina_berth'] == 1) ? 1 : null;
                    $datasRequest['agree_similar'] = (!empty($datasRequest['agree_similar']) && $datasRequest['agree_similar'] == 1) ? 1 : null;
                    $datasRequest['agree_emails'] = (!empty($datasRequest['agree_emails']) && $datasRequest['agree_emails'] == 1) ? 1 : null;
                    $datasRequest['agree_cgv'] = (!empty($datasRequest['agree_cgv']) && $datasRequest['agree_cgv'] == 1) ? 1 : null;
                    $datasRequest['status'] = 'in_moderation';

                    $rulesAdsCaracts = AdsCaractsFormRequest::rulesUpdate();
                    $validator = Validator::make($datasRequest, $rulesAdsCaracts);

                    $getDefaults = $this->getDefaults($adscaracts);

                    if($validator->fails()) {
                        $errors = $validator->errors();
                        $array = json_decode(json_encode($this->getDefaults($datasRequest)), true);
                        $array['errors'] = $errors;

                        $user_email = Auth::user()->email;
                        $user_id = Auth::user()->id;
                        $user_type = Auth::user()->type;

                        $usercaracts = '';
                        switch($user_type) {
                            case 'private':
                                $usercaracts = PrivatesCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                                break;
                            case 'dealer':
                                $usercaracts = DealersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                                break;
                            case 'commercial':
                                $usercaracts = CommercialsCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                                break;
                            case 'customer':
                                $usercaracts = CustomersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'zip', 'city', 'country_id', 'phone_1' , 'agree_emails')->get();
                                break;
                        }

                        $privatescaracts = [];
                        if(isset($usercaracts)) {
                            $array = json_decode(json_encode($usercaracts), true);
                            if (is_array($array) && !empty($array[0])) {
                                $privatescaracts = $array[0];
                            }
                        }
                        $privatescaracts['email'] = $user_email;

                        //$result = AdsCaracts::where('customer_id', $privatescaracts['id'])
                        /*$result = AdsCaracts::where('user_id', $privatescaracts['id'])
                            //->where('ci_email', $user_email)
                            //->select('customer_id', 'ci_firstname', 'ci_last_name', 'ci_phone', 'ci_email', 'ci_password')->get();
                            //->select('customer_id', 'ci_password')->get(1);
                            ->select('user_id', 'ci_password')->get(1);
                        $result = json_decode(json_encode($result), true);
                        $user_infos = [];
                        if (is_array($result) && !empty($result[0])) {
                            $user_infos = $result[0];
                        }*/
                        $datas = [
                            'privatescaracts' => $privatescaracts,
                            //'user_infos' => $user_infos,
                            'adscaracts' => $adscaracts,
                        ];

                        //$datasRequest['agree_cgv'] = null;
                        $return = $datas + $getDefaults + $array + compact('datasRequest');
                        return view($viewName, $return)->withErrors($errors, $this->errorBag());
                        //return view($viewName, $return)->withInput($request->input())->withErrors($errors, $this->errorBag());
                    } else {
                        $viewName = 'dashboard';
                        $currentLocale = config('app.locale');

                        // $input = $request->all();

                        //$input['status'] = 'in_moderation';
                        //$request = new Request($input);

                        //$request = $this->saveFiles($request);
                        $datasRequest = $request->all();

                        $adscaracts->update($datasRequest);
                        $adscaracts->status = 'in_moderation';
                        if($adscaracts->save()) {
                            $message_referrer = 'dashboard_edit_ads';
                            $message_title = trans('navigation.dashboard_edit_ads');
                            $message_text = trans('dashboard.successfully_updated');
                            $message_type = 'success';
                            Session::put('dashboard_message.referrer', $message_referrer);
                            Session::put('dashboard_message.title', $message_title);
                            Session::put('dashboard_message.text', $message_text);
                            Session::put('dashboard_message.type', $message_type);
                            $message = Session::get('dashboard_message');
                            $result = $this->getAdsListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                            $return +=  $result;
                            //$return['dashboard_message'] = $message_text;
                            $return['dashboard_message'] = $message;
                            //return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                            return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with($return)->withMessage($message);
                        }
                        //return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with('return',$return)->withMessage($message);
                        //return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with('return',$return);
                        return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                    }
                }
                return view($viewName, $return)->withInput($request->input());

            } catch(\Exception $e) {
                /*echo '<pre>';
                var_dump('Exception');
                var_dump($e->getMessage());
                die();*/
                return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
            }
        }

        /**
         * Unpublish the specified adscaracts in storage.
         * @param UpdateAdsCaractsRequest|Request $request
         *
         * @param  int  $id
         */
        public function unpublishAds(Request $request)
        {
            //$viewName = app('request')->route()->getName();
            $viewName = 'dashboard';

            try {
                $return = [];

                if(Auth::check()) {
                    $input = $request->all();
                    $id = $input['id'];

                    $viewName = 'dashboard';
                    $currentLocale = config('app.locale');

                    $adscaracts = AdsCaracts::findOrFail($id);
                    $adscaracts->status = 'unpublished';

                    if($adscaracts->save()) {
                        $message_referrer = 'dashboard_unpublish_ads';
                        $message_title = trans('navigation.dashboard_unpublish_ads');
                        $message_text = trans('dashboard.successfully_updated');
                        $message_type = 'success';
                        Session::put('dashboard_message.referrer', $message_referrer);
                        Session::put('dashboard_message.title', $message_title);
                        Session::put('dashboard_message.text', $message_text);
                        Session::put('dashboard_message.type', $message_type);
                        $message = Session::get('dashboard_message');
                        $result = $this->getAdsListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                        $return +=  $result;
                        //$return['dashboard_message'] = $message_text;
                        $return['dashboard_message'] = $message;
                        //return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                        return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with($return)->withMessage($message);
                    }
                }
                return view($viewName, $return)->withInput($request->input());
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }

        /**
         * Reactivate the specified adscaracts in storage.
         * @param UpdateAdsCaractsRequest|Request $request
         *
         * @param  int  $id
         */
        public function reactivateAds(Request $request)
        {
            //$viewName = app('request')->route()->getName();
            $viewName = 'dashboard';

            try {
                $return = [];

                if(Auth::check()) {
                    $input = $request->all();
                    $id = $input['id'];

                    $viewName = 'dashboard';
                    $currentLocale = config('app.locale');

                    $adscaracts = AdsCaracts::findOrFail($id);
                    $adscaracts->status = 'in_moderation';

                    if($adscaracts->save()) {
                        $message_referrer = 'dashboard_reactivate_ads';
                        $message_title = trans('navigation.dashboard_reactivate_ads');
                        $message_text = trans('dashboard.successfully_updated');
                        $message_type = 'success';
                        Session::put('dashboard_message.referrer', $message_referrer);
                        Session::put('dashboard_message.title', $message_title);
                        Session::put('dashboard_message.text', $message_text);
                        Session::put('dashboard_message.type', $message_type);
                        $message = Session::get('dashboard_message');
                        $result = $this->getAdsListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                        $return +=  $result;
                        //$return['dashboard_message'] = $message_text;
                        $return['dashboard_message'] = $message;
                        //return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                        return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with($return)->withMessage($message);
                    }
                }
                return view($viewName, $return)->withInput($request->input());
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }

        /**
         * Show a Customer edit page
         *
         * @param $id
         *
         * @return \Illuminate\View\View
         */
        public function editCustomer()
        {
            $viewName = app('request')->route()->getName();

            $return = [];

            if(Auth::check()) {
                $user_id = Auth::user()->id;
                $user_email = Auth::user()->email;

                $user_type = Auth::user()->type;
                $usercaracts = '';
                switch($user_type) {
                    case 'private':
                        $usercaracts = PrivatesCaracts::where('user_id', $user_id)->pluck('id')->all();
                        break;
                    case 'dealer':
                        $usercaracts = DealersCaracts::where('user_id', $user_id)->pluck('id')->all();
                        break;
                    case 'commercial':
                        $usercaracts = CommercialsCaracts::where('user_id', $user_id)->pluck('id')->all();
                        break;
                    case 'customer':
                        $usercaracts = CustomersCaracts::where('user_id', $user_id)->pluck('id')->all();
                        break;
                }

                $customerscaracts = [];
                $customer_id = 0;
                if(isset($usercaracts)) {
                    $array = json_decode(json_encode($usercaracts), true);
                    if (is_array($array) && !empty($array[0])) {
                        $customer_id = $array[0];

                        switch($user_type) {
                            case 'private':
                                $customerscaracts = PrivatesCaracts::findOrFail($customer_id);
                                break;
                            case 'dealer':
                                $customerscaracts = DealersCaracts::findOrFail($customer_id);
                                break;
                            case 'commercial':
                                $customerscaracts = CommercialsCaracts::findOrFail($customer_id);
                                break;
                            case 'customer':
                                $customerscaracts = CustomersCaracts::findOrFail($customer_id);
                                break;
                        }
                        //$customerscaracts = CustomersCaracts::findOrFail($customer_id);
                        $country_id = !empty($customerscaracts->country_id) ? $customerscaracts->country_id : '';
                        $customerscaracts->country_id = $country_id;
                    }
                }

                /*$result = CustomersCaracts::where('user_id', $user_id)
                    // @TODO  : check if 'emails' check necessary
                    //->where('emails', $user_email)
                    ->pluck('id')->all();

                $result = json_decode(json_encode($result), true);
                $customerscaracts = [];
                $customer_id = 0;
                if(!empty($result)) {
                    $customer_id = $result[0];

                    $customerscaracts = CustomersCaracts::findOrFail($customer_id);

                    $country_id = !empty($customerscaracts->country_id) ? $customerscaracts->country_id : '';

                    //$country_code = SearchController::getCountryById($country_id, false)['code'];
                    //$customerscaracts->country_id = $country_code;
                    $customerscaracts->country_id = $country_id;
                }*/
                //$countries = Countries::orderBy("name", "asc")->pluck('name', 'code')->all();
                $countries = Countries::orderBy("name", "asc")->pluck('name', 'id')->all();

                $datas = [
                    'customerscaracts' => $customerscaracts,
                    'countries' => $countries,
                ];
                $return = $datas;
            }

            return view($viewName, $return);
        }

        /**
         * Update our user information
         *
         * @param Request $request
         * @param         $id
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function updateCustomer(Request $request)
        {
            try {
                $viewName = app('request')->route()->getName();

                $return = [];

                if(Auth::check()) {
                    $user_id = Auth::user()->id;
                    $user_email = Auth::user()->email;

                    $user_type = Auth::user()->type;
                    $usercaracts = '';
                    switch($user_type) {
                        case 'private':
                            $usercaracts = PrivatesCaracts::where('user_id', $user_id)->pluck('id')->all();
                            break;
                        case 'dealer':
                            $usercaracts = DealersCaracts::where('user_id', $user_id)->pluck('id')->all();
                            break;
                        case 'commercial':
                            $usercaracts = CommercialsCaracts::where('user_id', $user_id)->pluck('id')->all();
                            break;
                        case 'customer':
                            $usercaracts = CustomersCaracts::where('user_id', $user_id)->pluck('id')->all();
                            break;
                    }

                    $customerscaracts = [];
                    $customer_id = 0;
                    if(isset($usercaracts)) {
                        $array = json_decode(json_encode($usercaracts), true);
                        if (is_array($array) && !empty($array[0])) {
                            $customer_id = $array[0];

                            switch($user_type) {
                                case 'private':
                                    $customerscaracts = PrivatesCaracts::findOrFail($customer_id);
                                    break;
                                case 'dealer':
                                    $customerscaracts = DealersCaracts::findOrFail($customer_id);
                                    break;
                                case 'commercial':
                                    $customerscaracts = CommercialsCaracts::findOrFail($customer_id);
                                    break;
                                case 'customer':
                                    $customerscaracts = CustomersCaracts::findOrFail($customer_id);
                                    break;
                            }
                        }
                    }

                    /*$result = CustomersCaracts::where('user_id',$user_id)
                        // @TODO  : check if 'emails' check necessary
                        //->where('emails', $user_email)
                        ->pluck('id')->all();
                    $result = json_decode(json_encode($result), true);

                    $customerscaracts = [];
                    $customer_id = 0;
                    if(!empty($result)) {
                        $customer_id = $result[0];
                        $customerscaracts = CustomersCaracts::findOrFail($customer_id);
                    }*/

                    $input = $request->all();
                    $input['user_id'] = $user_id;
                    $country_code = !empty($input['country_id']) ? $input['country_id'] : '';
                    $country_id = SearchController::getCountry($country_code)['id'];
                    $input['country_id'] = $country_id;

                    $request = new Request($input);
                    $datasRequest = $request->all();

                    $rulesCustomersCaracts = CustomerCaractsRequest::rules();

                    $datasRequest['agree_emails'] = !empty($datasRequest['agree_emails']) ? 1 : 0;

                    $countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

                    $validator = Validator::make($datasRequest, $rulesCustomersCaracts);

                    if($validator->fails()) {
                        $errors = $validator->errors();
                        $array = json_decode(json_encode($this->getDefaults($datasRequest)), true);
                        $array['errors'] = $errors;

                        $datas = [
                            'customerscaracts' => $customerscaracts,
                            'countries' => $countries,
                        ];
                        $return = $datas + $array;
                        return view($viewName, $return)->withInput($request->input())->withErrors($errors, $this->errorBag());
                    } else {
                        $viewName = 'dashboard';
                        $currentLocale = config('app.locale');

                        //$request = $this->saveFiles($request);
                        $datasRequest = $request->all();
                        //$customerscaracts->update($datasRequest);

                        $customerUpdateOrCreate = false;
                        if(!empty($customerscaracts) && $customerscaracts->update($datasRequest)) {
                             /*
                            $inputProspectiveCustomers = array(
                                'ci_firstname' => !empty($datasRequest['firstname']) ? $datasRequest['firstname'] : null,
                                'ci_last_name' => !empty($datasRequest['name']) ? $datasRequest['name'] : null,
                                'ci_email' => !empty($datasRequest['emails']) ? $datasRequest['emails'] : null,
                                'ci_phone' => !empty($datasRequest['phone_1']) ? $datasRequest['phone_1'] : null,
                                'country_code' => $country_code,
                                'referrer' => 'dashboard_edit_customer',
                                'reference' => !empty($datasRequest['origin']) ? $datasRequest['origin'] : null
                            );
                            */
                            //$Customers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['emails']));
                            //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
                            /*
                            $ProspectiveCustomers = ProspectiveCustomers::Create($inputProspectiveCustomers);
                            if ($ProspectiveCustomers->save()) {
                                $updateBodProspectiveCustomerId = CustomersCaracts::find($customerscaracts['id']);
                                $updateBodProspectiveCustomerId->prospective_customer_id = $ProspectiveCustomers['id'];
                                $updateBodProspectiveCustomerId->save();
                            }
                            */
                            $customerUpdateOrCreate = true;
                        } else {
                            switch($user_type) {
                                case 'private':
                                    $CustomersCaracts = PrivatesCaracts::Create($datasRequest);
                                    break;
                                case 'dealer':
                                    $CustomersCaracts = DealersCaracts::Create($datasRequest);
                                    break;
                                case 'commercial':
                                    $CustomersCaracts = CommercialsCaracts::Create($datasRequest);
                                    break;
                                case 'customer':
                                    $CustomersCaracts = CustomersCaracts::Create($datasRequest);
                                    break;
                            }
                            //$CustomersCaracts = CustomersCaracts::Create($datasRequest);
                            if ($CustomersCaracts->save()) {
                                $customerUpdateOrCreate = true;
                            }
                        }
                        if($customerUpdateOrCreate) {
                            $message_referrer = 'dashboard_update_customer';
                            $message_title = trans('navigation.dashboard_update_customer');
                            $message_text = trans('dashboard.your_account_details') . '. ' . trans('dashboard.successfully_updated');
                            $message_type = 'success';
                            Session::put('dashboard_message.referrer', $message_referrer);
                            Session::put('dashboard_message.title', $message_title);
                            Session::put('dashboard_message.text', $message_text);
                            Session::put('dashboard_message.type', $message_type);
                            $message = Session::get('dashboard_message');
                            $result = $this->getBodListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                            if(Auth::user()->type == 'private') {
                                $result += $this->getAdsListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                            }
                            $return +=  $result;
                            //$return['dashboard_message'] = $message_text;
                            $return['dashboard_message'] = $message;
                            //return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                            return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with($return)->withMessage($message);
                        }
                        return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                    }
                }
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }


        /**
         * Show a Account edit page
         *
         * @param $id
         *
         * @return \Illuminate\View\View
         */
        public function editAccount()
        {
            $viewName = app('request')->route()->getName();

            $return = [];

            if(Auth::check()) {
                $user_id = Auth::user()->id;
                $user_email = Auth::user()->email;

                $user_type = Auth::user()->type;
                $usercaracts = '';
                switch($user_type) {
                    case 'private':
                        $usercaracts = PrivatesCaracts::where('user_id', $user_id)->pluck('id')->all();
                        break;
                    case 'dealer':
                        $usercaracts = DealersCaracts::where('user_id', $user_id)->pluck('id')->all();
                        break;
                    case 'commercial':
                        $usercaracts = CommercialsCaracts::where('user_id', $user_id)->pluck('id')->all();
                        break;
                    case 'customer':
                        $usercaracts = CustomersCaracts::where('user_id', $user_id)->pluck('id')->all();
                        break;
                }

                $accountscaracts = [];
                $account_id = 0;
                if(isset($usercaracts)) {
                    $array = json_decode(json_encode($usercaracts), true);
                    if (is_array($array) && !empty($array[0])) {
                        $account_id = $array[0];

                        switch($user_type) {
                            case 'private':
                                $accountscaracts = PrivatesCaracts::findOrFail($account_id);
                                break;
                            case 'dealer':
                                $accountscaracts = DealersCaracts::findOrFail($account_id);
                                break;
                            case 'commercial':
                                $accountscaracts = CommercialsCaracts::findOrFail($account_id);
                                break;
                            case 'customer':
                                $accountscaracts = CustomersCaracts::findOrFail($account_id);
                                break;
                        }
                        $country_id = !empty($accountscaracts->country_id) ? $accountscaracts->country_id : '';
                        $accountscaracts->country_id = $country_id;
                    }
                }

                $countries = Countries::orderBy("name", "asc")->pluck('name', 'id')->all();

                $datas = [
                    'accountscaracts' => $accountscaracts,
                    'countries' => $countries,
                ];
                $return = $datas;
            }

            return view($viewName, $return);
        }

        /**
         * Update our user information
         *
         * @param Request $request
         * @param         $id
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function updateAccount(Request $request)
        {
            try {
                $viewName = app('request')->route()->getName();

                $return = [];

                if(Auth::check()) {
                    $user_id = Auth::user()->id;
                    $user_email = Auth::user()->email;

                    $user_type = Auth::user()->type;
                    $usercaracts = '';
                    switch($user_type) {
                        case 'private':
                            $usercaracts = PrivatesCaracts::where('user_id', $user_id)->pluck('id')->all();
                            break;
                        case 'dealer':
                            $usercaracts = DealersCaracts::where('user_id', $user_id)->pluck('id')->all();
                            break;
                        case 'commercial':
                            $usercaracts = CommercialsCaracts::where('user_id', $user_id)->pluck('id')->all();
                            break;
                        case 'customer':
                            $usercaracts = CustomersCaracts::where('user_id', $user_id)->pluck('id')->all();
                            break;
                    }

                    $accountscaracts = [];
                    $account_id = 0;
                    if(isset($usercaracts)) {
                        $array = json_decode(json_encode($usercaracts), true);
                        if (is_array($array) && !empty($array[0])) {
                            $account_id = $array[0];

                            switch($user_type) {
                                case 'private':
                                    $accountscaracts = PrivatesCaracts::findOrFail($account_id);
                                    break;
                                case 'dealer':
                                    $accountscaracts = DealersCaracts::findOrFail($account_id);
                                    break;
                                case 'commercial':
                                    $accountscaracts = CommercialsCaracts::findOrFail($account_id);
                                    break;
                                case 'customer':
                                    $accountscaracts = CustomersCaracts::findOrFail($account_id);
                                    break;
                            }
                        }
                    }

                    $input = $request->all();
                    $input['user_id'] = $user_id;

                    $country_id = '';
                    $country_code = !empty($input['country_id']) ? $input['country_id'] : '';
                    $country_id = SearchController::getCountry($country_code)['id'];
                    $input['country_id'] = $country_id;

                    $request = new Request($input);
                    $datasRequest = $request->all();

                    //$rulesAccountsCaracts = CustomerCaractsRequest::rules();
                    switch($user_type) {
                        case 'private':
                            $rulesAccountsCaracts = PrivatesCaractsRequest::rules();
                            break;
                        case 'dealer':
                            $rulesAccountsCaracts = DealersCaractsRequest::rules();
                            break;
                        case 'commercial':
                            $rulesAccountsCaracts = CommercialsCaractsRequest::rules();
                            break;
                        case 'customer':
                            $rulesAccountsCaracts = CustomerCaractsRequest::rules();
                            break;
                    }

                    $datasRequest['agree_emails'] = !empty($datasRequest['agree_emails']) ? 1 : 0;

                    $countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();

                    $validator = Validator::make($datasRequest, $rulesAccountsCaracts);

                    if($validator->fails()) {
                        $errors = $validator->errors();
                        $array = json_decode(json_encode($this->getDefaults($datasRequest)), true);
                        $array['errors'] = $errors;

                        $datas = [
                            'accountscaracts' => $accountscaracts,
                            'countries' => $countries,
                        ];
                        $return = $datas + $array;
                        return view($viewName, $return)->withInput($request->input())->withErrors($errors, $this->errorBag());
                    } else {
                        $viewName = 'dashboard';
                        $currentLocale = config('app.locale');

                        $datasRequest = $request->all();

                        $accountUpdateOrCreate = false;
                        if(!empty($accountscaracts) && $accountscaracts->update($datasRequest)) {
                            $accountUpdateOrCreate = true;
                        } else {
                            switch($user_type) {
                                case 'private':
                                    $AccountsCaracts = PrivatesCaracts::Create($datasRequest);
                                    break;
                                case 'dealer':
                                    $AccountsCaracts = DealersCaracts::Create($datasRequest);
                                    break;
                                case 'commercial':
                                    $AccountsCaracts = CommercialsCaracts::Create($datasRequest);
                                    break;
                                case 'customer':
                                    $AccountsCaracts = CustomersCaracts::Create($datasRequest);
                                    break;
                            }
                            if ($AccountsCaracts->save()) {
                                $accountUpdateOrCreate = true;
                            }
                        }
                        if($accountUpdateOrCreate) {
                            $message_referrer = 'dashboard_edit_account';
                            $message_title = trans('navigation.dashboard_update_account');
                            $message_text = trans('dashboard.your_account_details') . '. ' . trans('dashboard.successfully_updated');
                            $message_type = 'success';
                            Session::put('dashboard_message.referrer', $message_referrer);
                            Session::put('dashboard_message.title', $message_title);
                            Session::put('dashboard_message.text', $message_text);
                            Session::put('dashboard_message.type', $message_type);
                            $message = Session::get('dashboard_message');
                            $result = $this->getBodListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                            if(Auth::user()->type == 'private') {
                                $result += $this->getAdsListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                            }
                            $return +=  $result;
                            $return['dashboard_message'] = $message;
                            return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with($return)->withMessage($message);
                        }
                        return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                    }
                }
            } catch(\Exception $e) {
                /*var_dump($e->getMessage());
                die();*/
                return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
            }
        }

        /**
         * UpdatePassword
         *
         * @param Request $request
         * @param         $id
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function updatePassword(Request $request)
        {
            try {
                $viewName = app('request')->route()->getName();

                $return = [];

                if(Auth::check()) {
                    $user_id = Auth::user()->id;
                    $user_email = Auth::user()->email;
                    $user_username = Auth::user()->username;

                    $input = $request->all();

                    if($user_email == $input['email']) {
                        $input['username'] = $user_username;
                        //$user = User::findOrFail($user_id);
                        $rulesUser = [
                            'username' => 'required|max:255',
                            'email'    => 'required|email|max:255',
                            'password' => 'required|confirmed|min:6',
                        ];

                        $request = new Request($input);
                        $datasRequest = $request->all();

                        $validator = Validator::make($datasRequest, $rulesUser);
                        if($validator->fails()) {
                            $errors = $validator->errors();
                            $array['errors'] = $errors;
                            $return = $array;
                            return view($viewName, $return)->with($request->input())->withErrors($errors, $this->errorBag());
                        } else {
                            $viewName = 'dashboard';
                            $currentLocale = config('app.locale');

                            $user = User::findOrFail($user_id);

                            if($updatePassword = $user->fill([
                                'password' => Hash::make($request->password)
                            ])->save()) {
                                $message_referrer = 'dashboard_change_password';
                                $message_title = trans('navigation.dashboard_change_password');
                                $message_text = trans('passwords.updated');
                                $message_type = 'success';
                                Session::put('dashboard_message.referrer', $message_referrer);
                                Session::put('dashboard_message.title', $message_title);
                                Session::put('dashboard_message.text', $message_text);
                                Session::put('dashboard_message.type', $message_type);
                                $message = Session::get('dashboard_message');
                                $result = $this->getBodListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                                if(Auth::user()->type == 'private') {
                                    $result += $this->getAdsListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                                }
                                $return +=  $result;
                                //$return['dashboard_message'] = $message_text;
                                $return['dashboard_message'] = $message;
                                //return redirect(trans_route($currentLocale, 'routes.'.$viewName));
                                return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with($return)->withMessage($message);
                            }
                        }
                    }

                }
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
