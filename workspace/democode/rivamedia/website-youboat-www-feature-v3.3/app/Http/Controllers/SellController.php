<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Requests\AdsCaractsFormRequest;
    //use App\Http\Requests\CreateAdsCaractsRequest;
    //use App\Http\Requests\UpdateAdsCaractsRequest;

    use App\Http\Controllers\SearchController as Search;
    use App\Http\Controllers\ForsaleController;

    use App\User;
    //use App\Ads;
    use App\AdsCaracts;
    use App\CustomersCaracts;
    use App\CommercialsCaracts;
    use App\DealersCaracts;
    use App\PrivatesCaracts;
    use App\ProspectiveCustomers;

    use App\Countries;
    use App\CountryContracts;

    use Redirect;
    use Mail;
    use Auth;
    use File;
    use Session;
    use Carbon\Carbon;

    use Illuminate\Support\Facades\Validator;

    //class SellController extends ForsaleController
    class SellController extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            $this->middleware(['clearcache']);
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            //$action = app('request')->route()->getAction();
            //$viewName = Route::currentRouteName();
            $viewName = app('request')->route()->getName();
            $pageTitle = trans('navigation.' . $viewName);

            return view($viewName, compact('pageTitle'));
        }

        /**
         * Display a listing of adscaracts
         *
         * @param Request $request
         *
         * @return \Illuminate\View\View
         */
        public static function getAdsList($request)
        {
            $input = $request->all();

            $search_query = null;
            if (isset($input['search']["value"])) {
                $search_query = $input['search']["value"];
            }

            $input['max'] = isset($input['length']) ? $input['length'] : 100;
            $sort_by = $sort_by_request = 'updated_at';
            $sort_direction             = 'desc';
            if (isset($input['order'])) {
                $index = count($input['order'])-1;
                $order_col = $input['order'][$index]['column'];
                $order_dir = $input['order'][$index]['dir'];
                $order_id = $input['columns'][$order_col]['data'];
                $input['sort_by'] = $order_id . '-' . $order_dir;
            }
            if (isset($input['sort_by'])) {
                list($sort_by, $sort_direction) = explode("-", $input['sort_by']);
                $from = [
                    "adstypes_name","categories_name","subcategories_name","manufacturers_name","models_name","countries_name",
                    'year_built', 'model'
                ];
                $to = [
                    "adstypes_id","categories_ids","subcategories_ids","manufacturers_id","models_id","countries_id",
                    'ad_year_built', 'ad_model_name'
                ];
                $sort_by_request = str_replace($from, $to, $sort_by);
            }
            if(isset($input['start']) && $input['start'] == 0) {
                $current_page = 1;
            } else {
                $current_page = isset($input['page']) ? $input['page'] : 1;
                //$current_page = isset($input['start']) && isset($input['length']) ? ceil($input['start'] / $input['length']) + 1 : 1;
            }

            $max_query = isset($input['length']) ? $input['length'] : 100;
            $start = isset($input['start']) ? $input['start'] : $max_query * ($current_page -1);

            $user_type = Auth::user()->type;

            //if('admin' == $user_type || 'commercial' == $user_type) {

            //$WhereRaw = 'status IS NOT NULL';

            $WhereRaw = 'status <> "" ';
            if('private' == $user_type || 'dealer' == $user_type) {
                $dealercaracts_id = '';
                $dealercaracts = DealersCaracts::where('user_id', Auth::user()->id)
                    ->pluck('id')->all();
                $dealercaracts_id = $dealercaracts[0];
                $WhereRaw .= ' AND dealerscaracts_id = ' . $dealercaracts_id . ' ';
            }
            //$WhereRaw .= 'status = "active" ';
            //$WhereRaw .= 'AND ad_title <> "" ';
            if($search_query) {
                if (!empty($countries_id)) {
                    $ad_country_code = $countries_id;
                    $countries_code = !empty($input['countries_id']) ? Search::getCountryById($input['countries_id'], false)['code'] : 'uk';
                    //$countries_code = !empty($input['countries_id']) ? $this->getCountryById($input['countries_id'], false)['code'] : 'uk';
                    //$WhereRaw .= 'AND ' . 'countries_id = "' . $countries_id . '" ';
                    $from = ['gb'];
                    $to = ['uk'];
                    $ad_country_code = str_replace($from, $to, $ad_country_code);
                    $WhereRaw .= 'AND ' . 'ad_country_code = "' . $countries_code . '" ';
                }
                $WhereRaw .= "AND ( ";
                $WhereRaw .= "ad_title LIKE '%$search_query%' ";
                $WhereRaw .= "OR ";
                $WhereRaw .= "ad_manufacturer_name LIKE '%$search_query%' ";
                $WhereRaw .= "OR ";
                $WhereRaw .= "ad_model_name LIKE '%$search_query%' ";
                $WhereRaw .= "OR ";
                $WhereRaw .= "ad_dealer_name LIKE '%$search_query%' ";
                $WhereRaw .= ") ";
            }
            if(isset($input['columns'])) {
                foreach($input['columns'] as $key => $value) {
                    if(!empty($input['columns'][$key]["search"]["value"])) {
                        $value = $input['columns'][$key]['search']['value'];
                        $key_data = $input['columns'][$key]["data"];
                        $action = 'REGEXP';
                        if(preg_match('/_id$/', $key_data) || preg_match('/_ids$/', $key_data) || is_numeric($value)) {
                            $action = '=';
                        }

                        switch($key_data) {
                            case 'adstypes_name':
                                $adstype = !empty($value) ? (is_numeric($value) ? json_decode(json_encode(Search::getAdsType($value, true, true)), true) : json_decode(json_encode(Search::getAdsType($value, true, true)), true)[0]) : null;
                                //$adstype = !empty($value) ? (is_numeric($value) ? json_decode(json_encode($this->getAdsType($value, true, true)), true) : json_decode(json_encode($this->getAdsType($value, true, true)), true)[0]) : null;
                                $id = !empty($adstype['id']) ? $adstype['id'] : null;
                                $value = $id;
                                $key_data = 'adstypes_id';
                                $action = '=';
                                break;
                            case 'categories_name':
                                $category = !empty($value) ? (is_numeric($value) ? json_decode(json_encode(Search::getCategory($value, true, true)), true) : json_decode(json_encode(Search::getCategory($value, true, true)), true)[0]) : null;
                                //$category = !empty($value) ? (is_numeric($value) ? json_decode(json_encode($this->getCategory($value, true, true)), true) : json_decode(json_encode($this->getCategory($value, true, true)), true)[0]) : null;
                                $id = !empty($category['id']) ? $category['id'] : null;
                                $value = $id;
                                $key_data = 'categories_ids';
                                $action = '=';
                                break;
                            case 'subcategories_name':
                                $subcategory = !empty($value) ? (is_numeric($value) ? json_decode(json_encode(Search::getSubCategory($value, true, true)), true) : json_decode(json_encode(Search::getSubCategory($value, true, true)), true)[0]) : null;
                                //$subcategory = !empty($value) ? (is_numeric($value) ? json_decode(json_encode($this->getSubCategory($value, true, true)), true) : json_decode(json_encode($this->getSubCategory($value, true, true)), true)[0]) : null;
                                $id = !empty($subcategory['id']) ? $subcategory['id'] : null;
                                $value = $id;
                                $key_data = 'subcategories_ids';
                                $action = '=';
                                break;
                            case 'manufacturers_name':
                                $manufacturer = !empty($value) ? json_decode(json_encode(Search::getManufacturerByName($value, true, true)), true) : null;
                                //$manufacturer = !empty($value) ? json_decode(json_encode($this->getManufacturerByName($value, true, true)), true) : null;
                                $ids = [];
                                if(is_array($manufacturer)) {
                                    foreach($manufacturer as $k => $v) {
                                        array_push($ids, $v['id']);
                                    }
                                }
                                $value = !empty($ids) ? $ids : null;
                                $key_data = 'manufacturers_id';
                                $action = '=';
                                break;
                            case 'models_name':
                                $model = !empty($value) ? json_decode(json_encode(Search::getModelByName($value, true, true)), true) : null;
                                //$model = !empty($value) ? json_decode(json_encode($this->getModelByName($value, true, true)), true) : null;
                                $ids = [];
                                if(is_array($model)) {
                                    foreach($model as $k => $v) {
                                        array_push($ids, $v['id']);
                                    }
                                }
                                $value = !empty($ids) ? $ids : null;
                                $key_data = 'models_id';
                                $action = '=';
                                break;
                            case 'manufacturersengines_name':
                                $manufacturer_engine = !empty($value) ? json_decode(json_encode(Search::getManufacturerEngineByName($value, true, true)), true) : null;
                                //$manufacturer_engine = !empty($value) ? json_decode(json_encode($this->getManufacturerEngineByName($value, true, true)), true) : null;
                                $ids = [];
                                if(is_array($manufacturer_engine)) {
                                    foreach($manufacturer_engine as $k => $v) {
                                        array_push($ids, $v['id']);
                                    }
                                }
                                $value = !empty($ids) ? $ids : null;
                                $key_data = 'manufacturersengines_id';
                                $action = '=';
                                break;
                            case 'modelsengines_name':
                                $model_engine = !empty($value) ? json_decode(json_encode(Search::getModelEngineByName($value, true, true)), true) : null;
                                //$model_engine = !empty($value) ? json_decode(json_encode($this->getModelEngineByName($value, true, true)), true) : null;
                                $ids = [];
                                if(is_array($model_engine)) {
                                    foreach($model_engine as $k => $v) {
                                        array_push($ids, $v['id']);
                                    }
                                }
                                $value = !empty($ids) ? $ids : null;
                                $key_data = 'modelsengines_id';
                                $action = '=';
                                break;
                            case 'dealerscaracts_name':
                                $dealer = !empty($value) ? ((is_numeric($value) ? json_decode(json_encode(Search::getDealerCaracts($value, true, true)), true) : json_decode(json_encode(Search::getDealerCaracts($value, true, true)), true)[0])) : null;
                                //$dealer = !empty($value) ? ((is_numeric($value) ? json_decode(json_encode($this->getDealerCaracts($value, true, true)), true) : json_decode(json_encode($this->getDealerCaracts($value, true, true)), true)[0])) : null;
                                $id = !empty($dealer['id']) ? $dealer['id'] : null;
                                if (!empty($id)) {
                                    $value = $id;
                                    $key_data = 'dealerscaracts_id';
                                    $action = '=';
                                } else {
                                    $key_data = 'ad_dealer_name';
                                }

                                break;
                            case 'countries_name':
                                $country = !empty($value) ? Search::getCountry($value, true, true) : null;
                                //$country = !empty($value) ? $this->getCountry($value, true, true) : null;
                                $id = !empty($country['id']) ? $country['id'] : null;
                                $value = $id;
                                $key_data = 'countries_id';
                                $action = '=';
                                break;
                        }
                        if(!empty($value)) {
                            if(is_array($value)) {
                                $WhereRawArray = "";
                                foreach($value as $k => $v) {
                                    $WhereRawArray .= " " . $key_data . " " . $action . " '$v' OR ";
                                }
                                $WhereRawArray .= "";
                                $WhereRaw .= "AND (" . preg_replace('/OR$/', '', $WhereRawArray) . ") ";
                            } elseif ('=' == $action || 'REGEXP' == $action) {
                                $WhereRaw .= "AND " . $key_data . " " . $action . " '$value' ";
                            } else {
                                $WhereRaw .= "AND " . $key_data . " " . $action . " '%$value%' ";

                            }
                        }
                    }
                }
            }

            $sell_type = (!empty($input['sell_type']) ? $input['sell_type'] : null);

            $min_length = (!empty($input['min_length']) ? $input['min_length'] : null);
            $max_length = (!empty($input['max_length']) ? $input['max_length'] : null);

            $min_width = (!empty($input['min_width']) ? $input['min_width'] : null);
            $max_width = (!empty($input['max_width']) ? $input['max_width'] : null);

            $min_year_built = (!empty($input['min_year_built']) ? $input['min_year_built'] : null);
            $max_year_built = (!empty($input['max_year_built']) ? $input['max_year_built'] : null);

            $min_ad_price = (!empty($input['min_ad_price']) ? $input['min_ad_price'] : null);
            $max_ad_price = (!empty($input['$max_ad_price']) ? $input['$max_ad_price'] : null);

            $min_engine_power = (!empty($input['min_engine_power']) ? $input['min_engine_power'] : null);
            $max_engine_power = (!empty($input['max_engine_power']) ? $input['max_engine_power'] : null);
            $type_engine_power = (!empty($input['type_engine_power']) ? $input['type_engine_power'] : null);

            $appCountryCode = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
            $countries_id = (!empty($input['countries_id']) ? Search::getCountry($input['countries_id'])['id'] : Search::getCountry(config('youboat.' . $appCountryCode . '.country_code'))['id']);
            //$countries_id = (!empty($input['countries_id']) ? $this->getCountry($input['countries_id'])['id'] : $this->getCountry(config('youboat.' . $appCountryCode . '.country_code'))['id']);
            $county_id = !empty($input['county_id']) && '' != $input['county_id'] ? $input['county_id'] : null;

            $countries_code = !empty($input['countries_id']) ? Search::getCountryById($input['countries_id'], false)['code'] : 'uk';
            //$countries_code = !empty($input['countries_id']) ? $this->getCountryById($input['countries_id'], false)['code'] : 'uk';

            if (!empty($sell_type)) {
                $WhereRaw .= 'AND ' . 'sell_type = "' . $sell_type . '" ';
            }
            if (!empty($min_length)) {
                $WhereRaw .= 'AND ' . 'ad_length_meter >= ' . $min_length . ' ';
            }
            if (!empty($max_length)) {
                $WhereRaw .= 'AND ' . 'ad_length_meter <= ' . $max_length . ' ';
            }

            if (!empty($min_width)) {
                $WhereRaw .= 'AND ' . 'ad_width_meter >= ' . $min_width . ' ';
            }
            if (!empty($max_width)) {
                $WhereRaw .= 'AND ' . 'ad_width_meter <= ' . $max_width . ' ';
            }

            if (!empty($min_year_built)) {
                $WhereRaw .= 'AND ' . 'ad_year_built >= ' . $min_year_built . ' ';
            }
            if (!empty($max_year_built)) {
                $WhereRaw .= 'AND ' . 'ad_year_built <= ' . $max_year_built . ' ';
            }

            if (!empty($min_ad_price)) {
                $WhereRaw .= 'AND ' . 'ad_price >= ' . $min_ad_price . ' ';
            }
            if (!empty($max_ad_price)) {
                $WhereRaw .= 'AND ' . 'ad_price <= ' . $max_ad_price . ' ';
            }

            if (!empty($min_engine_power)) {
                $WhereRaw .= 'AND ' . 'ad_engine_power >= ' . $min_engine_power . ' ';
            }
            if (!empty($max_engine_power)) {
                $WhereRaw .= 'AND ' . 'ad_engine_power <= ' . $max_engine_power . ' ';
            }
            if (!empty($type_engine_power)) {
                $WhereRaw .= 'AND ' . 'ad_type_engine_power = "' .  $type_engine_power . '"" ';
            }

            //if (!empty($countries_id)) {
            //$WhereRaw .= 'AND ' . 'countries_id = "' . $countries_id . '" ';
            //}
            if (!empty($county_id)) {
                $WhereRaw .= 'AND ' . 'county_id = "' . $county_id . '" ';
            }
            if (!empty($countries_code)) {
                $WhereRaw .= 'AND ' . 'ad_country_code = "' . $countries_code . '" ';
            }

            $selectValues = "";
            $AdsCaracts = AdsCaracts::whereRaw($WhereRaw)
                //->where('status', 'active')
                ->select(
                    'id',
                    'ad_title',
                    'adstypes_id','categories_ids',
                    'manufacturers_id','models_id',
                    'manufacturersengines_id','modelsengines_id',
                    'countries_id',
                    'ad_price',
                    'updated_at',
                    'status',
                    'ad_dealer_name',
                    'dealerscaracts_id',
                    'subcategories_ids',
                    'sell_type'
                )
                ->take($max_query)
                ->orderBy($sort_by_request, $sort_direction)
                //->orderBy('updated_at', 'desc')
                ->paginate($max_query);

            $AdsCaracts->appends(['sort_by' => $sort_by_request . '-' . $sort_direction]);
            $AdsCaracts->appends(['page' => $current_page]);
            /*} else {
                $WhereRaw = 'status <> ""';
                if('private' == $user_type || 'dealer' == $user_type) {
                    $dealercaracts_id = '';
                    $dealercaracts = DealersCaracts::where('user_id', Auth::user()->id)
                        ->pluck('id')->all();
                    $dealercaracts_id = $dealercaracts[0];
                    $WhereRaw .= ' AND dealerscaracts_id = ' . $dealercaracts_id;
                }

                $AdsCaracts = AdsCaracts::whereRaw($WhereRaw)
                    ->select(
                        'id',
                        'ad_title',
                        'adstypes_id','categories_ids',
                        'manufacturers_id','models_id',
                        'manufacturersengines_id','modelsengines_id',
                        'ad_price',
                        'countries_id',
                        'updated_at',
                        'status',
                        'ad_dealer_name',
                        'dealerscaracts_id',
                        'subcategories_ids',
                        'sell_type'
                    )
                    ->take($max_query)
                    ->orderBy($sort_by_request, $sort_direction)
                    //->orderBy('updated_at', 'desc')
                    ->paginate($max_query);
            }*/

            return $AdsCaracts;
        }

        /**
         * Show the form for creating a new adscaracts
         *
         * @return \Illuminate\View\View
         */
        public function create(Request $request)
        {
            //var_dump('create');
            $currentLocale = config('app.locale');
            $request->session()->forget('ad_ref');
            $request->session()->forget('country_contracts');
            $viewName = app('request')->route()->getName();
            //$viewName = 'sell';

            if (Auth::check() && Auth::user()->type != 'private' && Auth::user()->type != 'customer') {
                Auth::logout();
                //$request->session()->forget('ad_ref');
                //$request->session()->forget('country_contracts');
                Session::flush();
                $logout_link = link_trans_route('logout', trans('navigation.logout'), ['class'=>"btn btn-md btn-warning"]);
                $message = trans('sell.must_be_logged_private_account') . '<p class="text-center">' . $logout_link . '</p>';
                //return redirect(trans_route($currentLocale, 'routes.sell'))->withErrors(['message' => $message]);
                return redirect()->back()->withInput($request->input())->withMessage($message);
            }
            try {
                if ($request) {
                    $input = $request->all();
                    $dealerscaracts_id = '';
                    $selltypes = getEnumValues('adscaracts', 'sell_type');
                    $countries = Countries::orderBy("name", "asc")->pluck('name', 'id')->all();

                    $ForsaleController = new ForsaleController();
                    $getDefaults = $ForsaleController->getDefaults($input);

                    $user_caracts = [];
                    $usernames = [];
                    $user_id = '';

                    if (Auth::check()) {
                        $user_type = Auth::user()->type;

                        $user_id = !empty(old('user_id')) ? old('user_id') : null;
                        if (empty($user_id) && !empty($input['user_id'])) {
                            $user_id = empty($user_id) && !empty($input['user_id']);
                        }

                        if (empty($user_id) && Auth::user()->id && $user_type != 'admin' && $user_type != 'commercial') {
                            $user_id = Auth::user()->id;
                        }

                        if (
                        (!empty($user_id) && ('admin' == $user_type || 'commercial' == $user_type) || 'private' == $user_type)
                        ) {
                            $usernames = User::where('type', 'private')
                                //->where('status', 'active')
                                ->where('users.id', $user_id)
                                ->orderBy('username', 'asc')
                                ->lists('username', 'id');
                        } else if ('private' == $user_type && $user_id != Auth::user()->id) {
                            //return redirect()->route(LaravelLocalization::transRoute('routes.sell'));
                            //return redirect(trans_route($currentLocale, 'routes.sell'));
                        }

                        if (isset($usernames)) {
                            $array = json_decode(json_encode($usernames), true);
                            if (is_array($array) && isset($array)) {
                                $usernames = $array;
                            }
                        } else {
                            $usernames = [];
                        }

                        $country_id = '';
                        $ad_dealer_name = '';

                        if (!empty($user_id)) {
                            $user = User::findOrFail($user_id);
                            $user_type = $user->type;
                            $privatescaracts = [];
                            switch ($user_type) {
                                /*case 'admin':
                                    $usercaracts = [];
                                    break;*/
                                case 'private':
                                    $usercaracts = PrivatesCaracts::where('user_id', $user_id)->get();
                                    break;
                                /*case 'dealer':
                                    $usercaracts = DealersCaracts::where('user_id', $user_id)->get();
                                    break;*/
                                case 'customer':
                                    $usercaracts = CustomersCaracts::where('user_id', $user_id)->get();
                                    break;
                                /*case 'commercial':
                                    $usercaracts = CommercialsCaracts::where('user_id', $user_id)->get();
                                    break;*/
                            }
                            if(isset($usercaracts)) {
                                $array = json_decode(json_encode($usercaracts), true);
                                if (is_array($array) && !empty($array[0])) {
                                    $privatescaracts = $array[0];
                                }
                            }
                            $privatescaracts['type'] = $user_type;
                            $privatescaracts['email'] = Auth::user()->email;

                            if (is_array($privatescaracts) && array_key_exists('id', $privatescaracts)) {
                                $dealerscaracts_id = $privatescaracts['id'];
                                $ad_dealer_name = isset($privatescaracts['denomination']) ? $privatescaracts['denomination'] : '';

                                if (empty($ad_dealer_name) && (isset($privatescaracts['firstname']) || isset($privatescaracts['name']))) {
                                    $ad_dealer_name = $privatescaracts['name'] . (!empty($privatescaracts['firstname']) ? ' ' . $privatescaracts['firstname'] : '');
                                }
                                $ad_dealer_name = trim(ucwords(mb_strtolower($ad_dealer_name)));
                                $country_id = array_key_exists('country_id', $privatescaracts) && !empty($privatescaracts['country_id']) ? $privatescaracts['country_id'] : '';
                            } else {
                                $message = '<p class="text-danger text-center">' . trans('ads_caracts.dealer_caracts_missing');
                                $message .= '<br>' . '<a href="' . url(trans_route($currentLocale, 'routes.dashboard_edit_account')) . '" title="' . trans('navigation.edit') . ' '  . trans('dashboard.your_account_details') . '" class="btn btn-primary"><i class="fa fa-edit fa-fw"></i>' . trans('navigation.edit') . ' '  . trans('dashboard.your_account_details')  . '</a>';
                                $message .= '</p>';

                                $message_referrer = 'dashboard';
                                $message_title = trans('navigation.sell');
                                $message_text = $message;
                                $message_type = 'warning';
                                Session::put('dashboard_message.referrer', $message_referrer);
                                Session::put('dashboard_message.title', $message_title);
                                Session::put('dashboard_message.text', $message_text);
                                Session::put('dashboard_message.type', $message_type);
                                $message = Session::get('dashboard_message');
                                $return = $request->input();
                                return redirect()->back()->withInput($return)->withMessage($message);
                            }
                        }
                    }
                    if (empty($country_id) && !empty($input['countries_id'])) {
                        $country_id = $input['countries_id'];
                    } else if (empty($country_id)) {
                        $country_id = 77; // uk by default
                    }

                    /*$ad_country_code = '';
                    $getCountryById = Search::getCountryById($country_id, false);
                    //$getCountryById = $this->getCountryById($country_id, false);
                    if (is_array($getCountryById) && array_key_exists('code', $getCountryById)) {
                        $ad_country_code = mb_strtolower($getCountryById['code']);
                    }
                    $from = ['gb'];
                    $to = ['uk'];
                    $ad_country_code = str_replace($from, $to, $ad_country_code);*/

                    $ad_country_code = '';
                    if(is_numeric($country_id) && !empty($country_id)) {
                        $getCountryById = SearchController::getCountryById($country_id, false);
                        //$getCountryById = $this->getCountryById($country_id, false);
                        if (is_array($getCountryById) && array_key_exists('code', $getCountryById)) {
                            $ad_country_code = mb_strtolower($getCountryById['code']);
                        }
                        $from = ['gb'];
                        $to = ['uk'];
                        $ad_country_code = str_replace($from, $to, $ad_country_code);
                    }

                    $ad_referrer = 'YB';
                    $formPosted = '';
                    $ready_to_pay = !empty($input['ready_to_pay']) ? $input['ready_to_pay'] : false;

                    $datas = compact(
                        'privatescaracts',
                        'ready_to_pay',
                        'formPosted',
                        'user_caracts',
                        'usernames',
                        'user_id',
                        //'ad_dealer_name',
                        //'dealerscaracts_id',
                        'ad_referrer',
                        'ad_country_code',
                        'selltypes',
                        'country_id',
                        'countries',
                        'status'
                    );

                    if (!empty(old('manufacturers_id'))) {
                        $getDefaults['models'] = Search::getModels(old('manufacturers_id'));
                        //$getDefaults['models'] = $this->getModels(old('manufacturers_id'));
                    }

                    $getDefaults['manufacturersengines'] = '';
                    $getDefaults['modelsengines'] = '';
                    $return = $datas + $getDefaults;

                    unset($usernames, $array, $selltypes, $country_id, $countries,
                        //$status,
                        $ad_country_code,
                        $ad_referrer, $privatescaracts,
                        //$dealerscaracts_id, $ad_dealer_name,
                        $datas, $getDefaults);

                    return view($viewName, $return);
                } else {
                    $ForsaleController = new ForsaleController();
                    $getDefaults = $ForsaleController->getDefaults($input);
                    $getDefaults['ready_to_pay'] = false;
                    return view($viewName, $getDefaults);
                }

            } catch(\Exception $e) {
                /*var_dump($viewName);
                var_dump(Auth::user()->type);
                var_dump("Exception");
                var_dump($e);
                var_dump($e->getMessage());
                die();*/
                return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
            }
        }

        /**
         * Store a newly created contact in storage.
         *m
         * @param Request|Request $request
         */
        public function store(Request $request)
        {
            //var_dump('store');
            $currentLocale = config('app.locale');

            if (Auth::check() && Auth::user()->type != 'private' && Auth::user()->type != 'customer') {
                Auth::logout();
                Session::flush();
                $logout_link = link_trans_route('logout', trans('navigation.logout'), ['class'=>"btn btn-md btn-warning"]);
                $message = trans('sell.must_be_logged_private_account') . '<p class="text-center">' . $logout_link . '</p>';
                //$message = trans('sell.must_be_logged_private_account');
                return redirect(trans_route($currentLocale, 'routes.sell'))->withErrors(['message' => $message]);
            }

            $viewName = app('request')->route()->getName();
            //$viewName = 'sell';

            $return = [];

            $datasRequest = $request->all();


            //$getDefaults 	    = $this->getDefaults($datasRequest);
            $ForsaleController  = new ForsaleController();
            $getDefaults 	    = $ForsaleController->getDefaults($datasRequest);
            $getDefaults = json_decode(json_encode($getDefaults), true);

            try {
                //$Sell = AdsCaracts::create($request->all());

                $user_check = false;
                $sell_check = false;

                $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';

                $datasRequest['ci_firstname']   = !empty($datasRequest['ci_firstname']) ? ucwords(mb_strtolower($datasRequest['ci_firstname'])) : null;
                $datasRequest['ci_last_name']   = !empty($datasRequest['ci_last_name']) ? mb_strtoupper($datasRequest['ci_last_name']) : null;
                $datasRequest['ci_city']        = !empty($datasRequest['ci_city']) ? mb_strtoupper($datasRequest['ci_city']) : null;

                $datasRequest['email']          = !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null;

                $username = '';
                $password = '';
                $privatescaracts = [];
                if(Auth::check()) {
                    $user_check = true;
                    $ci_email = Auth::user()->email;
                    $user_id = Auth::user()->id;
                    $user_type = Auth::user()->type;
                    $username = Auth::user()->username;
                    switch($user_type) {
                        /*case 'admin':
                            $usercaracts = [];
                            break;*/
                        case 'private':
                            $usercaracts = PrivatesCaracts::where('user_id', $user_id)
                                //->select('id', 'firstname', 'name')
                                ->get();
                            break;
                        /*case 'dealer':
                            $usercaracts = DealersCaracts::where('user_id', $user_id)
                                //->select('id', 'firstname', 'name')
                                ->get();
                            break;*/
                        case 'customer':
                            $usercaracts = CustomersCaracts::where('user_id', $user_id)
                                //->select('id', 'firstname', 'name')
                                ->get();
                            break;
                        /*case 'commercial':
                            $usercaracts = CommercialsCaracts::where('user_id', $user_id)
                                //->select('id', 'firstname', 'name')
                                ->get();
                            break;*/
                    }
                    $array = json_decode(json_encode($usercaracts), true);
                    if(!empty($array[0])) {
                        $privatescaracts = $array[0];
                    }
                    $privatescaracts['email'] = $ci_email;
                    $datasRequest['ci_email'] = $ci_email;

                    $result = User::where('id', $user_id)->where('email', $ci_email)->select('password')->get();
                    $result = json_decode(json_encode($result), true);
                    $user_infos = [];
                    if(!empty($result)) {
                        $user_infos = $result[0];
                    }
                    $password                       = 'already_created';
                    $passwordCrypted                = !empty($user_infos['password']) ? $user_infos['password'] : null;
                } else {
                    if(!empty($datasRequest['username'])) {
                        $username = $datasRequest['username'];
                    } else {
                        $username = !empty($datasRequest['ci_last_name']) ? !empty($datasRequest['ci_firstname']) ? str_slug(mb_strtolower($datasRequest['ci_firstname'])[0] . mb_strtolower($datasRequest['ci_last_name']), '_') : str_slug(mb_strtolower($datasRequest['ci_last_name']), '_') : null;

                        // username base on email
                        //list($username, $mailer) = explode('@', $datasRequest['ci_email']);
                        //$username = preg_replace('/\./', '_', $username);
                        //$username = str_replace('+', '_', $username);
                        ////$username = snake_case($username);
                        //$username = str_slug($username, '_');


                        // if username exist create it with incremental number
                        $z = 1;
                        while (!empty(json_decode(json_encode($result = User::select('id')->where('username', '=', $username)->get()), true))) {
                            $count_before = $z-1;
                            $username = str_replace($count_before, '', $username) . $z;
                            $z++;
                        }
                        //$datasRequest['username'] = $username;
                    }
                    $password                       = !empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null;
                    $passwordCrypted                = !empty($datasRequest['ci_password']) ? bcrypt($datasRequest['ci_password']) : null;
                }
                $datasRequest['username']       = $username;
                $datasRequest['ci_password']    = $password;
                $datasRequest['password']       = $password;
                $datasRequest['role_id']        = 3; //default 3 as 'private/individual account role',
                $datasRequest['type']           = 'private';
                $datasRequest['status']         = 'in_moderation';

                if(session()->has('ad_ref')) {
                    $datasRequest['reference']      = session()->get('ad_ref');
                } else {
                    //$datasRequest['reference']      = 'sell_' . $country_code . '|' . $_SERVER['REQUEST_TIME'] . '_' . $datasRequest['username'];
                    $datasRequest['reference']      = 'sell_' . $country_code . '|' . $_SERVER['REQUEST_TIME'];
                    $request->session()->put('ad_ref', $datasRequest['reference']);
                }
                //$datasRequest['reference']      = 'sell_' . $country_code . '|' . $username;
                //$datasRequest['reference']      = 'sell_' . $country_code;

                $datasRequest['with_marina_berth'] = !empty($datasRequest['with_marina_berth']) ? 1 : '';
                $datasRequest['agree_emails'] = !empty($datasRequest['agree_emails']) ? 1 : '';
                $datasRequest['agree_cgv'] = !empty($datasRequest['agree_cgv']) ? 1 : '';

                $ad_manufacturer_name = !empty($datasRequest['manufacturers_id']) ? $getDefaults['manufacturers'][$datasRequest['manufacturers_id']] : '';
                $ad_model_name = !empty($datasRequest['models_id']) ? $getDefaults['models'][$datasRequest['models_id']] : '';

                $datasRequest['ad_title'] = !empty($ad_manufacturer_name) ? $ad_manufacturer_name . ( !empty($ad_model_name) ? ' ' . $ad_model_name : '') : '';

                $ad_description_caracts_labels = '';
                if(array_key_exists('description_labels', $datasRequest) && is_array($datasRequest['description_labels']) && count($datasRequest['description_labels']) > 0) {
                    $ad_description_caracts_labels = implode(';', $datasRequest['description_labels']) . ';';
                }
                $datasRequest['ad_description_caracts_labels'] = $ad_description_caracts_labels;

                $ad_description_caracts_values = '';
                if(array_key_exists('description_values', $datasRequest) && is_array($datasRequest['description_values']) && count($datasRequest['description_values']) > 0) {
                    $ad_description_caracts_values = implode(';', $datasRequest['description_values']) . ';';
                }
                $datasRequest['ad_description_caracts_values'] = $ad_description_caracts_values;

                //
                $ad_specifications_caracts_labels = '';
                if(array_key_exists('specifications_labels', $datasRequest) && is_array($datasRequest['specifications_labels']) && count($datasRequest['specifications_labels']) > 0) {
                    $ad_specifications_caracts_labels = implode(';', $datasRequest['specifications_labels']) . ';';
                }
                $datasRequest['ad_specifications_caracts_labels'] = $ad_specifications_caracts_labels;

                $ad_specifications_caracts_values = '';
                if(array_key_exists('specifications_values', $datasRequest) && is_array($datasRequest['specifications_values']) && count($datasRequest['specifications_values']) > 0) {
                    $ad_specifications_caracts_values = implode(';', $datasRequest['specifications_values']) . ';';
                }
                $datasRequest['ad_specifications_caracts_values'] = $ad_specifications_caracts_values;

                //
                $ad_features_caracts_categories = '';
                if(array_key_exists('features_labels', $datasRequest) && is_array($datasRequest['features_labels']) && count($datasRequest['features_labels']) > 0) {
                    $ad_features_caracts_categories = implode(';', $datasRequest['features_labels']) . ';';
                }
                $datasRequest['ad_features_caracts_categories'] = $ad_features_caracts_categories;

                $ad_features_caracts_values = '';
                if(array_key_exists('features_values', $datasRequest) && is_array($datasRequest['features_values']) && count($datasRequest['features_values']) > 0) {
                    $ad_features_caracts_values = implode(';', $datasRequest['features_values']) . ';';
                }
                $datasRequest['ad_features_caracts_values'] = $ad_features_caracts_values;

                if(Auth::check()) {
                    $datasRequest['agree_cgv'] = 1;
                    $rulesAdsCaracts = AdsCaractsFormRequest::rulesUpdate();
                } else {
                    $rulesAdsCaracts = AdsCaractsFormRequest::rules();
                }
                $validator = Validator::make($datasRequest, $rulesAdsCaracts);
                if($validator->fails()) {
                    $errors = $validator->errors();
                    $datasRequest['ready_to_pay'] = session()->has('ready_to_pay') ? session()->get('ready_to_pay') : false;
                    $request->session()->put('ready_to_pay', $datasRequest['ready_to_pay']);
                    $array = $getDefaults;
                    $array['errors'] = $errors;
                    $array['privatescaracts'] = $privatescaracts;
                    $array['datasRequest'] = $datasRequest;
                    $array += $request->input();
                    $array['ready_to_pay'] = $datasRequest['ready_to_pay'];
                    return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                } else {
                    if(!$datasRequest['ready_to_pay'] || $datasRequest['ready_to_pay'] == 'false') {
                        //$datasRequest['ready_to_pay'] = true;
                        $datasRequest['ready_to_pay'] = 'ready_to_pay';
                        $request->session()->put('ready_to_pay', $datasRequest['ready_to_pay']);

                        $array = $getDefaults;
                        $array['privatescaracts'] = $privatescaracts;
                        $array['datasRequest'] = $datasRequest;
                        $array += $request->input();
                        $array['ready_to_pay'] = $datasRequest['ready_to_pay'];

                        if(!Auth::check()) {
                            $rulesUser = [
                                'username' => 'required|max:255|unique:users',
                                'email' => 'required|email|max:255|unique:users',
                                'password' => 'required|min:6',
                                //'g-recaptcha-response' => 'required|recaptcha',
                            ];
                            $validatorUser = Validator::make($datasRequest, $rulesUser);

                            if ($validatorUser->fails()) {
                                $datasRequest['ready_to_pay'] = false;
                                $request->session()->put('ready_to_pay', $datasRequest['ready_to_pay']);

                                $errors = $validatorUser->errors();
                                $array['errors'] = json_decode(json_encode($errors), true);
                                $array['datasRequest'] = $datasRequest;
                                $array['ready_to_pay'] = $datasRequest['ready_to_pay'];
                                return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                            }
                        }

                        return view($viewName, $array)->withInput($request->input());
                    } else {
                        //var_dump('ready_to_pay');
                        $appCountryCode = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
                        $getCountry = Search::getCountry(config('youboat.' . $appCountryCode . '.country_code'));
                        $countries_id = 77;
                        if(is_array($getCountry)) {
                            if (array_key_exists('id', $getCountry)) {
                                $countries_id = !empty($getCountry['id']) ? $getCountry['id'] : 77;
                            }
                        }

                        if(isset($datasRequest['ready_to_pay']) && $datasRequest['ready_to_pay'] == 'ready_to_pay') {
                            //var_dump('ready_to_pay');
                            $datasRequest['status'] = 'nok';
                            $Sell = AdsCaracts::firstOrNew(array('ad_ref' => $datasRequest['reference']));
                            $Sell->fill($datasRequest)->save();

                            //$datasRequest['reference'] = $Sell['ad_ref'] . '|' . $Sell['id'];
                            if (Auth::check()) {
                                // converter Customer to Private if type != private
                                if(Auth::user()->type != 'private') {
                                    $user_id = Auth::user()->id;
                                    $datasRequest['user_id'] = $user_id;
                                    $rulesPrivate = [
                                        'user_id' => 'required'
                                    ];
                                    $validatorPrivate = Validator::make($datasRequest, $rulesPrivate);

                                    if ($validatorPrivate->fails()) {
                                        $errors = $validatorPrivate->errors();
                                        $array = $getDefaults;
                                        $array['errors'] = json_decode(json_encode($errors), true);
                                        //return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                                        //return back()->withInput()->withErrors($errors, $this->errorBag());
                                        //return back()->with($array)->withInput();
                                        return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                                    } else {
                                        $Private = PrivatesCaracts::firstOrNew([
                                            'user_id' => $user_id,
                                            'firstname' => !empty($datasRequest['ci_firstname']) ? ucwords(mb_strtolower($datasRequest['ci_firstname'])) : null,
                                            'name' => !empty($datasRequest['ci_last_name']) ? mb_strtoupper($datasRequest['ci_last_name']) : null,
                                            'address' => '',
                                            'address_more' => '',
                                            'zip' => !empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null,
                                            'city' => !empty($datasRequest['ci_city']) ? mb_strtoupper($datasRequest['ci_city']) : null,
                                            'province' => '',
                                            'region' => '',
                                            'subregion' => '',
                                            'country_id' => !empty($datasRequest['ci_countries_id']) ? $datasRequest['ci_countries_id'] : null,
                                            'phone_1' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                                            'phone_mobile' => '',
                                            'fax' => '',
                                            'emails' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                            'twitter' => '',
                                            'facebook' => '',
                                            'origin' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                                        ]);

                                        if ($Private->save()) {
                                            $updateUser = User::find($user_id);
                                            $updateUser->role_id = 3;
                                            $updateUser->type = 'private';
                                            $updateUser->save();
                                        }
                                    }
                                }

                                $updateSell = AdsCaracts::find($Sell['id']);
                                $updateSell->user_id = $user_id;
                                $updateSell->save();
                            } else {
                                $datasRequest['ci_password'] = $datasRequest['password'] = $password;

                                $rulesUser = [
                                    'username' => 'required|max:255|unique:users',
                                    'email' => 'required|email|max:255|unique:users',
                                    //'email' => 'required|email|max:255',
                                    'password' => 'required|min:6',
                                    //'g-recaptcha-response' => 'required|recaptcha',
                                ];
                                $validatorUser = Validator::make($datasRequest, $rulesUser);
                                if ($validatorUser->fails()) {
                                    //$datasRequest['ready_to_pay'] = true;
                                    //$datasRequest['ready_to_pay'] = 'success';
                                    $datasRequest['ready_to_pay'] = false;
                                    $request->session()->put('ready_to_pay', $datasRequest['ready_to_pay']);

                                    $errors = $validatorUser->errors();
                                    $array = $getDefaults;
                                    $array['errors'] = json_decode(json_encode($errors), true);
                                    $array['privatescaracts'] = $privatescaracts;
                                    $array['datasRequest'] = $datasRequest;
                                    $array += $request->input();
                                    $array['ready_to_pay'] = $datasRequest['ready_to_pay'];
                                    return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                                } else {
                                    $datasRequest['password'] = $passwordCrypted;
                                    $User = User::create([
                                        'username' => $datasRequest['username'],
                                        'email' => $datasRequest['email'],
                                        'password' => $datasRequest['password'],
                                        'role_id' => $datasRequest['role_id'],
                                        'type' => $datasRequest['type'],
                                        'status' => 'active',
                                    ]);
                                }
                                if ($User->save()) {
                                    $datasRequest['user_id'] = $User["id"];
                                    $rulesPrivate = [
                                        'user_id' => 'required'
                                    ];
                                    $validatorPrivate = Validator::make($datasRequest, $rulesPrivate);

                                    if ($validatorPrivate->fails()) {
                                        $errors = $validatorPrivate->errors();
                                        $array = $getDefaults;
                                        $array['errors'] = json_decode(json_encode($errors), true);
                                        //return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                                        //return back()->withInput()->withErrors($errors, $this->errorBag());
                                        //return back()->with($array)->withInput();
                                        return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                                    } else {
                                        //$datasRequest['reference'] = 'sell_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . $datasRequest['username'];
                                         $Private = PrivatesCaracts::create([
                                            'user_id' => $User["id"],
                                            'firstname' => !empty($datasRequest['ci_firstname']) ? ucwords(mb_strtolower($datasRequest['ci_firstname'])) : null,
                                            'name' => !empty($datasRequest['ci_last_name']) ? mb_strtoupper($datasRequest['ci_last_name']) : null,
                                            'address' => '',
                                            'address_more' => '',
                                            'zip' => !empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null,
                                            'city' => !empty($datasRequest['ci_city']) ? mb_strtoupper($datasRequest['ci_city']) : null,
                                            'province' => '',
                                            'region' => '',
                                            'subregion' => '',
                                            'country_id' => !empty($datasRequest['ci_countries_id']) ? $datasRequest['ci_countries_id'] : null,
                                            'phone_1' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                                            'phone_mobile' => '',
                                            'fax' => '',
                                            'emails' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                            'twitter' => '',
                                            'facebook' => '',
                                            'origin' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                                        ]);

                                        if ($Private->save()) {
                                            $updateSell = AdsCaracts::find($Sell['id']);
                                            //$updateSell->ad_ref = $Sell['ad_ref'] . '|' . $Sell['id'];
                                            $updateSell->user_id = $User["id"];
                                            $updateSell->save();
                                        }
                                    }
                                }
                                if ($User->save() && $Private->save()) {
                                    $user_check = true;
                                }
                            }

                            if ($Sell->save()) {
                                $sell_check = true;
                            }

                            if ($user_check && $sell_check) {
                                // Payment

                                $datasRequest['ready_to_pay'] = 'success';
                                $request->session()->put('ready_to_pay', $datasRequest['ready_to_pay']);

                                if (!Auth::check()) {
                                    Auth::login($User);
                                }
                           }
                            if (Auth::check()) {
                                // get countrycontracts with user id
                                /***$CountryContracts = CountryContracts::where('user_id', '=', Auth::user()->id)->where('countries_ids', '=', $countries_id)->get();
                                $resultCountryContracts = $CountryContracts->toArray();
                                if(isset($resultCountryContracts) &&!empty($resultCountryContracts[0])) {
                                    $request->session()->put('country_contracts.id', $resultCountryContracts[0]['id']);
                                    $request->session()->put('country_contracts.reference', $resultCountryContracts[0]['reference']);
                                }**/
                                $datasRequest['ready_to_pay'] = 'success';
                                $request->session()->put('ready_to_pay', $datasRequest['ready_to_pay']);
                            }

                        } /*else */ if(isset($datasRequest['ready_to_pay']) && $datasRequest['ready_to_pay'] == 'success') {
                            //echo '<br>success<br><pre>';
                            $reference = $datasRequest['reference'];

                            $message_referrer = 'sell';
                            $message_title = trans('navigation.sell');
                            $message_text = trans('emails.thanks_sell_boat');
                            $message_type = 'success';

                            if(session()->has('ad_ref')) {
                                $reference      = session()->get('ad_ref');
                            }
                            $datasRequest['reference'] = $reference ;

                            $countrycontracts_id = '';
                            $transaction_id = '';

                            if(null !== session()->get('country_contracts')['id']) {
                                $countrycontracts_id = session()->get('country_contracts')['id'];

                            } else if(
                                isset($datasRequest['stripeToken']) && !empty($datasRequest['stripeToken']) &&
                                isset($datasRequest['stripeTokenType']) &&  $datasRequest['stripeTokenType'] == "card" &&
                                isset($datasRequest['stripeEmail']) && !empty($datasRequest['stripeEmail'])
                            ) {
                                require $_SERVER['DOCUMENT_ROOT'] . '/pay/actions/pay_stripe.php';

                                if(isset($transaction) && !empty($transaction)) {
                                    Session::put('transaction.success_msg', $success_msg);
                                    Session::put('transaction.id', $transaction['id']);
                                    Session::put('transaction.description', $transaction['description']);
                                    Session::put('transaction.amount', $transaction['amount']);
                                    $message_text .= session()->get('transaction.success_msg');

                                    $CommercialsCaracts = CommercialsCaracts::where('country_code', '=', config('app.country_code'))->pluck('id');
                                    $result = $CommercialsCaracts->toArray();
                                    $commercialscaracts_id = isset($result) &&!empty($result[0]) ? $result[0] : 0;

                                    $transaction_id = session()->get('transaction.id');
                                    //$transaction_id = $reference;

                                    $date_now = Carbon::now();
                                    $start_date = $date_now->format('Y-m-d');;
                                    $end_date = $date_now->addYear(100)->format('Y-m-d');

                                    $inputCountryContracts = [
                                        'user_id' =>Auth::user()->id,
                                        'commercialscaracts_id' => $commercialscaracts_id,
                                        'reference' =>  $reference . '|' . $transaction_id,
                                        'description' => session()->get('transaction.description'),
                                        'countries_ids' => $countries_id,
                                        'amount'=>session()->get('transaction.amount'),
                                        'start_date'=> $start_date,
                                        'end_date'=> $end_date,
                                        'status'=>'active'
                                    ];

                                    //Carbon::parse($request->input('start_date'))
                                    $createCountryContracts = CountryContracts::Create($inputCountryContracts);
                                    $createCountryContracts->save();

                                    $countrycontracts_id = $createCountryContracts["id"];
                                    $request->session()->put('country_contracts.id', $countrycontracts_id);
                                    $request->session()->put('country_contracts.reference', $transaction_id);
                                }

                            }
                            if(!empty($countrycontracts_id)) {
                                $Sell = AdsCaracts::where('ad_ref', $datasRequest['reference'])->pluck('id')->all();
                                $sell_id = $Sell[0];

                                $updateSell = AdsCaracts::find($sell_id);
                                $updateSell->status = 'in_moderation';
                                $updateSell->ad_ref = $reference . '|' . $transaction_id;
                                $updateSell->countrycontracts_id = $countrycontracts_id;
                                $saveSell = $updateSell->save();

                                if($saveSell) {
                                    //$datasRequest['reference'] .= '|' . $saveSell->id;
                                    $details = [];
                                    if (!app()->isLocal()) {
                                        if (!empty($datasRequest)) {
                                            $boat_locations = config('youboat.' . $country_code . '.locations');
                                            $boat_locations_regions = $boat_locations['regions'];
                                            $boat_locations_counties = $boat_locations['counties'];

                                            $details = array(
                                                'price' => !empty($datasRequest['ad_price']) ? $datasRequest['ad_price'] . $datasRequest['currency'] : null,
                                                'sell_type' => !empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : null,

                                                'adstype' => !empty($datasRequest['adstypes_id']) ? Search::getAdsTypeById($datasRequest['adstypes_id'])['name'] : null,
                                                'category' => !empty($datasRequest['categories_ids']) ? Search::getCategoryById($datasRequest['categories_ids'])['name'] : null,
                                                'subcategory' => !empty($datasRequest['subcategories_ids']) ? Search::getSubcategoryById($datasRequest['subcategories_ids'])['name'] : null,

                                                'manufacturer' => !empty($datasRequest['manufacturers_id']) ? Search::getManufacturerById($datasRequest['manufacturers_id'])['name'] : null,
                                                'model' => !empty($datasRequest['models_id']) ? Search::getModelById($datasRequest['models_id'])['name'] : null,

                                                'manufacturer_engines' => !empty($datasRequest['manufacturersengines_id']) ? Search::getManufacturerEngineById($datasRequest['manufacturersengines_id'])['name'] : null,
                                                'model_engine' => !empty($datasRequest['modelsengines_id']) ? Search::getModelEngineById($datasRequest['modelsengines_id'])['name'] : null,

                                                'width_meter' => !empty($datasRequest['ad_width_meter']) ? $datasRequest['ad_width_meter'] : null,
                                                'length_meter' => !empty($datasRequest['ad_length_meter']) ? $datasRequest['ad_length_meter'] : null,
                                                'draft_meter' => !empty($datasRequest['ad_draft_meter']) ? $datasRequest['ad_draft_meter'] : null,
                                                'propulsion' => !empty($datasRequest['ad_propulsion']) ? $datasRequest['ad_propulsion'] : null,
                                                'nb_engines' => !empty($datasRequest['ad_nb_engines']) ? $datasRequest['ad_nb_engines'] : null,
                                                'nb_engines' => !empty($datasRequest['ad_nb_engines']) ? $datasRequest['ad_nb_engines'] : null,
                                                'year_built' => !empty($datasRequest['ad_year_built']) ? $datasRequest['ad_year_built'] : null,

                                                'with_marina_berth' => (!empty($datasRequest['with_marina_berth']) && $datasRequest['with_marina_berth'] == 1) ? ucfirst(trans('sell.with_marina_berth')) : null,

                                                'mooring_country' => !empty($datasRequest['ad_mooring_country']) ? $datasRequest['ad_mooring_country'] : null,
                                                'phone' => !empty($datasRequest['ad_phones']) ? $datasRequest['ad_phones'] : null,

                                                'description' => !empty($datasRequest['ad_description']) ? $datasRequest['ad_description'] : null,

                                                'country' => !empty($datasRequest['countries_id']) ? Search::getCountryById($datasRequest['countries_id'])['name'] : null,
                                                'region' => !empty($datasRequest['regions_id']) ? $boat_locations_regions[$datasRequest['regions_id']]['name'] : null,
                                                'county' => !empty($datasRequest['counties_id']) ? $boat_locations_counties[$datasRequest['counties_id']] : null,

                                                'first_name' => !empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null,
                                                'last_name' => !empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null,
                                                'email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                                'password' => !empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null,
                                                'phone' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                                                'zip' => !empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null,
                                                'city' => !empty($datasRequest['ci_city']) ? $datasRequest['ci_city'] : null,
                                                'country' => !empty($datasRequest['ci_countries_id']) ? Search::getCountryById($datasRequest['ci_countries_id'])['name'] : null,
                                                'region' => !empty($datasRequest['ci_regions_id']) ? $boat_locations_regions[$datasRequest['ci_regions_id']]['name'] : null,
                                                'county' => !empty($datasRequest['ci_counties_id']) ? $boat_locations_counties[$datasRequest['ci_counties_id']] : null,

                                                'agree_emails' => (!empty($datasRequest['agree_emails']) && $datasRequest['agree_emails'] == 1) ? ucfirst(trans('contact_informations.label_optin_agree_emails')) : null
                                            );

                                            $title = htmlspecialchars_decode(title_case(trans('navigation.cgv')));
                                            $url = url(trans_route($currentLocale, 'routes.cgv'));
                                            $terms_link = '<a href="' . $url . '" title="' . title_case($title) . '" target="_blank">' . $title . '</a>';
                                            $details['agree_cgv'] = (!empty($datasRequest['agree_cgv']) && $datasRequest['agree_cgv'] == 1) ? ucfirst(trans('contact_informations.label_optin_agree_cgv', ['terms' => $terms_link, 'website_name' => $country_code . '.' . env('APP_NAME')])) : null;
                                        }
                                        $datasEmail = array(
                                            //'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null,
                                            'details' => $details,
                                            'website_name' => config('youboat.' . $country_code . '.website_name'),
                                            'type_request' => 'a "' . trans('navigation.sell') . '"" request',
                                            'name' => !empty($datasRequest['ci_last_name']) ? !empty($datasRequest['ci_firstname']) ? ucwords(mb_strtolower($datasRequest['ci_firstname'])) . ' ' . mb_strtoupper($datasRequest['ci_last_name']) : mb_strtoupper($datasRequest['ci_last_name']) : null,
                                            'email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                            'password' => !empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null,
                                            'country_code' => $country_code,
                                            //'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc') . ',' . config('youboat.' . $country_code . '.country_manager_email'),
                                            'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                                            'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                                            'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                                        );
                                        Mail::send('emails.sell', $datasEmail, function ($message) use ($datasEmail) {
                                            $message->subject(trans('navigation.sell') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                                            $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.sell') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                                            $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                                            $message->to($datasEmail['email'], $datasEmail['name']);
                                        });

                                        $datasEmail['reference'] = !empty($datasRequest['reference']) ? $datasRequest['reference'] : null;
                                        Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                                            $message->subject($datasEmail['website_name'] . " > " . $datasEmail['type_request'] . ' ' . trans('emails.from') . ' ' . $datasEmail['email']);
                                            $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.sell') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                                            $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                                            //$message->bcc(explode(',', $datasEmail['bcc_mails']));
                                            $message->to($datasEmail['contact_email'], $datasEmail['contact_email']);
                                        });
                                    }

                                    ///////////////////////////
                                    //Propective Store + emails
                                    $inputProspectiveCustomers = array(
                                        'ci_firstname' => !empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null,
                                        'ci_last_name' => !empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null,
                                        'ci_email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                        'ci_phone' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                                        'country_code' => $country_code,
                                        'referrer' => 'sell',
                                        'ci_description' => '',
                                        'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                                    );

                                    $ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['ci_email']));
                                    $ProspectiveCustomers->fill($inputProspectiveCustomers)->save();
                                //}
                                //if($saveSell) {

                                    $request->session()->put('ad_ref', $reference);
                                    $request->session()->put('sell_message.referrer', $message_referrer);
                                    $request->session()->put('sell_message.title', $message_title);
                                    $request->session()->put('sell_message.text', $message_text);
                                    $request->session()->put('sell_message.type', $message_type);
                                    $message = session()->get('sell_message');

                                    if (Auth::check()) {
                                        $ForsaleController = new ForsaleController();
                                        $result = $ForsaleController->getAdsListing(Auth::user()->id, Auth::user()->email, Auth::user()->type);
                                        $return += $result;
                                        return redirect(trans_route($currentLocale, 'routes.dashboard'))->with($return)->withMessage($message);
                                    } else {
                                        return redirect(trans_route($currentLocale, 'routes.login'))->withMessage($message);
                                    }
                                }
                            } else {
                                /*$Sell = AdsCaracts::where('ad_ref', $datasRequest['reference'])->pluck('id')->all();
                                $sell_id = $Sell[0];

                                $updateSell = AdsCaracts::find($sell_id);
                                $updateSell->ad_ref = $reference;
                                $updateSell->countrycontracts_id = $countrycontracts_id;
                                $updateSell->status = 'nok';
                                $saveSell = $updateSell->save();

                                $message_referrer = 'sell';
                                $message_title = trans('navigation.sell');
                                $message_text = 'No country contracts!';
                                $message_type = 'error';

                                $request->session()->put('ad_ref', $reference);
                                $request->session()->put('sell_message.referrer', $message_referrer);
                                $request->session()->put('sell_message.title', $message_title);
                                $request->session()->put('sell_message.text', $message_text);
                                $request->session()->put('sell_message.type', $message_type);
                                $message = session()->get('sell_message');

                                return redirect()->back()->withErrors($message)->withMessage($message);
                                */
                            }
                        }
                        $array = $getDefaults;
                        $array['datasRequest'] = $datasRequest;
                        $array['privatescaracts'] = $privatescaracts;
                        $array += $request->input();
                        $array['ready_to_pay'] = $datasRequest['ready_to_pay'];
                        return view($viewName, $array)->withInput($request->input());
                    }
                }
            } catch(\Exception $e) {
                /*echo '<pre>';
                var_dump('Exception');
                var_dump($e->getMessage());
                die();*/
                return redirect()->back()->withErrors($e->getMessage());
            }
        }

        /**
         * Show the form for editing the specified adscaracts.
         *
         * @param  int  $id
         * @return \Illuminate\View\View
         */
        public function edit($id, $action = 'edit')
        {
            try {
                //$ads = Ads::orderBy("id", "asc")->pluck('id')->all();
                //session()->put('current_ad_id', $id);
                $AdsCaracts = AdsCaracts::find($id);
                $selltypes = getEnumValues('adscaracts', 'sell_type');
                $countries = Countries::orderBy("name", "asc")->pluck('name','id')->all();
                //$status = ['active'=>'active','inactive'=>'inactive','removed'=>'removed'];
                //$getDefaults = ForsaleController::getDefaults($AdsCaracts);
                $ForsaleController  = new ForsaleController();
                $getDefaults 	    = $ForsaleController->getDefaults($input);

                $country_id 	= !empty($AdsCaracts->countries_id) ? $AdsCaracts->countries_id : 77; // uk
                $getCountryById = Search::getCountryById($country_id, false);
                //$getCountryById = $this->getCountryById($country_id, false);
                if(is_array($getCountryById) && array_key_exists('code', $getCountryById)) {
                    $AdsCaracts->country_code = $getCountryById['code'];
                }
                $AdsCaracts->ad_photos_thumbs = '';

                $datas = compact(
                    'AdsCaracts',
                    'selltypes',
                    'country_id',
                    'countries'
                    //'status'
                );
                $getDefaults['manufacturersengines'] = '';
                $getDefaults['modelsengines'] = '';
                $return = $datas + $getDefaults;

                return view(config('quickadmin.route') . '.adscaracts.' . $action, $return);
            } catch(\Exception $e) {
                //var_dump($e->getMessage());
                //die();
                return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
            }
        }

        /**
         * Update the specified adscaracts in storage.
         * @param UpdateAdsCaractsRequest|Request $request
         *
         * @param  int  $id
         */
        //public function update($id, UpdateAdsCaractsRequest $request)
        public function update($id, AdsCaractsFormRequest $request)
        {
            try {
                //session()->put('current_ad_id', $id);
                $AdsCaracts = AdsCaracts::findOrFail($id);
                //$request = $this->saveFiles($request);

                $input = $request->all();

                //$country_code = !empty($input['countries_id']) ? $input['countries_id'] : '';
                $getCountry = Search::getCountry($country_code);
                //$getCountry = $this->getCountry($country_code);
                $country_id = !empty($input['countries_id']) ? $input['countries_id'] : '';
                $getCountry = Search::getCountry($country_id, false);

                $country_id = '';
                $country_name = '';
                if(is_array($getCountry)) {
                    if(array_key_exists('id', $getCountry)) {
                        $country_id = !empty($getCountry['id']) ? $getCountry['id'] : null;
                    }
                    if(array_key_exists('name', $getCountry)) {
                        $country_name = !empty($getCountry['name']) ? $getCountry['name'] : null;
                    }
                }
                $input['countries_id'] = $country_id;
                $input['ad_location'] = $country_name;
                if(isset($input['old_ad_mooring_country']) && !empty($input['old_ad_mooring_country'])) {
                    $input['ad_mooring_country'] = $input['old_ad_mooring_country'];
                } else {
                    $input['ad_mooring_country'] = !empty($input['ad_mooring_country']) ? $input['ad_mooring_country'] : '';
                }
                //$currentLocale = !empty($country_code) ? mb_strtolower($country_code) : 'uk';

                //
                $ad_manufacturer_name = '';
                if(array_key_exists('manufacturers_id', $input)) {
                    $getManufacturerById = Search::getManufacturerById($input['manufacturers_id']);
                    //$getManufacturerById = $this->getManufacturerById($input['manufacturers_id']);
                    if(array_key_exists('name', $getManufacturerById)) {
                        $ad_manufacturer_name = $getManufacturerById['name'];
                    }
                }
                $ad_model_name = '';
                if(array_key_exists('models_id', $input)) {
                    $getModelById = Search::getModelById($input['models_id']);
                    //$getModelById = $this->getModelById($input['models_id']);
                    if(array_key_exists('name', $getModelById)) {
                        $ad_model_name = $getModelById['name'];
                    }
                }
                $ad_title = !empty($ad_manufacturer_name) ? $ad_manufacturer_name . (!empty($ad_model_name) ? ' ' . $ad_model_name : '') : '';
                if(!empty($ad_title)) {
                    $input['ad_title'] = $ad_title;
                }

                //
                $ad_photos = '';
                if(array_key_exists('upload_photos', $input) && is_array($input['upload_photos']) && count($input['upload_photos']) > 0) {
                    $ad_photos = implode(';', $input['upload_photos']);
                    $input['ad_photo'] = $input['upload_photos'][0];
                } else if(array_key_exists('upload_photos', $input)) {
                    $ad_photos = $input['upload_photos'];
                    $input['ad_photo'] = $input['upload_photos'][0];
                }
                $input['ad_photos'] = $ad_photos;

                //
                $ad_description_caracts_labels = '';
                if(array_key_exists('description_labels', $input) && is_array($input['description_labels']) && count($input['description_labels']) > 0) {
                    $ad_description_caracts_labels = implode(';', $input['description_labels']) . ';';
                }
                $input['ad_description_caracts_labels'] = $ad_description_caracts_labels;

                $ad_description_caracts_values = '';
                if(array_key_exists('description_values', $input) && is_array($input['description_values']) && count($input['description_values']) > 0) {
                    $ad_description_caracts_values = implode(';', $input['description_values']) . ';';
                }
                $input['ad_description_caracts_values'] = $ad_description_caracts_values;

                //
                $ad_specifications_caracts_labels = '';
                if(array_key_exists('specifications_labels', $input) && is_array($input['specifications_labels']) && count($input['specifications_labels']) > 0) {
                    $ad_specifications_caracts_labels = implode(';', $input['specifications_labels']) . ';';
                }
                $input['ad_specifications_caracts_labels'] = $ad_specifications_caracts_labels;

                $ad_specifications_caracts_values = '';
                if(array_key_exists('specifications_values', $input) && is_array($input['specifications_values']) && count($input['specifications_values']) > 0) {
                    $ad_specifications_caracts_values = implode(';', $input['specifications_values']) . ';';
                }
                $input['ad_specifications_caracts_values'] = $ad_specifications_caracts_values;

                //
                $ad_features_caracts_categories = '';
                if(array_key_exists('features_labels', $input) && is_array($input['features_labels']) && count($input['features_labels']) > 0) {
                    $ad_features_caracts_categories = implode(';', $input['features_labels']) . ';';
                }
                $input['ad_features_caracts_categories'] = $ad_features_caracts_categories;

                $ad_features_caracts_values = '';
                if(array_key_exists('features_values', $input) && is_array($input['features_values']) && count($input['features_values']) > 0) {
                    $ad_features_caracts_values = implode(';', $input['features_values']) . ';';
                }
                $input['ad_features_caracts_values'] = $ad_features_caracts_values;

                $request = new Request($input);

                $input = $request->all();

                if($AdsCaracts->update($input)) {
                    if(array_key_exists('upload_photos', $input) && is_array($input['upload_photos'])) {
                        foreach ($input['upload_photos'] as $key => $url) {
                            $pathinfo = pathinfo($url);
                            $dirname = $pathinfo['dirname'];
                            $basename = $pathinfo['basename'];
                            $sourceDir = public_path() . $dirname;
                            $targetDir = str_replace(['youboat-www_boatgest', 'boatgest-youboat'], ['youboat-www_website', 'youboat-www_website'], public_path()) . $dirname;
                            while (!File::isDirectory($targetDir)) {
                                File::makeDirectory($targetDir, 0775, true, true);
                            }
                            File::copy($sourceDir . '/' . $basename, $targetDir . '/' . $basename);
                        }
                    }
                    $message = trans('ads_caracts.ad_successfully_updated');
                    return redirect()->back()->withInput($request->input())->withMessage($message);
                }
            } catch(\Exception $e) {
                //var_dump($e->getMessage());
                //die();
                return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
            }
        }
    }
