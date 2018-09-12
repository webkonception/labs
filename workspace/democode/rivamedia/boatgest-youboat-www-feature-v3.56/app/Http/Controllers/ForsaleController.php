<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Controllers\Controller;

    use App\PrivatesCaracts;
    use App\DealersCaracts;
    use App\CommercialsCaracts;
    use App\CustomersCaracts;

    use App\AdsCaracts;
    use App\BodCaracts;

    use Auth;
    use Redirect;
    use DB;

    class ForsaleController extends SearchController
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            //$this->middleware('auth');
            ////$this->middleware(['clearcache']);
        }

        public function index (Request $request) {
            //
            $viewName = app('request')->route()->getName();
            //return view($viewName, compact('request'))->with(['controller'=>$this]);
            return view($viewName);
        }
    /*
        public static function getAdsTypesList(){
            return $this->getAdsTypes('active', true);
        }

        public static function getAdsCategoriesList(){
            return $this->getCategories('', true);
        }
    */
        public function getAdsListing($user_id, $email, $user_type) {

            $usercaracts = '';
            switch($user_type) {
                case 'private':
                    $usercaracts = PrivatesCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                    break;
                case 'dealer':
                    $usercaracts = DealersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                    break;
                case 'commercial':
                    $usercaracts = CommercialsCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                    break;
                case 'customer':
                    $usercaracts = CustomersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                    break;
            }

            $customerscaracts = [];
            if(isset($usercaracts)) {
                $array = json_decode(json_encode($usercaracts), true);
                if (is_array($array) && !empty($array[0])) {
                    $customerscaracts = $array[0];
                }
            }
            $customerscaracts['email'] = $email;

            //$result = AdsCaracts::where('dealerscaracts_id', $customerscaracts['id'])
            $result = AdsCaracts::where('user_id', $user_id)
                ->where('status', '<>', 'nok')
                ->select('id', 'adstypes_id', 'manufacturers_id', 'models_id', 'ad_price', 'status', 'updated_at','ad_country_code', 'countries_id', 'ad_title')
                ->orderby('updated_at','DESC')
                ->get();

            $ads_listing = [];
            if(isset($result)) {
                $ads_listing = json_decode(json_encode($result), true);
            }

            return compact('customerscaracts', 'ads_listing');
        }

        public function getBodListing($user_id, $email, $user_type) {

            $usercaracts = '';
            switch($user_type) {
                case 'private':
                    $usercaracts = PrivatesCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                    break;
                case 'dealer':
                    $usercaracts = DealersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                    break;
                case 'commercial':
                    $usercaracts = CommercialsCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                    break;
                case 'customer':
                    $usercaracts = CustomersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name')->get();
                    break;
            }

            $customerscaracts = [];
            if(isset($usercaracts)) {
                $array = json_decode(json_encode($usercaracts), true);
                if (is_array($array) && !empty($array[0])) {
                    $customerscaracts = $array[0];
                }
            }
            $customerscaracts['email'] = $email;

            $result = BodCaracts::where('user_id', $user_id)
                ->where('ci_email', $email)
                ->select('id', 'adstypes_id', 'manufacturers_id', 'models_id', 'budget', 'status', 'updated_at')
                ->orderby('updated_at','DESC')
                ->get();

            $bod_listing = [];
            if(isset($result)) {
                $bod_listing = json_decode(json_encode($result), true);
            }

            return compact('customerscaracts', 'bod_listing');
        }

        /**
         * getDefaults(Request $request) {
         *
         * @return \Illuminate\Http\Response
         */
        public static function getDefaults($datasRequest) {
            //debug('>>> getDefaults <<<');
            //debug('$datasRequest');
            //debug($datasRequest);
            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code       = config('youboat.' . $currentCountryCode . '.country_code');

            /////////////////
            // START FILTERS
            $countries = $recovery_countries =  SearchController::getCountries();

            $_adsTypeId                 = isset($datasRequest['adstypes_id']) ? $datasRequest['adstypes_id'] : null;
            $_categoryId                = isset($datasRequest['categories_ids']) ? $datasRequest['categories_ids'] : null;
            $_manufacturersId           = isset($datasRequest['manufacturers_id']) ? $datasRequest['manufacturers_id'] : null;
            $_manufacturersenginesId    = isset($datasRequest['manufacturersengines_id']) ? $datasRequest['manufacturersengines_id'] : null;

            $_recovery_adsTypeId                 = isset($datasRequest['recovery_adstypes_id']) ? $datasRequest['recovery_adstypes_id'] : null;
            $_recovery_categoryId                = isset($datasRequest['recovery_categories_ids']) ? $datasRequest['recovery_categories_ids'] : null;
            $_recovery_manufacturersId           = isset($datasRequest['recovery_manufacturers_id']) ? $datasRequest['recovery_manufacturers_id'] : null;
            $_recovery_manufacturersenginesId    = isset($datasRequest['recovery_manufacturersengines_id']) ? $datasRequest['recovery_manufacturersengines_id'] : null;

            /////////////////
            $selltypes = $recovery_selltypes = SearchController::getAdsSellTypes();
            //$years_built = $recovery_years_built = SearchController::getGateWayYearsBuilt();

            $adstypes               = SearchController::getAdsTypes('active', false, [4,5,9,10,11]); // exclude boat-engines, other, boat-trailers, pontoon-mooring, location
            $categories             = SearchController::getCategories($_adsTypeId);
            $subcategories          = $_categoryId ? SearchController::getSubcategories($_categoryId) : [];
            $manufacturers          = SearchController::getManufacturers();
            $models                 = $_manufacturersId ? SearchController::getModels($_manufacturersId) : [];
            $manufacturersengines   = $recovery_manufacturersengines = SearchController::getManufacturersEngines();
            $modelsengines          = $_manufacturersenginesId ? SearchController::getModelsEngines($_manufacturersenginesId) : [];

            $recovery_adstypes              = SearchController::getAdsTypes('active', false, [4,5,9,10,11]); // exclude boat-engines, other, boat-trailers, pontoon-mooring, location
            $recovery_categories            = SearchController::getCategories($_recovery_adsTypeId);
            $recovery_subcategories         = $_recovery_categoryId ? SearchController::getSubcategories($_recovery_categoryId) : [];
            $recovery_manufacturers         = SearchController::getManufacturers();
            $recovery_models                = $_recovery_manufacturersId ? SearchController::getModels($_recovery_manufacturersId) : [];
            $recovery_manufacturersengines  = SearchController::getManufacturersEngines();
            $recovery_modelsengines         = $_recovery_manufacturersenginesId ? SearchController::getModelsEngines($_recovery_manufacturersenginesId) : [];

            $adstype        = $_manufacturersenginesId ? SearchController::getAdsType('engines', false, true) : null;

            $sell_type      = !empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : null;

            // END FILTERS
            /////////////////

            //$arrayGetAdsList['countries_id'] = (!empty($datasRequest['countries_id']) ? SearchController::getCountry($datasRequest['countries_id']) : SearchController::getCountry($country_code)['id']);

            $countries_id = '';
            if(!empty($datasRequest['countries_id'])) {
                $countryId = '';
                $country = SearchController::getCountry($country_code);
                if(isset($country) && array_key_exists('id', $country)) {
                    $countryId = $country['id'];
                }
                $countries_id = $countryId;
            }
            if(empty($countries_id)) {
                $currentCountryId = '';
                $currentCountry = SearchController::getCountry($country_code);
                if(isset($currentCountry) && array_key_exists('id', $currentCountry)) {
                    $currentCountryId = $currentCountry['id'];
                }
                $countries_id = $currentCountryId;
            }
            $arrayGetAdsList['countries_id'] = $countries_id;
            $arrayGetAdsList['results_view'] = 'list';
            $arrayGetAdsList['max'] = 12;
            $arrayGetAdsList['sort_by'] = 'updated_at-desc';

            $ads_list = [];
            $viewName = app('request')->route()->getName();
            if ($viewName  != 'boat_on_demand' && $viewName  != 'sell' && !preg_match('/dashboard/', $viewName) && !preg_match('/^boatgest/', $viewName)) {
                $ads_list = SearchController::getAdsList($arrayGetAdsList);
            }

            ////////////////
            ////////////////
            /*
            $total_used_boats = SearchController::getTotal('sell_type', 'used'); // cumul used and empty values
            $total_new_boats = SearchController::getTotal('sell_type', 'new');
            */

            //PATCH SCRAPPING
            $min_ad_price = 1000;
            $WherePrice = ' AND ' . 'ad_price >= ' . $min_ad_price .' ';

            $total_used_boats = SearchController::getTotal('sell_type', 'used', $WherePrice . ') OR (ad_country_code = "' . $currentCountryCode . '" AND sell_type = ""' . $WherePrice . ') OR (ad_country_code = "' . $currentCountryCode . '" AND sell_type = "used"' . $WherePrice . ')'); // cumul used and empty values
            $total_new_boats = SearchController::getTotal('sell_type', 'new', $WherePrice . ') OR (ad_country_code = "' . $currentCountryCode . '" AND sell_type = "new"' . $WherePrice . ')');
            //PATCH SCRAPPING /

            return compact(
                'total_new_boats',
                'total_used_boats',

                'ads_list',

                'datasRequest',

                'countries',

                'selltypes',
                'adstypes','categories','subcategories',
                'manufacturers','models',
                'manufacturersengines','modelsengines',

                'recovery_adstypes','recovery_categories','recovery_subcategories',
                'recovery_manufacturers','recovery_models',
                'recovery_manufacturersengines','recovery_modelsengines',

                'adstype',
                'sell_type',
                'years_built'
            );
        }

        /**
         * showAds
         *
         * @return \Illuminate\Http\Response
         */
        public function showAds(Request $request) {
            //debug('>>> showAds @!!@ <<<');

            $count = false;
            if ($request) {
                $currentLocale = config('app.locale');
                $datasRequest = $request->all();
                $routeParameters = $request->route()->parameters();
                $query = !empty($datasRequest['query']) ? $datasRequest['query'] : null;

                if (($viewName = app('request')->route()->getName()) != 'homepage') {
                    if (!$query) {
                        switch($viewName) {
                            case 'showAdsByType' :
                                $search = $routeParameters['adstypes'];
                                $return = $this->getAdsType($search);
                                break;

                            case 'showAdsByCategory' :
                                $search = $routeParameters['categories'];
                                $return = $this->getCategory($search);
                                break;

                            case 'showAdsBySubcategory' :
                                $search = $routeParameters['subcategories'];
                                $return = $this->getSubcategory($search);
                                break;

                            case 'showAdsByManufacturer' :
                                $search = $routeParameters['manufacturers'];
                                $return = $this->getManufacturer($search);
                                //##$return = $this->getGateWayManufacturerByName($search);
                                break;

                            case 'showAdsByModel' :
                                $search = $routeParameters['models'];
                                $return = $this->getModel($search);
                                //##$return = $this->getGateWayModelByName($search,true)[0];
                                break;

                            case 'showAdsByManufacturerEngine' :
                                $search = $routeParameters['manufacturersengines'];
                                $return = $this->getManufacturerEngine($search);
                                //##$return = $this->getGateWayManufacturerEngineByName($search);
                                break;

                            case 'showAdsByModelEngine' :
                                $search = $routeParameters['modelssengines'];
                                $return = $this->getModelEngine($search);
                                //##$return = $this->getGateWayModelByName($search,true)[0];
                                break;
                        }
                        if(isset($return) && is_array($return) && empty($return)) {
                            if(!empty($routeParameters['manufacturers']) && !empty($routeParameters['models'])) {
                                //echo '<br>manufacturers ok / models  ok
                                $error_code = 'ad_not_found';
                                $routeParameters['error_code'] = $error_code;

                                $q = '';

                                $model = $this->getModel($routeParameters['models']);
                                $manufacturer = $this->getManufacturerByName($routeParameters['manufacturers']);

                                if(!array_key_exists('id', $manufacturer)) {
                                    //echo '<br>$manufacturer['id\'] ko<br>';
                                    //$manufacturer = $this->getGateWayManufacturerByName(str_slug($routeParameters['manufacturers'], ' '));
                                    $manufacturer = $this->getManufacturer(str_slug($routeParameters['manufacturers'], ' '));
                                    $route = trans_route($currentLocale, 'routes.for_sale');
                                } else {
                                    //echo '<br>$manufacturer['id'] ok
                                    $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model') . '/' . $routeParameters['manufacturers'] . '/' . $routeParameters['models'];
                                }

                                if(!array_key_exists('id', $model)) {
                                    //echo '<br>$model['id\'] ko<br>';
                                    //$model = $this->getGateWayModelByName(str_slug($routeParameters['models'], ' '));
                                    $model = $this->getModelEngine(str_slug($routeParameters['models'], ' '));
                                } else {
                                    //echo '<br>$model['id'] ok
                                }

                                if(!empty($routeParameters['adstypes'])) {
                                    //echo '<br>adstypes<br>';
                                    $adstype = $this->getAdsTypeByName($routeParameters['adstypes']);
                                    if(isset($adstype['id']) && !empty($adstype['id'])) {
                                        //echo '<br>$adstype['id'] ok
                                        if(!array_key_exists('name', $manufacturer)) {
                                            $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                        } else {
                                            $q .= (!empty($q) ? '&' : '/?') . 'adstypes_id=' . $adstype['id'];
                                        }
                                    }
                                }
                                if(!$manufacturer && !$model && isset($adstype['id']) && !empty($adstype['id'])) {
                                    $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                    return redirect($route, 301)->with(compact('routeParameters'));
                                }
                                if((isset($manufacturer['id']) && !empty($manufacturer['id'])) && (isset($model['id']) && !empty($model['id']))) {
                                    $route .= $q;
                                    return redirect($route, 301)->with(compact('routeParameters'));
                                }
                                if(!array_key_exists('name', $manufacturer) && !array_key_exists('name', $model)) {
                                    $route = trans_route($currentLocale, 'routes.for_sale');
                                } else {
                                    if(!array_key_exists('name', $manufacturer) && !$model) {
                                        //echo '<br>getGateWayManufacturerByName $manufacturer['name\'] ko<br>';
                                        $q .= (!empty($q) ? '&' : '/?') . 'query=' . str_slug($routeParameters['manufacturers'], '+');
                                    } else {
                                        //echo '<br>getGateWayManufacturerByName $manufacturer['name'] ok
                                        //$q .= (!empty($q) ? ' ' : '/?query=') . '' . $manufacturer['name'];
                                    }
                                    if($model) {
                                        if (!array_key_exists('name', $model)) {
                                            //echo '<br>getGateWayModelByName $model['name\'] ko<br>';
                                            $q .= (!empty($q) ? '&' : '/?') . 'query=' . str_slug($routeParameters['models'], '+');
                                        } else {
                                            //echo '<br>getGateWayModelByName $model['name'] ok
                                            //$q .= (!empty($q) ? ' ' : '/?query=') . '' . $model['name'];
                                            if (!array_key_exists('id', $model) || (isset($model['id']) && empty($model['id']))) {
                                                //echo '<br>$model['id\'] ko<br>';
                                                $q .= (!empty($q) ? '&' : '/?') . 'query=' . $model['name'];
                                            } elseif (isset($model['id']) && !empty($model['id'])) {
                                                //echo '<br>$model['id'] ok
                                                $q .= (!empty($q) ? '&' : '/?') . 'query=' . $model['name'];
                                            }
                                        }
                                    } else {
                                        $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer') . '/' . $routeParameters['manufacturers'];
                                    }
                                }
                                $route .= $q;
                                return redirect($route, 301)->with(compact('routeParameters'));
                            } elseif(!empty($routeParameters['manufacturers'])) {
                                //echo '<br>manufacturers  ok
                                $error_code = 'ad_not_found';
                                $routeParameters['error_code'] = $error_code;

                                $q = '';

                                $manufacturer = $this->getManufacturerByName($routeParameters['manufacturers']);

                                if(!array_key_exists('id', $manufacturer)) {
                                    //echo '<br>$manufacturer['id\'] ko<br>';
                                    //$manufacturer = $this->getGateWayManufacturerByName(str_slug($routeParameters['manufacturers'], ' '));
                                    $manufacturer = $this->getManufacturer(str_slug($routeParameters['manufacturers'], ' '));
                                    $route = trans_route($currentLocale, 'routes.for_sale');
                                } else {
                                    //echo '<br>$manufacturer['id'] ok
                                    $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer') . '/' . $routeParameters['manufacturers'];
                                }

                                if(!array_key_exists('name', $manufacturer)) {
                                    $route = trans_route($currentLocale, 'routes.for_sale');
                                } else {
                                    //echo '<br>getGateWayManufacturerByName $manufacturer['name'] ok
                                }

                                if(!empty($routeParameters['adstypes'])) {
                                    //echo '<br>adstypes<br>';
                                    $adstype = $this->getAdsTypeByName($routeParameters['adstypes']);
                                    if(isset($adstype['id']) && !empty($adstype['id'])) {
                                        //echo '<br>$adstype['id'] ok
                                        if(!array_key_exists('name', $manufacturer)) {
                                            $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                        } else {
                                            $q .= (!empty($q) ? '&' : '/?') . 'adstypes_id=' . $adstype['id'];
                                        }
                                    }
                                }
                                $route .= $q;
                                return redirect($route, 301)->with(compact('routeParameters'));
                            } elseif(!empty($routeParameters['manufacturersengines']) && !empty($routeParameters['modelsengines'])) {
                                //echo '<br>manufacturersengines ok / modelsengines  ok
                                $error_code = 'ad_not_found';
                                $routeParameters['error_code'] = $error_code;

                                $q = '';

                                $modelengine = $this->getModel($routeParameters['modelsengines']);
                                $manufacturerengine = $this->getManufacturerByName($routeParameters['manufacturersengines']);

                                if(!array_key_exists('id', $manufacturerengine)) {
                                    //echo '<br>$manufacturerengine['id\'] ko<br>';
                                    //$manufacturerengine = $this->getGateWayManufacturerByName(str_slug($routeParameters['manufacturersengines'], ' '));
                                    $manufacturerengine = $this->getManufacturer(str_slug($routeParameters['manufacturersengines'], ' '));
                                    $route = trans_route($currentLocale, 'routes.for_sale');
                                } else {
                                    //echo '<br>$manufacturerengine['id'] ok
                                    $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model_engine') . '/' . $routeParameters['manufacturersengines'] . '/' . $routeParameters['modelsengines'];
                                }

                                if(!array_key_exists('id', $modelengine)) {
                                    //echo '<br>$modelengine['id\'] ko<br>';
                                    $modelengine = $this->getGateWayModelByName(str_slug($routeParameters['modelsengines'], ' '));
                                } else {
                                    //echo '<br>$modelengine['id'] ok
                                }

                                if(!empty($routeParameters['adstypes'])) {
                                    //echo '<br>adstypes<br>';
                                    $adstype = $this->getAdsTypeByName($routeParameters['adstypes']);
                                    if(isset($adstype['id']) && !empty($adstype['id'])) {
                                        //echo '<br>$adstype['id'] ok
                                        if(!array_key_exists('name', $manufacturerengine)) {
                                            $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                        } else {
                                            $q .= (!empty($q) ? '&' : '/?') . 'adstypes_id=' . $adstype['id'];
                                        }
                                    }
                                }
                                if(!$manufacturerengine && !$modelengine && isset($adstype['id']) && !empty($adstype['id'])) {
                                    $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                    return redirect($route, 301)->with(compact('routeParameters'));
                                }
                                if((isset($manufacturerengine['id']) && !empty($manufacturerengine['id'])) && (isset($modelengine['id']) && !empty($modelengine['id']))) {
                                    $route .= $q;
                                    return redirect($route, 301)->with(compact('routeParameters'));
                                }

                                if(!array_key_exists('name', $manufacturerengine) && !array_key_exists('name', $modelengine)) {
                                    $route = trans_route($currentLocale, 'routes.for_sale');
                                } else {
                                    if(!array_key_exists('name', $manufacturerengine) && !$modelengine) {
                                        //echo '<br>getGateWayManufacturerByName $manufacturerengine['name\'] ko<br>';
                                        $q .= (!empty($q) ? '&' : '/?') . 'query=' . str_slug($routeParameters['manufacturers'], '+');
                                    } else {
                                        //echo '<br>getGateWayManufacturerByName $manufacturerengine['name'] ok
                                        //$q .= (!empty($q) ? ' ' : '/?query=') . '' . $manufacturerengine['name'];
                                    }
                                    if($modelengine) {
                                        if (!array_key_exists('name', $modelengine)) {
                                            //echo '<br>getGateWayModelByName $modelengine['name\'] ko<br>';
                                            $q .= (!empty($q) ? '&' : '/?') . 'query=' . str_slug($routeParameters['models'], '+');
                                        } else {
                                            //echo '<br>getGateWayModelByName $modelengine['name'] ok
                                            //$q .= (!empty($q) ? ' ' : '/?query=') . '' . $modelengine['name'];
                                            if (!array_key_exists('id', $modelengine) || (isset($modelengine['id']) && empty($modelengine['id']))) {
                                                //echo '<br>$modelengine['id\'] ko<br>';
                                                $q .= (!empty($q) ? '&' : '/?') . 'query=' . $modelengine['name'];
                                            } elseif (isset($modelengine['id']) && !empty($modelengine['id'])) {
                                                //echo '<br>$modelengine['id'] ok
                                                $q .= (!empty($q) ? '&' : '/?') . 'query=' . $modelengine['name'];
                                            }
                                        }
                                    } else {
                                        $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer_engine') . '/' . $routeParameters['manufacturers'];
                                    }
                                }
                                $route .= $q;
                                return redirect($route, 301)->with(compact('routeParameters'));
                            } elseif(!empty($routeParameters['manufacturersengines'])) {
                                //echo '<br>manufacturersengines  ok
                                $error_code = 'ad_not_found';
                                $routeParameters['error_code'] = $error_code;

                                $q = '';

                                $manufacturerengine = $this->getManufacturerByName($routeParameters['manufacturersengines']);

                                if(!array_key_exists('id', $manufacturerengine)) {
                                    //echo '<br>$manufacturerengine['id\'] ko<br>';
                                    //$manufacturerengine = $this->getGateWayManufacturerByName(str_slug($routeParameters['manufacturersengines'], ' '));
                                    $manufacturerengine = $this->getManufacturer(str_slug($routeParameters['manufacturersengines'], ' '));
                                    $route = trans_route($currentLocale, 'routes.for_sale');
                                } else {
                                    //echo '<br>$manufacturerengine['id'] ok
                                    $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer_engine') . '/' . $routeParameters['manufacturersengines'];
                                }

                                if(!array_key_exists('name', $manufacturerengine)) {
                                    $route = trans_route($currentLocale, 'routes.for_sale');
                                } else {
                                    //echo '<br>getGateWayManufacturerByName $manufacturerengine['name'] ok
                                }

                                if(!empty($routeParameters['adstypes'])) {
                                    //echo '<br>adstypes<br>';
                                    $adstype = $this->getAdsTypeByName($routeParameters['adstypes']);
                                    if(isset($adstype['id']) && !empty($adstype['id'])) {
                                        //echo '<br>$adstype['id'] ok
                                        if(!array_key_exists('name', $manufacturerengine)) {
                                            $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                        } else {
                                            $q .= (!empty($q) ? '&' : '/?') . 'adstypes_id=' . $adstype['id'];
                                        }
                                    }
                                }
                                $route .= $q;
                                return redirect($route, 301)->with(compact('routeParameters'));
                            } elseif(!empty($routeParameters['adstypes']) && !empty($routeParameters['categories'])) {
                                $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'] . '?query=' . str_slug(str_replace('/',' ', $routeParameters['categories']));
                                $error_code = 'ad_not_found';
                                $routeParameters['error_code'] = $error_code;
                                return redirect($route, 301)->with(compact('routeParameters'));
                            } elseif(!empty($routeParameters['adstypes'])) {
                                $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                $error_code = 'ad_not_found';
                                $routeParameters['error_code'] = $error_code;
                                return redirect($route, 301)->with(compact('routeParameters'));
                            } else {
                                $route = trans_route($currentLocale, 'routes.ad_not_found');
                                $error_code = 'ad_not_found';
                                $routeParameters['error_code'] = $error_code;
                                return redirect($route, 301)->with(compact('routeParameters'));
                            }

                        }
                    }
                    $viewName       = 'for_sale';
                }

                $adstype = [];
                $category = [];
                $subcategory = [];

                $manufacturer = [];
                $model = [];

                $manufacturerengine = [];
                $modelengine = [];

                ////////////////////////////////////
                // RETRIEVE DATAS IF URL REWRITTING
                if ($viewName != 'homepage') {
                    // @TODO : Start redo 301 showAds
                    if(!empty($routeParameters['adstypes'])) {
                        $_adsType = $routeParameters['adstypes'];
                        $adstype = $this->getAdsType($_adsType);
                    }

                    if (!empty($routeParameters['categories'])) {
                        $_adsCategory = $routeParameters['categories'];
                        $category   = $this->getCategory($_adsCategory);
                        if (isset($category['adstypes_id']) && isset($adstype['id']) && $category['adstypes_id'] != $adstype['id']) {
                            $adstype = $this->getAdsTypeByCategoryId($category['id']);
                        }
                    }

                    if (!empty($routeParameters['subcategories'])) {
                        $_adsSubcategory = $routeParameters['subcategories'];
                        $subcategory   = $this->getSubcategory($_adsSubcategory);
                        if (isset($subcategory['categories_id']) && isset($category['id']) && $subcategory['categories_id'] != $category['id']) {
                            $category = $this->getAdsCategoryBySubcategoryId($subcategory['id']);
                        }
                        if(!empty($category)) {
                            $adstype = $this->getAdsTypeByCategoryId($category['id']);
                        } else {
                            unset($subcategory);
                        }
                    }

                    if (!empty($routeParameters['manufacturers'])) {
                        $_adsManufacturer = $routeParameters['manufacturers'];
                        $manufacturer    = $this->getManufacturer($_adsManufacturer);
                        //##$manufacturer   = $this->getGateWayManufacturerByName($_adsManufacturer);
                    }

                    if (!empty($routeParameters['manufacturers']) && !empty($routeParameters['models'])) {
                        $_adsModel = $routeParameters['models'];
                        if(isset($manufacturer['id'])) {
                            $model   = $this->getModelByName($manufacturer['id'], $_adsModel);
                        } else {
                            $model   = $this->getModel($_adsModel);
                            $manufacturer    = $this->getManufacturerByModelId($model['id']);
                            //##$model   = getGateWayModelByName($_adsModel,true)[0];
                        }

                        if (isset($model['manufacturers_id']) && isset($manufacturer['id']) && $model['manufacturers_id'] != $manufacturer['id']) {
                            $manufacturer = $this->getManufacturerByModelId($model['id']);
                        }

                        if(empty($manufacturer)) {
                            unset($model);
                        }
                    }

                    if (!empty($routeParameters['manufacturersengines'])) {
                        $_adsManufacturerEngine = $routeParameters['manufacturersengines'];
                        $adstype        = $this->getAdsType('engines', false, true);
                        $manufacturerengine    = $this->getManufacturerEngine($_adsManufacturerEngine);
                    }

                    if (!empty($routeParameters['manufacturersengines']) && !empty($routeParameters['modelsengines'])) {
                        $_adsModelEngine = $routeParameters['modelsengines'];
                        if(isset($manufacturerengine['id'])) {
                            $model   = $this->getModelEngineByName($manufacturerengine['id'], $_adsModelEngine);
                        } else {
                            $modelengine   = $this->getModelEngine($_adsModelEngine);
                        }

                        if (isset($modelengine['manufacturersengines_id']) && isset($manufacturerengine['id']) && $modelengine['manufacturersengines_id'] != $manufacturerengine['id']) {
                            $manufacturerengine = $this->getManufacturerEngineByModelEngineId($modelengine['id']);
                        }
                        if(empty($manufacturerengine)) {
                            unset($modelengine);
                        }
                    }
                    // @TODO : End redo 301 showAds

                    if (!empty($adstype['id'])) {
                        $datasRequest['adstypes_id'] = $adstype['id'];
                    }
                    if (!empty($category['id'])) {
                        $datasRequest['categories_ids'] = $category['id'];
                    }
                    if (!empty($subcategory['id'])) {
                        $datasRequest['subcategories_ids'] = $subcategory['id'];
                    }
                    if (!empty($manufacturer['id'])) {
                        $datasRequest['manufacturers_id'] = $manufacturer['id'];
                    }
                    if (!empty($model['id'])) {
                        $datasRequest['models_id'] = $model['id'];
                    }
                    if (!empty($manufacturerengine['id'])) {
                        $datasRequest['manufacturersengines_id'] = $manufacturerengine['id'];
                    }
                    if (!empty($modelengine['id'])) {
                        $datasRequest['modelsengines_id'] = $modelengine['id'];
                    }

                    // END RETRIEVE DATAS IF URL REWRITTING
                    ///////////////////////////////////////
                } else {
                    //$datasRequest['no_empty_photo'] = true;
                }

                ////////////////////////////////////
                // RETRIEVE DATAS FROM REQUEST
                $_adsTypeId                 = isset($datasRequest['adstypes_id']) ? $datasRequest['adstypes_id'] : null;
                $_categoryId                = isset($datasRequest['categories_ids']) ? $datasRequest['categories_ids'] : null;
                $_subcategoryId             = isset($datasRequest['subcategories_ids']) ? $datasRequest['subcategories_ids'] : null;
                $_manufacturersId           = isset($datasRequest['manufacturers_id']) ? $datasRequest['manufacturers_id'] : null;
                $_manufacturersenginesId    = isset($datasRequest['manufacturersengines_id']) ? $datasRequest['manufacturersengines_id'] : null;
                $_modelId                   = isset($datasRequest['models_id']) ? $datasRequest['models_id'] : null;
                $_modelengineId             = isset($datasRequest['modelsengines_id']) ? $datasRequest['modelsengines_id'] : null;
                // END RETRIEVE DATAS FROM REQUEST

                ///////////////////////
                // START FILTERS DATAS

                //$adstypes       = $this->getAdsTypes();
                //$array = SearchController::getGateWayAdsTypes();
                $getAdsTypes = SearchController::getAdsTypes('active',true);
                $array = is_array($getAdsTypes) ? $getAdsTypes : $getAdsTypes->toArray();
                //@TODO : try to user array_map
                foreach($array as $key => $val) {
                     //$result = SearchController::getAdsType($val[0]);
                    $result = SearchController::getAdsType($val);
                    if(isset($result['id'])) {
                        if($count) {
                            $array[$result['id']] = trans('adstypes.' . trim($result['rewrite_url'])) . ' (' . $val['count'] .')';
                        } else {
                            $array[$result['id']] = trans('adstypes.' . trim($result['rewrite_url'])) ;
                        }
                    }
                }
                $adstypes = $array;

                $categories    = $array = [];
                //$array = SearchController::getGateWayAdsCategories($_adsTypeId);
                if($_adsTypeId) {
                    $getCategories = SearchController::getCategories($_adsTypeId);
                    $array = is_array($getCategories) ? $getCategories : $getCategories->toArray();

                    //@TODO : try to user array_map
                    foreach ($array as $key => $val) {
                        //$result = SearchController::getCategory($val[0]);
                        $result = SearchController::getCategory($val);
                        if(isset($result['id'])) {
                            if($count) {
                                $array[$result['id']] = trans('categories.' . trim($result['rewrite_url'])) . ' (' . $val['count'] . ')';
                            } else {
                                $array[$result['id']] = trans('categories.' . trim($result['rewrite_url']));
                            }
                        }
                    }
                }
                $categories = $array;

                //$subcategories  = $_categoryId ? $this->getSubcategories($_categoryId) : [];
                $subcategories    = $array = [];
                //$array = SearchController::getGateWayAdsSubcategories($_categoryId);
                if($_categoryId) {
                    $getSubcategories = SearchController::getSubcategories($_categoryId);
                    $array = is_array($getSubcategories) ? $getSubcategories : $getSubcategories->toArray();
                    //@TODO : try to user array_map
                    foreach($array as $key => $val) {
                        $result = SearchController::getSubcategory($val);
                        //$result = SearchController::getSubcategory($val[0]);
                        if(isset($result['id'])) {
                            if($count) {
                                $array[$result['id']] = trans('subcategories.' . trim($result['rewrite_url'])) . ' (' . $val['count'] .')';
                            } else {
                                $array[$result['id']] = trans('subcategories.' . trim($result['rewrite_url']));
                            }
                        }
                    }
                }
                $subcategories = $array;

                //$manufacturers  = $this->getManufacturers();
                //$array = SearchController::getGateWayAdsManufacturers($_adsTypeId, $_categoryId, $_subcategoryId);
                //$array = SearchController::getManufacturers($_adsTypeId, $_categoryId, $_subcategoryId);

                $manufacturers = $array = [];
                $getManufacturers = SearchController::getManufacturers();
                $array = is_array($getManufacturers) ? $getManufacturers : $getManufacturers->toArray();
//                foreach($array as $key => $val) {
//                    $result = SearchController::getManufacturer($val[0]);
//                    if(isset($result['id'])) {
//                        if($count) {
//                            $array[$result['id']] = ucwords(trim($result['name'])) . ' (' . $val['count'] .')';
//                        } else {
//                            $array[$result['id']] = ucwords(trim($result['name']));
//                        }
//                    }
//               }
                $manufacturers = $array;

                //$models         = $_manufacturersId ? $this->getModels($_manufacturersId) : [];
                //$array = SearchController::getGateWayAdsModels($_adsTypeId, $_categoryId, $_subcategoryId, $_manufacturersId);
                //$array = SearchController::getGateWayAdsModels($_manufacturersId);
                //$array = SearchController::getModels($_manufacturersId);
                $models = $array = [];
                if($_manufacturersId) {
                    $getModels = SearchController::getModels($_manufacturersId);
                    $array = is_array($getModels) ? $getModels : $getModels->toArray();
//                foreach($array as $key => $val) {
//                    $result = SearchController::getModel($val[0]);
//                    if(isset($result['id'])) {
//                        if($count) {
//                            $array[$result['id']] = ucwords(trim($result['name'])) . ' (' . $val['count'] .')';
//                        } else {
//                            $array[$result['id']] = ucwords(trim($result['name']));
//                        }
//                    }
//                }
                }
                $models = $array;
                //$manufacturersengines = $this->getManufacturersEngines();
                //$modelsengines  = $_manufacturersenginesId ? $this->getModelsEngines($_manufacturersenginesId) : [];

                $selltypes      = $this->getAdsSellTypes();
                /*$array = SearchController::getGateWaySellType($_adsTypeId, $_categoryId, $_subcategoryId, $_modelId);
                foreach($array as $key => $val) {
                    if($count) {
                        $array[$val[0]] = trim($val[0]) . ' (' . $val['count'] .')';
                    } else {
                        $array[$val[0]] = trim($val[0]);
                    }
                }
                $selltypes = $array;
                */
                $years_built    = $recovery_years_built = $this->getGateWayYearsBuilt();
                //@TODO range
                //$ad_prices      = $recovery_ad_prices = $this->getGateWayAdPrices();

                //$countries = $recovery_countries =  $this->getCountries();
                $countries = null;

                // END FILTERS DATAS
                ////////////////////

                ///////////////////////////////////////////
                // START CHECK IF CHOICES ALREADY SELECTED
                if (empty($adstype) && $_adsTypeId) {
                    //$adstype        = $_manufacturersenginesId ? $this->getAdsType('engines', false, true) : null;
                    $adstype = $this->getAdsType($_adsTypeId);
                }
                if (empty($category) && $_categoryId) {
                    $category   = $this->getCategory($_categoryId);
                    if (isset($category['adstypes_id']) && isset($adstype['id']) && $category['adstypes_id'] != $adstype['id']) {
                        $adstype = $this->getAdsTypeByCategoryId($category['id']);
                    }
                }
                if (empty($subcategory) && $_subcategoryId) {
                    $subcategory   = $this->getSubcategory($_subcategoryId);
                    if (isset($subcategory['categories_id']) && isset($category['id']) && $subcategory['categories_id'] != $category['id']) {
                        $category = $this->getAdsCategoryBySubcategoryId($subcategory['id']);
                    }
                    if(!empty($category)) {
                        $adstype = $this->getAdsTypeByCategoryId($category['id']);
                    } else {
                        unset($subcategory);
                    }
                }
                if (empty($manufacturer) && $_manufacturersId) {
                    $manufacturer    = $this->getManufacturer($_manufacturersId);
                    //$manufacturer   = $this->getGateWayManufacturerByName($_manufacturersId);
                }
                if (empty($model) && $_modelId) {
                    if(isset($manufacturer['id'])) {
                        $model   = $this->getModelByName($manufacturer['id'], $_modelId);
                    } else {
                        $model   = $this->getModel($_modelId);
                    }
                    if (isset($model['manufacturers_id']) && isset($manufacturer['id']) && $model['manufacturers_id'] != $manufacturer['id']) {
                        $manufacturer = $this->getManufacturerByModelId($model['id']);
                    }
                    if(empty($manufacturer)) {
                        unset($model);
                    }
                }

                if (empty($manufacturerengine) && $_manufacturersenginesId) {
                    $adstype        = $this->getAdsType('engines', false, true);
                    $manufacturerengine    = $this->getManufacturerEngine($_manufacturersenginesId);
                }
                if (empty($modelengine) && $_modelengineId) {
                    if(isset($manufacturerengine['id'])) {
                        $model   = $this->getModelEngineByName($manufacturerengine['id'], $_modelengineId);
                    } else {
                        $modelengine   = $this->getModelEngine($_modelengineId);
                    }
                    if (isset($modelengine['manufacturersengines_id']) && isset($manufacturerengine['id']) && $modelengine['manufacturersengines_id'] != $manufacturerengine['id']) {
                        $manufacturerengine = $this->getManufacturerEngineByModelEngineId($modelengine['id']);
                    }
                    if(empty($manufacturerengine)) {
                        unset($modelengine);
                    }
                }

                $sell_type      = !empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : null;
                // END CHECK IF CHOICES ALREADY SELECTED
                /////////////////////////////////////////

                if (!empty($query) && empty($datasRequest['manufacturers_id'])) {
                    $datasRequest['manufacturers_id'] = null;
                }

                // GET ADS's LISTING
                if(!isset($datasRequest['countries_id']) && !empty(config('app.country_code'))) {
                    $currentCountryCode = mb_strtolower(config('app.country_code'));
                    $country_code       = config('youboat.' . $currentCountryCode . '.country_code');
                    $getCountry = SearchController::getCountry(config('youboat.' . $currentCountryCode . '.country_code'));
                    $countries_id = array_key_exists('id', $getCountry) ? $getCountry['id'] : 77;
                    $datasRequest['countries_id'] = $countries_id;
                }

                /*
                $total_used_boats = $this->getTotal('sell_type', 'used'); // cumul used and empty values
                $total_new_boats = $this->getTotal('sell_type', 'new');
                */
                //PATCH SCRAPPING
                //$min_ad_price = 1000;
                //$WherePrice = ' AND ' . 'ad_price >= ' . $min_ad_price .' ';

                //$total_used_boats = $this->getTotal('sell_type', 'used', $WherePrice . ') OR (ad_country_code = "' . $currentCountryCode . '" AND sell_type = ""' . $WherePrice . ') OR (ad_country_code = "' . $currentCountryCode . '" AND sell_type = "used"' . $WherePrice . ')'); // cumul used and empty values
                //$total_new_boats = $this->getTotal('sell_type', 'new', $WherePrice . ') OR (ad_country_code = "' . $currentCountryCode . '" AND sell_type = "new"' . $WherePrice . ')');
                //PATCH SCRAPPING /
                $ads_list = $this->getAdsList($datasRequest);

                if ($viewName != 'homepage') {
                    return view($viewName, compact(
                        'datasRequest',

                        //'total_new_boats',
                        //'total_used_boats',

                        'countries',
                        'query',
                        //'gateawayManufacturers',
                        'ads_list',

                        'selltypes',
                        'adstypes', 'categories', 'subcategories',
                        'manufacturers', 'models',
                        //'manufacturersengines','modelsengines',

                        'adstype', 'category', 'subcategory',
                        'manufacturer', 'model',
                        'manufacturerengine', 'modelsengine',
                        'sell_type',
                        'years_built',
                        'ad_prices'
                    ))->with(['controller' => $this]);
                } else {
                    return compact(
                        'datasRequest',

                        //'total_new_boats',
                        //'total_used_boats',

                        'countries',
                        'query',
                        //'gateawayManufacturers',
                        'ads_list',

                        'selltypes',
                        'adstypes', 'categories', 'subcategories',
                        'manufacturers', 'models',
                        //'manufacturersengines','modelsengines',

                        'adstype', 'category', 'subcategory',
                        'manufacturer', 'model',
                        'manufacturerengine', 'modelsengine',
                        'sell_type',
                        'years_built',
                        'ad_prices'
                    );
                }
            } else {
                return view('for_sale');
            }
        }

        /**
         * showAdDetail
         *
         * @return \Illuminate\Http\Response
         */
        public function showAdDetail(Request $request) {
            //debug('>>> showAdDetail !! <<<');
            ////////////////////////////////////////////////////////////////////////
            // Prevent ERROR 500
            // FastCGI: comm with server "/fcgi-bin-php5-fpm" aborted: read failed
            ini_set('max_execution_time', 360); // Maximum execution time of each script, in seconds (I CHANGED THIS VALUE)
            ////ini_set('max_input_time', 120); // Maximum amount of time each script may spend parsing request data
            //ini_set('max_input_nesting_level', 64); // Maximum input variable nesting level
            ////ini_set('memory_limit', '256M'); // Maximum amount of memory a script may consume (128MB by default)
            //ini_set('memory_limit', '-1');
            set_time_limit (0);
            ////////////////////////////////////////////////////////////////////////

            //debug('>>> showAdDetail !! <<<');
            if ($request) {

                $viewName       = app('request')->route()->getName();
                $currentLocale = config('app.locale');
                $routeParameters = $request->route()->parameters();
                $ad_id = isset($routeParameters['ad_id']) ? $routeParameters['ad_id'] : '';

                $datasRequest = is_numeric($ad_id) ? $this->getAdDetail($ad_id) : '';
                $arrayGetAdsList = $datasRequest;
                if(!is_array($arrayGetAdsList)) {
                    $array = json_decode(json_encode($arrayGetAdsList), true);
                    if (is_array($array) && isset($array[0])) {
                        //
                        /*debug('adstypes_id');
                        //debug($array[0]['adstypes_id']);
                        //debug($array[0]['categories_ids']);
                        //debug($array[0]['subcategories_ids']);
                        //debug('manufacturers_id');
                        //debug($array[0]['manufacturers_id']);
                        //debug('models_id');
                        //debug($array[0]['models_id']);
                        //debug($array[0]['manufacturersengines_id']);
                        //debug('ad_manufacturer_url');
                        //debug($array[0]['ad_manufacturer_url']);
                        //debug($array[0]['modelsengines_id']);
                        //debug('$routeParameters');
                        //debug($routeParameters);*/
                    } else {
                        if(!empty($routeParameters['manufacturers']) && !empty($routeParameters['models'])) {
                            // manufacturers ok / models ok
                            $error_code = 'ad_not_found';
                            $routeParameters['error_code'] = $error_code;

                            $q = '';

                            $model = $this->getModel($routeParameters['models']);
                            $manufacturer = $this->getManufacturerByName($routeParameters['manufacturers']);

                            if(!array_key_exists('id', $manufacturer)) {
                                // $manufacturer['id\'] ko<br>';
                                $manufacturer = $this->getGateWayManufacturerByName(str_slug($routeParameters['manufacturers'], ' '));
                                $route = trans_route($currentLocale, 'routes.for_sale');
                            } else {
                                // $manufacturer['id'] ok
                                $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model') . '/' . $routeParameters['manufacturers'] . '/' . $routeParameters['models'];
                            }

                            if(!array_key_exists('id', $model)) {
                                // $model['id\'] ko<br>';
                                $model = $this->getGateWayModelByName(str_slug($routeParameters['models'], ' '));
                            } else {
                                // $model['id'] ok
                            }

                            if(!empty($routeParameters['adstypes'])) {
                                // adstypes<br>';
                                $adstype = $this->getAdsTypeByName($routeParameters['adstypes']);
                                if(isset($adstype['id']) && !empty($adstype['id'])) {
                                    // $adstype['id'] ok
                                    if(!array_key_exists('name', $manufacturer)) {
                                        $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                    } else {
                                        $q .= (!empty($q) ? '&' : '/?') . 'adstypes_id=' . $adstype['id'];
                                    }
                                }
                            }
                            if(!$manufacturer && !$model && isset($adstype['id']) && !empty($adstype['id'])) {
                                $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                return redirect($route, 301)->with(compact('routeParameters'));
                            }
                            if((isset($manufacturer['id']) && !empty($manufacturer['id'])) && (isset($model['id']) && !empty($model['id']))) {
                                $route .= $q;
                                return redirect($route, 301)->with(compact('routeParameters'));
                            }
                            if(!array_key_exists('name', $manufacturer) && !array_key_exists('name', $model)) {
                                $route = trans_route($currentLocale, 'routes.for_sale');
                            } else {
                                if(!array_key_exists('name', $manufacturer) && !$model) {
                                    // getGateWayManufacturerByName $manufacturer['name\'] ko<br>';
                                    $q .= (!empty($q) ? '&' : '/?') . 'query=' . str_slug($routeParameters['manufacturers'], '+');
                                } else {
                                    // getGateWayManufacturerByName $manufacturer['name'] ok
                                    //$q .= (!empty($q) ? ' ' : '/?query=') . '' . $manufacturer['name'];
                                }
                                if($model) {
                                    if (!array_key_exists('name', $model)) {
                                        // getGateWayModelByName $model['name\'] ko<br>';
                                        $q .= (!empty($q) ? '&' : '/?') . 'query=' . str_slug($routeParameters['models'], '+');
                                    } else {
                                        // getGateWayModelByName $model['name'] ok
                                        //$q .= (!empty($q) ? ' ' : '/?query=') . '' . $model['name'];
                                        if (!array_key_exists('id', $model) || (isset($model['id']) && empty($model['id']))) {
                                            // $model['id\'] ko<br>';
                                            $q .= (!empty($q) ? '&' : '/?') . 'query=' . $model['name'];
                                        } elseif (isset($model['id']) && !empty($model['id'])) {
                                            // $model['id'] ok
                                            $q .= (!empty($q) ? '&' : '/?') . 'query=' . $model['name'];
                                        }
                                    }
                                } else {
                                    $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer') . '/' . $routeParameters['manufacturers'];
                                }
                            }
                            $route .= $q;
                            return redirect($route, 301)->with(compact('routeParameters'));
                        } elseif(!empty($routeParameters['manufacturers'])) {
                            // manufacturers  ok
                            $error_code = 'ad_not_found';
                            $routeParameters['error_code'] = $error_code;

                            $q = '';

                            $manufacturer = $this->getManufacturerByName($routeParameters['manufacturers']);

                            if(!array_key_exists('id', $manufacturer)) {
                                // $manufacturer['id\'] ko<br>';
                                $manufacturer = $this->getGateWayManufacturerByName(str_slug($routeParameters['manufacturers'], ' '));
                                $route = trans_route($currentLocale, 'routes.for_sale');
                            } else {
                                // $manufacturer['id'] ok
                                $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer') . '/' . $routeParameters['manufacturers'];
                            }

                            if(!array_key_exists('name', $manufacturer)) {
                                $route = trans_route($currentLocale, 'routes.for_sale');
                            } else {
                                // getGateWayManufacturerByName $manufacturer['name'] ok
                            }

                            if(!empty($routeParameters['adstypes'])) {
                                // adstypes<br>';
                                $adstype = $this->getAdsTypeByName($routeParameters['adstypes']);
                                if(isset($adstype['id']) && !empty($adstype['id'])) {
                                    // $adstype['id'] ok
                                    if(!array_key_exists('name', $manufacturer)) {
                                        $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                    } else {
                                        $q .= (!empty($q) ? '&' : '/?') . 'adstypes_id=' . $adstype['id'];
                                    }
                                }
                            }
                            $route .= $q;
                            return redirect($route, 301)->with(compact('routeParameters'));
                        } elseif(!empty($routeParameters['manufacturersengines']) && !empty($routeParameters['modelsengines'])) {
                            // manufacturersengines ok / modelsengines  ok
                            $error_code = 'ad_not_found';
                            $routeParameters['error_code'] = $error_code;

                            $q = '';

                            $modelengine = $this->getModel($routeParameters['modelsengines']);
                            $manufacturerengine = $this->getManufacturerByName($routeParameters['manufacturersengines']);

                            if(!array_key_exists('id', $manufacturerengine)) {
                                // $manufacturerengine['id\'] ko<br>';
                                $manufacturerengine = $this->getGateWayManufacturerByName(str_slug($routeParameters['manufacturersengines'], ' '));
                                $route = trans_route($currentLocale, 'routes.for_sale');
                            } else {
                                // $manufacturerengine['id'] ok
                                $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model_engine') . '/' . $routeParameters['manufacturersengines'] . '/' . $routeParameters['modelsengines'];
                            }

                            if(!array_key_exists('id', $modelengine)) {
                                // $modelengine['id\'] ko<br>';
                                $modelengine = $this->getGateWayModelByName(str_slug($routeParameters['modelsengines'], ' '));
                            } else {
                                // $modelengine['id'] ok
                            }

                            if(!empty($routeParameters['adstypes'])) {
                                // adstypes<br>';
                                $adstype = $this->getAdsTypeByName($routeParameters['adstypes']);
                                if(isset($adstype['id']) && !empty($adstype['id'])) {
                                    // $adstype['id'] ok
                                    if(!array_key_exists('name', $manufacturerengine)) {
                                        $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                    } else {
                                        $q .= (!empty($q) ? '&' : '/?') . 'adstypes_id=' . $adstype['id'];
                                    }
                                }
                            }
                            if(!$manufacturerengine && !$modelengine && isset($adstype['id']) && !empty($adstype['id'])) {
                                $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                return redirect($route, 301)->with(compact('routeParameters'));
                            }
                            if((isset($manufacturerengine['id']) && !empty($manufacturerengine['id'])) && (isset($modelengine['id']) && !empty($modelengine['id']))) {
                                $route .= $q;
                                return redirect($route, 301)->with(compact('routeParameters'));
                            }

                            if(!array_key_exists('name', $manufacturerengine) && !array_key_exists('name', $modelengine)) {
                                $route = trans_route($currentLocale, 'routes.for_sale');
                            } else {
                                if(!array_key_exists('name', $manufacturerengine) && !$modelengine) {
                                    // getGateWayManufacturerByName $manufacturerengine['name\'] ko<br>';
                                    $q .= (!empty($q) ? '&' : '/?') . 'query=' . str_slug($routeParameters['manufacturers'], '+');
                                } else {
                                    // getGateWayManufacturerByName $manufacturerengine['name'] ok
                                    //$q .= (!empty($q) ? ' ' : '/?query=') . '' . $manufacturerengine['name'];
                                }
                                if($modelengine) {
                                    if (!array_key_exists('name', $modelengine)) {
                                        // getGateWayModelByName $modelengine['name\'] ko<br>';
                                        $q .= (!empty($q) ? '&' : '/?') . 'query=' . str_slug($routeParameters['models'], '+');
                                    } else {
                                        // getGateWayModelByName $modelengine['name'] ok
                                        //$q .= (!empty($q) ? ' ' : '/?query=') . '' . $modelengine['name'];
                                        if (!array_key_exists('id', $modelengine) || (isset($modelengine['id']) && empty($modelengine['id']))) {
                                            // $modelengine['id\'] ko<br>';
                                            $q .= (!empty($q) ? '&' : '/?') . 'query=' . $modelengine['name'];
                                        } elseif (isset($modelengine['id']) && !empty($modelengine['id'])) {
                                            // $modelengine['id'] ok
                                            $q .= (!empty($q) ? '&' : '/?') . 'query=' . $modelengine['name'];
                                        }
                                    }
                                } else {
                                    $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer_engine') . '/' . $routeParameters['manufacturers'];
                                }
                            }
                            $route .= $q;
                            return redirect($route, 301)->with(compact('routeParameters'));
                        } elseif(!empty($routeParameters['manufacturersengines'])) {
                            // manufacturersengines  ok
                            $error_code = 'ad_not_found';
                            $routeParameters['error_code'] = $error_code;

                            $q = '';

                            $manufacturerengine = $this->getManufacturerByName($routeParameters['manufacturersengines']);

                            if(!array_key_exists('id', $manufacturerengine)) {
                                // $manufacturerengine['id\'] ko<br>';
                                $manufacturerengine = $this->getGateWayManufacturerByName(str_slug($routeParameters['manufacturersengines'], ' '));
                                $route = trans_route($currentLocale, 'routes.for_sale');
                            } else {
                                // $manufacturerengine['id'] ok
                                $route = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer_engine') . '/' . $routeParameters['manufacturersengines'];
                            }

                            if(!array_key_exists('name', $manufacturerengine)) {
                                $route = trans_route($currentLocale, 'routes.for_sale');
                            } else {
                                // getGateWayManufacturerByName $manufacturerengine['name'] ok
                            }

                            if(!empty($routeParameters['adstypes'])) {
                                // adstypes<br>';
                                $adstype = $this->getAdsTypeByName($routeParameters['adstypes']);
                                if(isset($adstype['id']) && !empty($adstype['id'])) {
                                    // $adstype['id'] ok
                                    if(!array_key_exists('name', $manufacturerengine)) {
                                        $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                                    } else {
                                        $q .= (!empty($q) ? '&' : '/?') . 'adstypes_id=' . $adstype['id'];
                                    }
                                }
                            }
                            $route .= $q;
                            return redirect($route, 301)->with(compact('routeParameters'));
                        } elseif(!empty($routeParameters['adstypes']) && !empty($routeParameters['categories'])) {
                            $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'] . '?query=' . str_slug(str_replace('/',' ', $routeParameters['categories']));
                            $error_code = 'ad_not_found';
                            $routeParameters['error_code'] = $error_code;
                            return redirect($route, 301)->with(compact('routeParameters'));
                        } elseif(!empty($routeParameters['adstypes'])) {
                            $route = trans_route($currentLocale, 'routes.for_sale') . '/' . $routeParameters['adstypes'];
                            $error_code = 'ad_not_found';
                            $routeParameters['error_code'] = $error_code;
                            return redirect($route, 301)->with(compact('routeParameters'));
                        } else {
                            $route = trans_route($currentLocale, 'routes.ad_not_found');
                            $error_code = 'ad_not_found';
                            $routeParameters['error_code'] = $error_code;
                            return redirect($route, 301)->with(compact('routeParameters'));
                        }
                    }
                }

                $ci_email = $ci_username = $user_id = $ci_firstname = $ci_last_name = $ci_countries_id = $ci_phone = '';

                $userInfos = [];
                if(Auth::check()) {
                    $ci_email = Auth::user()->email;
                    $userInfos['ci_email'] = $ci_email;
                    $user_type = Auth::user()->type;
                    $ci_username = Auth::user()->username;
                    $user_type = Auth::user()->type;
                    //$userInfos['username'] = $ci_username;

                    $user_id = Auth::user()->id;
                    //$userInfos['user_id'] = $user_id;

                    /*$customerCaracts = CustomersCaracts::
                    //select('firstname', 'name', 'country_id', 'phone_1', 'emails')
                    select(DB::raw('firstname as ci_firstname, name as ci_last_name, country_id as ci_countries_id, phone_1 as ci_phone'))
                        ->where('user_id', '=', $user_id)
                        ->where('emails', $ci_email)
                        ->get();
                    $result = json_decode(json_encode($customerCaracts), true);*/

                    switch($user_type) {
                        case 'private':
                            $usercaracts = PrivatesCaracts::select(DB::raw('firstname as ci_firstname, name as ci_last_name, country_id as ci_countries_id, phone_1 as ci_phone'))
                                ->where('user_id', '=', $user_id)
                                ->where('emails', $ci_email)
                                ->get();
                            break;
                        case 'dealer':
                            $usercaracts = DealersCaracts::select(DB::raw('firstname as ci_firstname, name as ci_last_name, country_id as ci_countries_id, phone_1 as ci_phone'))
                                ->where('user_id', '=', $user_id)
                                ->where('emails', $ci_email)
                                ->get();
                            break;
                        case 'commercial':
                            $usercaracts = CommercialsCaracts::select(DB::raw('firstname as ci_firstname, name as ci_last_name, country_id as ci_countries_id, phone_1 as ci_phone'))
                                ->where('user_id', '=', $user_id)
                                ->where('emails', $ci_email)
                                ->get();
                            break;
                        case 'customer':
                            $usercaracts = CustomersCaracts::select(DB::raw('firstname as ci_firstname, name as ci_last_name, country_id as ci_countries_id, phone_1 as ci_phone'))
                                ->where('user_id', '=', $user_id)
                                ->where('emails', $ci_email)
                                ->get();
                            break;
                    }
                    $result = json_decode(json_encode($usercaracts), true);

                    $arrayCustomerCaracts = [];
                    if(!empty($result)) {
                        $arrayCustomerCaracts = $result[0];
                    }
                    //$arrayCustomerCaracts = json_decode(json_encode($customerCaracts), true)[0];
                    if(array_key_exists('ci_firstname', $arrayCustomerCaracts) && !empty($arrayCustomerCaracts["ci_firstname"])) {
                        $ci_firstname = $arrayCustomerCaracts["ci_firstname"];
                        $userInfos['ci_firstname'] = $ci_firstname;
                    }
                    if(array_key_exists('ci_last_name', $arrayCustomerCaracts) && !empty($arrayCustomerCaracts["ci_last_name"])) {
                        $ci_last_name = $arrayCustomerCaracts["ci_last_name"];
                        $userInfos['ci_last_name'] = $ci_last_name;
                    }
                    if(array_key_exists('ci_countries_id', $arrayCustomerCaracts) && !empty($arrayCustomerCaracts["ci_countries_id"])) {
                        $ci_countries_id = $arrayCustomerCaracts["ci_countries_id"];
                        $userInfos['ci_countries_id'] = $ci_countries_id;
                    }
                    if(array_key_exists('ci_phone', $arrayCustomerCaracts) && !empty($arrayCustomerCaracts["ci_phone"])) {
                        $ci_phone = $arrayCustomerCaracts["ci_phone"];
                        $userInfos['ci_phone'] = $ci_phone;
                    }
                }

                $arrayGetAdsList['results_view'] = 'list';
                $arrayGetAdsList['max'] = 9;
                $arrayGetAdsList['sort_by'] = 'updated_at-desc';
                $ads_list = $this->getAdsList($arrayGetAdsList);
                $countries = $recovery_countries =  $this->getCountries();
                //debug($ads_list);
                return view($viewName, compact(
                    'ci_email', 'ci_username', 'user_id', 'ci_firstname', 'ci_last_name', 'ci_countries_id', 'ci_phone',

                    //'ad_detail',
                    'routeParameters',
                    'ads_list',
                    'countries',
                    'datasRequest'
                ))->with(['controller'=>$this]);
            } else {
                return view('for_sale');
            }
        }

    }
