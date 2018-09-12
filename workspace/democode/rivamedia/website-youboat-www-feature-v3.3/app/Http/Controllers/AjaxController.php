<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    use App\Http\Requests;

    //use App\User;
    //use App\Categories;
    //use App\Subcategories;
    //use App\CountryContracts;
    //use App\Models;
    //use App\ModelsEngines;

    class AjaxController extends SearchController
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            //$this->middleware(['clearcache']);
        }

        /**
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            return Response::json(null);
        }

        /**
         * Handle an ajax email request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxEmail(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $dealer_id = $request->input('dealer_id');
            $email = User::where('id', '=', $dealer_id)->select('email')->get();
            //$emails = DealersCaracts::where('dealer_id', '=', $dealer_id)->select('emails')->get();
            return response()->json($email);
        }

        /**
         * Handle an ajax email request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxDealerEmails(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $dealer_id = $request->input('dealer_id');
            $emails = DealersCaracts::where('dealer_id', '=', $dealer_id)->select('emails')->get();
            return response()->json($emails);
        }

        /**
         * Handle an ajax countries request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxCountries(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $dealer_id = $request->input('dealer_id');
            $countries = Countries::orderBy('name', 'asc')->lists("name", "id");
            return $countries;
        }

        /**
         * Handle an ajax country request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxCountry(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $name = $request->input('name');
            $countries = Countries::orderBy('name', 'asc')->lists("name", "id");
            return $countries;
        }
        /**
         * Handle an ajax country contracts request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxCountryContracts(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $dealer_id = $request->input('dealer_id');
            //$country_contracts = CountryContracts::where('id', '=', $dealer_id)->orderBy('reference', 'asc')->get();
            //return response()->json($country_contracts);
            $country_contracts = CountryContracts::where('id', '=', $dealer_id)->orderBy('reference', 'asc')->lists("reference", "id");
            return $country_contracts;
        }

        /**
         * Handle an ajax adstypes request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxAdsTypes(Request $request)
        {
            $currentLocale = $request->input('locale');
            $list = $request->input('adstypes_rewrite_url')?:false;
            app()->setLocale($currentLocale);
            $adstypes = $this->getAdsTypes('active', $list, [11]);
            return $adstypes;
        }

        /**
         * Handle an ajax adstype request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxAdsType(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $name = $request->input('name');
            $adstypes = $this->getAdsType($name, true, true);
            return $adstypes;
        }

        /**
         * Handle an ajax categories request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxCategories(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $adstypes_id = $request->input('adstypes_id');
            $categories = $this->getCategories($adstypes_id);
            return $categories;
        }

        /**
         * Handle an ajax category request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxCategory(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $name = $request->input('name');
            $categories = $this->getCategory($name, true, true);
            return $categories;
        }

        /**
         * Handle an ajax sub-categories request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxSubCategories(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $category_id = $request->input('category_id');
            $subcategories = $this->getSubcategories($category_id);
            return $subcategories;
        }

        /**
         * Handle an ajax sub-category request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxSubCategory(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $name = $request->input('name');
            $subcategories = $this->getSubcategory($name, true, true);
            return $subcategories;
        }

        /**
         * Handle an ajax manufacturers request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxManufacturers(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $list = $request->input('manufacturers_rewrite_url')?:false;
            $adstypes_id = $request->input('adstypes_id');
            $category_id = $request->input('category_id');
            $subcategory_id = $request->input('subcategory_id');
            $manufacturers = $this->getManufacturers($list, $adstypes_id, $category_id, $subcategory_id);
            $array = json_decode(json_encode($manufacturers), true);
            asort($array);
            $manufacturers = $array;
            return $manufacturers;
        }

        /**
         * Handle an ajax manufacturer request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxManufacturer(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $name = $request->input('term') ? $request->input('term') : $request->input('name') ? $request->input('name') : '';
            $manufacturers = $this->getManufacturer($name, true, true);

            return $manufacturers;
        }

        /**
         * Handle an ajax models request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxModels(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $models = [];
            $manufacturers_id = $request->input('manufacturers_id');
            $array = $this->getModels($manufacturers_id, false, true);
            $array = json_decode(json_encode($array), true);

            foreach($array as $key => $val) {
                //$array[$key] = trim($val);
                $models[] = SearchController::getModel($val['id']);
                //$array[$models['id']] = trim($models['name']) . ' (' . $val['count'] .')';
                //$array[$models['id']] = trim($models['name']);
            }

            //asort($array);

            $models = $array;
            return $models;
        }

        /**
         * Handle an ajax model request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxModel(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $name = $request->input('term') ? $request->input('term') : $request->input('name') ? $request->input('name') : '';
            $models = $this->getModel($name, true, true);
            return $models;
        }

        /**
         * Handle an ajax manufacturers engines request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxManufacturersEngines(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $list = $request->input('manufacturersengines_rewrite_url')?:false;
            $manufacturersengines = $this->getManufacturersEngines($list);
            $array = json_decode(json_encode($manufacturersengines), true);
            asort($array);
            $manufacturersengines = $array;
            return $manufacturersengines;
        }

        /**
         * Handle an ajax manufacturer engine request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxManufacturerEngine(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $name = $request->input('name');
            $manufacturersengines = $this->getManufacturerEngine($name, true, true);
            return $manufacturersengines;
        }

       /**
         * Handle an ajax modeles engines request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxModelsEngines(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $manufacturersengines_id = $request->input('manufacturersengines_id');
            $modelsengines = $this->getModelsEngines($manufacturersengines_id);
            $array = json_decode(json_encode($modelsengines), true);
            asort($array);
            $modelsengines = $array;
            return $modelsengines;
        }

        /**
         * Handle an ajax adstype detail request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxAdsTypeDetail(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $adstypes_id = $request->input('adstypes_id');
            $adstype = $this->getAdsType($adstypes_id);
            return $adstype;
        }

        /**
         * Handle an ajax categories request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxGateWayCategories(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $adstypes_id = $request->input('adstypes_id');
            //$categories = $this->getGateWayAdsCategories($adstypes_id);
            $array = SearchController::getGateWayAdsCategories($adstypes_id);
            foreach($array as $key => $val) {
                $category = SearchController::getCategory($val[0]);
                //$array[$category['id']] = trans('categories.' . trim($category['rewrite_url'])) . ' (' . $val['count'] .')';
                $array[$category['id']] = trans('categories.' . trim($category['rewrite_url']));
            }
            $categories = $array;
            return $categories;
        }


        /**
         * Handle an ajax categories request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxGateWaySubcategories(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $category_id = $request->input('category_id');
            //$subcategories = $this->getGateWayAdsSubcategories($category_id);
            $array = SearchController::getGateWayAdsSubcategories($category_id);
            foreach($array as $key => $val) {
                $subcategory = SearchController::getSubcategory($val[0]);
                //$array[$subcategory['id']] = trans('subcategories.' . trim($subcategory['rewrite_url'])) . ' (' . $val['count'] .')';
                $array[$subcategory['id']] = trans('subcategories.' . trim($subcategory['rewrite_url']));
            }
            $subcategories = $array;
            return $subcategories;
        }

        /**
         * Handle an ajax manufacturers gateway request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxGateWayManufacturers (Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $manufacturers = [];
            $adstypes_id = $request->input('adstypes_id');
            $category_id = $request->input('category_id');
            $subcategory_id = $request->input('subcategory_id');
            $array = SearchController::getGateWayAdsManufacturers($adstypes_id, $category_id, $subcategory_id);
            foreach($array as $key => $val) {
                $manufacturers[] = SearchController::getManufacturer($val[0]);
                //$array[$manufacturers['id']] = trim($manufacturers['name']) . ' (' . $val['count'] .')';
                //$array[$manufacturers['id']] = trim($manufacturers['name']);
            }
            //$manufacturers = $array;

            return $manufacturers;
        }

        /**
         * Handle an ajax gateway manufacturer request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxGateWayManufacturer(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);

            $adstypes_id = $request->input('adstypes_id');
            $category_id = $request->input('category_id');
            $subcategory_id = $request->input('subcategory_id');
            $name = $request->input('term') ? $request->input('term') : $request->input('name') ? $request->input('name') : '';

            // search only by name
            //$manufacturer = SearchController::getGateWayManufacturerByName($name, true);

            // search affiné
            $manufacturer = SearchController::getGateWayAdsManufacturersByName($name, true, $adstypes_id, $category_id, $subcategory_id);

            return $manufacturer;
        }

        /**
         * Handle an ajax modeles request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxGateWayModels(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $models = [];
            $manufacturers_id = $request->input('manufacturers_id');
            $array = SearchController::getGateWayAdsModels($manufacturers_id);
            foreach($array as $key => $val) {
                $models[] = SearchController::getModel($val[0]);
                //$array[$models['id']] = trim($models['name']) . ' (' . $val['count'] .')';
                //$array[$models['id']] = trim($models['name']);
            }
            //$models = $array;

            return $models;
        }

        /**
         * Handle an ajax manufacturers engines request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxGateWayManufacturersEngines(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $manufacturersengines = [];
            $adstypes_id = $request->input('adstypes_id');
            $category_id = $request->input('category_id');
            $subcategory_id = $request->input('subcategory_id');
            $array = SearchController::getGateWayAdsManufacturersEngines($adstypes_id, $category_id, $subcategory_id);
            foreach($array as $key => $val) {
                $manufacturersengines[] = SearchController::getManufacturerEngine($val[0]);
                //$array[$manufacturersengines['id']] = trim($manufacturersengines['name']) . ' (' . $val['count'] .')';
                //$array[$manufacturersengines['id']] = trim($manufacturersengines['name']);
            }
            //$manufacturersengines = $array;

            return $manufacturersengines;
        }

        /**
         * Handle an ajax gateway manufacturer engine request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxGateWayManufacturerEngine(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);

            $adstypes_id = $request->input('adstypes_id');
            $category_id = $request->input('category_id');
            $subcategory_id = $request->input('subcategory_id');
            $name = $request->input('term') ? $request->input('term') : $request->input('name') ? $request->input('name') : '';

            // search only by name
            //$manufacturerengine = SearchController::getGateWayManufacturerEngineByName($name, true);

            // search affiné
            $manufacturerengine = SearchController::getGateWayAdsManufacturersByName($name, true, $adstypes_id, $category_id, $subcategory_id);

            return $manufacturerengine;
        }


        /**
         * Handle an ajax modeles engines request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function AjaxGateWayModelsEngines(Request $request)
        {
            $currentLocale = $request->input('locale');
            app()->setLocale($currentLocale);
            $modelsengines = [];
            $manufacturersengines_id = $request->input('manufacturers_id');
            //$array = SearchController::getGateWayAdsModelsEngines($adstypes_id, $category_id, $subcategory_id, $manufacturersengines_id);
            $array = SearchController::getGateWayAdsModelsEngines($manufacturersengines_id);
            foreach($array as $key => $val) {
                $modelsengines[] = SearchController::getModelEngine($val[0]);
                //$array[$modelsengines['id']] = trim($modelsengines['name']) . ' (' . $val['count'] .')';
                //$array[$modelsengines['id']] = trim($modelsengines['name']);
            }
            //$modelsengines = $array;

            return $modelsengines;
        }
    }
