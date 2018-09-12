<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Requests\BodFormRequest as BodFormRequest;
    //use App\Http\Requests\ProspectiveCustomersRequest;

    use App\User;
    use App\BodCaracts;
    use App\CustomersCaracts;
    use App\CommercialsCaracts;
    use App\DealersCaracts;
    use App\PrivatesCaracts;
    use App\ProspectiveCustomers;

    use Mail;
    use Auth;
    use Cache;

    use Illuminate\Support\Facades\Validator;

    class BodController extends ForsaleController
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
         * Show the application dashboard.
         *
         * @return \Illuminate\Http\Response
         */
        public function create (Request $request)
        {
            $viewName       = 'boat_on_demand';
            if ($request) {
                $customerscaracts = [];
                $user_infos = [];
                //if(!Auth::guest()) {
                if(Auth::check()) {
                    $ci_email = Auth::user()->email;
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
                    $customerscaracts['email'] = $ci_email;
                    ////
                    ////


                    //$result = BodCaracts::where('customer_id', $customerscaracts['id'])->where('ci_email', $ci_email)
                    $result = BodCaracts::where('user_id', $user_id)->where('ci_email', $ci_email)
                        //->select('customer_id', 'ci_firstname', 'ci_last_name', 'ci_phone', 'ci_email', 'ci_password')->get();
                        //->select('customer_id', 'ci_password')->get(1);
                        ->select('user_id', 'ci_password')->get(1);
                    $result = json_decode(json_encode($result), true);
                    $user_infos = [];
                    if(!empty($result)) {
                        $user_infos = $result[0];
                    }
                    //$user_infos = json_decode(json_encode($result), true)[0];
                }
                $datasRequest = $request->all();
                $getDefaults = $this->getDefaults($datasRequest);
                $datas = [
                    'customerscaracts' => $customerscaracts,
                    'user_infos' => $user_infos,
                ];
                $return = $datas + $getDefaults;

                //return view($viewName, $return)->withInput($request->all());
                //Cache::flush();
                return view($viewName, $return);
                /*return view($viewName, compact(
                    'pageTitle',
                    'customer_infos',
                    'user_infos',
                    'bod_listing',
                    'datasRequest'
                ))->with(['controller' => $this]);

                return view($viewName, $this->getDefaults($datasRequest))->withInput($request->all());*/
            } else {
                return view($viewName, $this->getDefaults([]));
            }
        }

        /**
         * Store a newly created contact in storage.
         *m
         * @param Request|Request $request
         */
        //public function store(BodFormRequest $request)
        public function store(Request $request)
        {
            $currentLocale = config('app.locale');

            $viewName       = 'boat_on_demand';

            $return = [];

            try {
                //$BoatOnDemand = BodCaracts::create($request->all());

                $user_check = false;
                $bod_check = false;

                $datasRequest = $request->all();

                $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';

                $datasRequest['ci_firstname']   = !empty($datasRequest['ci_firstname']) ? ucwords(mb_strtolower($datasRequest['ci_firstname'])) : null;
                $datasRequest['ci_last_name']   = !empty($datasRequest['ci_last_name']) ? mb_strtoupper($datasRequest['ci_last_name']) : null;
                $datasRequest['ci_city']        = !empty($datasRequest['ci_city']) ? mb_strtoupper($datasRequest['ci_city']) : null;

                $username                       = !empty($datasRequest['ci_last_name']) ? !empty($datasRequest['ci_firstname']) ? str_slug(mb_strtolower($datasRequest['ci_firstname'])[0] . mb_strtolower($datasRequest['ci_last_name']), '_') : str_slug(mb_strtolower($datasRequest['ci_last_name']), '_') : null;
                // if username exist create it with incremental number
                $z = 1;
                while(!empty(json_decode(json_encode($result = User::select('id')->where('username', '=', $username)->get()), true))) {
                    $username = $username . $z;
                    $z++;
                }
                $datasRequest['username'] = $username;

                $datasRequest['email']          = !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null;
                if(Auth::check()) {
                    $user_check = true;
                    $ci_email = Auth::user()->email;
                    $user_id = Auth::user()->id;
                    $user_type = Auth::user()->type;
                    switch($user_type) {
                        case 'admin':
                            $usercaracts = [];
                            break;
                        case 'private':
                            $usercaracts = PrivatesCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                            break;
                        case 'dealer':
                            $usercaracts = DealersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                            break;
                        case 'customer':
                            $usercaracts = CustomersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                            break;
                        case 'commercial':
                            $usercaracts = CommercialsCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                            break;
                    }
                    $array = json_decode(json_encode($usercaracts), true);
                    $customerscaracts = [];
                    if(!empty($array[0])) {
                        $customerscaracts = $array[0];
                    }
                    /*$result = CustomersCaracts::where('user_id', $user_id)
                        // @TODO  : check if 'emails' check necessary
                        //->where('emails', $ci_email)
                        ->select('id', 'firstname', 'name')
                        ->get();
                    $result = json_decode(json_encode($result), true);
                    $customerscaracts = [];
                    if(!empty($result)) {
                        $customerscaracts = $result[0];
                    }*/
                    //$customerscaracts = json_decode(json_encode($result), true)[0];
                    $customerscaracts['email'] = $ci_email;

                    $result = User::where('id', $user_id)->where('email', $ci_email)->select('password')->get();
                    $result = json_decode(json_encode($result), true);
                    $user_infos = [];
                    if(!empty($result)) {
                        $user_infos = $result[0];
                    }
                    //$user_infos = json_decode(json_encode($result), true)[0];
                    $password                       = 'already_created';
                    $passwordCrypted                = !empty($user_infos['password']) ? $user_infos['password'] : null;
                } else {
                    $password                       = !empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null;
                    $passwordCrypted                = !empty($datasRequest['ci_password']) ? bcrypt($datasRequest['ci_password']) : null;
                    //$passwordCrypted                = !empty($datasRequest['ci_password']) ? Hash::make($datasRequest['ci_password']) : null;
                }
                $datasRequest['ci_password']    = $password;
                $datasRequest['password']       = $password;
                $datasRequest['role_id']        = 6; //default 6 as 'customer account role',
                $datasRequest['type']           = 'customer';
                $datasRequest['status']         = 'active';

                $datasRequest['reference']      = 'bod_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . $datasRequest['username'];
                //$BoatOnDemand = BodCaracts::create($datasRequest);
                //$BoatOnDemand = BodCaracts::firstOrNew(array('reference' => $datasRequest['reference']));

                $datasRequest['with_marina_berth'] = !empty($datasRequest['with_marina_berth']) ? 1 : 0;
                $datasRequest['agree_similar'] = !empty($datasRequest['agree_similar']) ? 1 : 0;
                $datasRequest['agree_emails'] = !empty($datasRequest['agree_emails']) ? 1 : 0;
                $datasRequest['agree_cgv'] = !empty($datasRequest['agree_cgv']) ? 1 : 0;

                if(Auth::check()) {
                    $datasRequest['agree_cgv'] = 1;
                    $rulesBoatOnDemand = BodFormRequest::rulesUpdate();
                } else {
                    $rulesBoatOnDemand = BodFormRequest::rules();
                }

                $validator = Validator::make($datasRequest, $rulesBoatOnDemand);
                if($validator->fails()) {
                    $errors = $validator->errors();
                    $array = json_decode(json_encode($this->getDefaults($datasRequest)), true);
                    $array['errors'] = $errors;
                    return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                } else {
                    $BoatOnDemand = BodCaracts::firstOrNew(array('reference' => $datasRequest['reference']));
                    $BoatOnDemand->fill($datasRequest)->save();

                    if(Auth::check()) {
                        $updateBodCustomerId = BodCaracts::find($BoatOnDemand['id']);
                        //$updateBodCustomerId->customer_id = $customerscaracts['id'];
                        $updateBodCustomerId->user_id = $user_id;
                        $updateBodCustomerId->save();
                    } else {
                        $datasRequest['ci_password'] = $datasRequest['password'] = $password;

                        $rulesUser = [
                            'username' => 'required|max:255',
                            'email' => 'required|email|max:255|unique:users',
                            'password' => 'required|min:6',
                            //'g-recaptcha-response' => 'required|recaptcha',
                        ];
                        $validatorUser = Validator::make($datasRequest, $rulesUser);
                        if ($validatorUser->fails()) {
                            $errors = $validatorUser->errors();
                            //$array = json_decode(json_encode($this->getDefaultsBod($datasRequest)), true);
                            $array = json_decode(json_encode($this->getDefaults($datasRequest)), true);
                            $array['errors'] = json_decode(json_encode($errors), true);
                            return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                        } else {
                            $datasRequest['password'] = $passwordCrypted;
                            $User = User::create([
                                'username' => $datasRequest['username'],
                                'email' => $datasRequest['email'],
                                'password' => $datasRequest['password'],
                                'role_id' => $datasRequest['role_id'],
                                'type' => $datasRequest['type'],
                                'status' => $datasRequest['status'],
                            ]);
                        }
                        if ($User->save()) {
                            $datasRequest['user_id'] = $User["id"];
                            $rulesCustomer = [
                                'user_id' => 'required'
                            ];
                            $validatorCustomer = Validator::make($datasRequest, $rulesCustomer);

                            if ($validatorCustomer->fails()) {
                                $errors = $validatorCustomer->errors();

                                $array = json_decode(json_encode($this->getDefaults($datasRequest)), true);
                                $array['errors'] = json_decode(json_encode($errors), true);
                                return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                            } else {
                                $datasRequest['reference'] = 'bod_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . $datasRequest['username'];
                                $Customer = CustomersCaracts::create([
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

                                if ($Customer->save()) {
                                    $updateBodCustomerId = BodCaracts::find($BoatOnDemand['id']);
                                    //$updateBodCustomerId->customer_id = $Customer['id'];
                                    $updateBodCustomerId->user_id = $User["id"];
                                    $updateBodCustomerId->save();
                                }
                            }
                        }
                        if ($User->save() && $Customer->save()) {
                            $user_check = true;
                        }
                    }

                    if ($BoatOnDemand->save()) {
                        $bod_check = true;
                    }

                    if ($user_check && $bod_check) {

                        $inputProspectiveCustomers = array(
                            'ci_firstname' => !empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null,
                            'ci_last_name' => !empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null,
                            'ci_email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                            'ci_phone' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                            'country_code' => $country_code,
                            'referrer' => 'bod',
                            'ci_description' => '',
                            'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                        );

                        //$ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['ci_email']));
                        //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
                        $ProspectiveCustomers = ProspectiveCustomers::Create($inputProspectiveCustomers);
                        if ($ProspectiveCustomers->save()) {
                            $updateBodProspectiveCustomerId = BodCaracts::find($BoatOnDemand['id']);
                            $updateBodProspectiveCustomerId->prospective_customer_id = $ProspectiveCustomers['id'];
                            $updateBodProspectiveCustomerId->save();
                        }

                        $details = [];
                        if(!empty($datasRequest)) {
                            $boat_locations = config('youboat.'. $country_code .'.locations');
                            $boat_locations_regions = $boat_locations['regions'];
                            $boat_locations_counties = $boat_locations['counties'];

                            $details = array(
                                'adstype' => !empty($datasRequest['adstypes_id']) ? $this->getAdsTypeById($datasRequest['adstypes_id'])['name'] : null,
                                'category' => !empty($datasRequest['categories_ids']) ? $this->getCategoryById($datasRequest['categories_ids'])['name'] : null,
                                'subcategory' => !empty($datasRequest['subcategories_ids']) ? $this->getSubcategoryById($datasRequest['subcategories_ids'])['name'] : null,

                                'manufacturer' => !empty($datasRequest['manufacturers_id']) ? $this->getManufacturerById($datasRequest['manufacturers_id'])['name'] : null,
                                'model' => !empty($datasRequest['models_id']) ? $this->getModelById($datasRequest['models_id'])['name'] : null,

                                'manufacturer_engines' => !empty($datasRequest['manufacturersengines_id']) ? $this->getManufacturerEngineById($datasRequest['manufacturersengines_id'])['name'] : null,
                                'model_engine' => !empty($datasRequest['modelsengines_id']) ? $this->getModelEngineById($datasRequest['modelsengines_id'])['name'] : null,

                                'min_year_built' => !empty($datasRequest['min_year_built']) ? $datasRequest['min_year_built'] : null,
                                'max_year_built' => !empty($datasRequest['max_year_built']) ? $datasRequest['max_year_built'] : null,

                                'min_length' => !empty($datasRequest['min_length']) ? $datasRequest['min_length'] : null,
                                'max_length' => !empty($datasRequest['max_length']) ? $datasRequest['max_length'] : null,

                                'min_width' => !empty($datasRequest['min_width']) ? $datasRequest['min_width'] : null,
                                'max_width' => !empty($datasRequest['max_width']) ? $datasRequest['max_width'] : null,

                                'country' => !empty($datasRequest['countries_id']) ? $this->getCountryById($datasRequest['countries_id'])['name'] : null,
                                'region' => !empty($datasRequest['regions_id']) ? $boat_locations_regions[$datasRequest['regions_id']]['name'] : null,
                                'county' => !empty($datasRequest['counties_id']) ? $boat_locations_counties[$datasRequest['counties_id']] : null,

                                'budget' => !empty($datasRequest['budget']) ? $datasRequest['budget'] . $datasRequest['currency'] : null,
                                'sell_type' => !empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : null,

                                'description' => !empty($datasRequest['ci_description']) ? $datasRequest['ci_description'] : null,

                                'with_marina_berth' => (!empty($datasRequest['with_marina_berth']) && $datasRequest['with_marina_berth'] == 1) ? ucfirst(trans('boat_on_demand.with_marina_berth')) : null,
                                'agree_similar' => (!empty($datasRequest['agree_similar']) && $datasRequest['agree_similar'] == 1) ? ucfirst(trans('boat_on_demand.agree_similar')) : null,

                                'recovery_adstype' => !empty($datasRequest['recovery_adstypes_id']) ? $this->getAdsTypeById($datasRequest['recovery_adstypes_id'])['name'] : null,
                                'recovery_category' => !empty($datasRequest['recovery_categories_ids']) ? $this->getCategoryById($datasRequest['recovery_categories_ids'])['name'] : null,
                                'recovery_subcategory' => !empty($datasRequest['recovery_subcategories_ids']) ? $this->getSubcategoryById($datasRequest['recovery_subcategories_ids'])['name'] : null,

                                'recovery_manufacturer' => !empty($datasRequest['recovery_manufacturers_id']) ? $this->getManufacturerById($datasRequest['recovery_manufacturers_id'])['name'] : null,
                                'recovery_model' => !empty($datasRequest['recovery_models_id']) ? $this->getModelById($datasRequest['recovery_models_id'])['name'] : null,
                                'recovery_year_built' => !empty($datasRequest['recovery_year_built']) ? $datasRequest['recovery_year_built'] : null,
                                'recovery_manufacturer_engine' => !empty($datasRequest['recovery_manufacturersengines_id']) ? $this->getManufacturerEngineById($datasRequest['recovery_manufacturersengines_id'])['name'] : null,
                                'recovery_model_engine' => !empty($datasRequest['recovery_modelsengines_id']) ? $this->getModelEngineById($datasRequest['recovery_modelsengines_id'])['name'] : null,

                                'recovery_description' => !empty($datasRequest['recovery_description']) ? $datasRequest['recovery_description'] : null,

                                'recovery_budget' => !empty($datasRequest['recovery_budget']) ? $datasRequest['recovery_budget'] . $datasRequest['currency'] : null,

                                'first_name' => !empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null,
                                'last_name' => !empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null,
                                'email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                'password' => !empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null,
                                'phone' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                                'zip' => !empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null,
                                'city' => !empty($datasRequest['ci_city']) ? $datasRequest['ci_city'] : null,
                                'country' => !empty($datasRequest['ci_countries_id']) ? $this->getCountryById($datasRequest['ci_countries_id'])['name'] : null,
                                'region' => !empty($datasRequest['ci_regions_id']) ? $boat_locations_regions[$datasRequest['ci_regions_id']]['name'] : null,
                                'county' => !empty($datasRequest['ci_counties_id']) ? $boat_locations_counties[$datasRequest['ci_counties_id']] : null,

                                'agree_emails' => (!empty($datasRequest['agree_emails']) && $datasRequest['agree_emails'] == 1) ? ucfirst(trans('contact_informations.label_optin_agree_emails')) : null
                            );

                            $title  = htmlspecialchars_decode(title_case(trans('navigation.cgv')));
                            $url    = url(trans_route($currentLocale, 'routes.cgv'));
                            $terms_link = '<a href="' . $url . '" title="' . title_case($title) . '" target="_blank">' . $title .'</a>';
                            $details['agree_cgv'] = (!empty($datasRequest['agree_cgv']) && $datasRequest['agree_cgv'] == 1) ? ucfirst(trans('contact_informations.label_optin_agree_cgv', ['terms'=>$terms_link,'website_name'=>$country_code . '.' . env('APP_NAME')])) : null;
                        }
                        $datasEmail = array(
                            //'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null,
                            'details' => $details,
                            'website_name' => config('youboat.' . $country_code . '.website_name'),
                            'type_request' => 'a Boat On Demand request',
                            'name' => !empty($datasRequest['ci_last_name']) ? !empty($datasRequest['ci_firstname']) ? ucwords(mb_strtolower($datasRequest['ci_firstname'])) . ' ' . mb_strtoupper($datasRequest['ci_last_name']) : mb_strtoupper($datasRequest['ci_last_name']) : null,
                            'email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                            'password' => !empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null,
                            'country_code' => $country_code,
                            //'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc') . ',' . config('youboat.' . $country_code . '.country_manager_email'),
                            'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                            'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                            'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                        );
                        Mail::send('emails.bod', $datasEmail, function ($message) use ($datasEmail) {
                            $message->subject('Boat On Demand' . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                            $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.boat_on_demand') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                            $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            $message->to($datasEmail['email'], $datasEmail['name']);
                        });

                        $datasEmail['reference'] = !empty($datasRequest['reference']) ? $datasRequest['reference'] : null;
                        Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                            $message->subject($datasEmail['website_name'] . " > " . $datasEmail['type_request'] . ' ' . trans('emails.from') . ' ' . $datasEmail['email']);
                            $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.boat_on_demand') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                            $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            //$message->bcc(explode(',', $datasEmail['bcc_mails']));
                            $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
                        });

                        $message_referrer = 'boat_on_demand';
                        $message_title = trans('navigation.boat_on_demand');
                        $message_text = trans('emails.thanks_boat_on_demand');
                        $message_type = 'success';
                        $request->session()->put('bod_message.referrer', $message_referrer);
                        $request->session()->put('bod_message.title', $message_title);
                        $request->session()->put('bod_message.text', $message_text);
                        $request->session()->put('bod_message.type', $message_type);

                        $message = session()->get('bod_message');

                        if(!Auth::check()) {
                            Auth::login($User);
                        }

                        if(Auth::check()) {
                            $result = $this->getBodListing( Auth::user()->id, Auth::user()->email, Auth::user()->type);
                            $return +=  $result;
                            return redirect(trans_route($currentLocale, 'routes.dashboard'))->with($return)->withMessage($message);
                        } else {
                            return redirect(trans_route($currentLocale, 'routes.login'))->withMessage($message);
                        }

                    }
                }
            } catch(\Exception $e) {
                //var_dump($e->getMessage());
                //die();
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
