<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;
	use App\Http\Requests;
    use App\Http\Requests\SearchNotificationRequest;
    use App\Http\Controllers\Controller;

	use DB;
	use Schema;
    use App\CountryContracts;
	use App\Countries;
	use App\AdsTypes;
	use App\Categories;
	use App\Subcategories;
	use App\Manufacturers;
	use App\ManufacturersEngines;
	use App\Models;
	use App\ModelsEngines;
    use App\DealersCaracts;
    use App\PrivatesCaracts;
    use App\CommercialsCaracts;
    use App\CustomersCaracts;

    use App\SearchNotification;
    use App\ProspectiveCustomers;
    use Mail;
    use Illuminate\Support\Facades\Validator;

    use App\Gateway; // @TODO : temporaire

	use Input;
	use Html;
    use Lang;

    class SearchController extends Controller {
		/*
		 * Search Controller
		 */
        public $view_name;
        public $statusActive;

        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            //$this->middleware(['clearcache']);
            $view_name = session()->get('view_name');
            $statusActive = true;
            if(preg_match('/adscaracts/', $view_name) ||preg_match('/bodcaracts/', $view_name)) {
                $statusActive = false;
            }
        }

        /*public function index(Request $request) {
            //return view('search', compact('request'));
            $viewName       = app('request')->route()->getName();
            return view($viewName, compact('request'));
        }*/

        /**
         * setSomethingById
         * @param  char $_somethingTable
         * @param  int $_somethingId
         * @param  char $_somethingName
         * @param  char $_somethingValue
         * @return Array
         */
        public static function setSomethingById ($_somethingTable = '', $_somethingId = null, $_somethingName = '', $_somethingValue = null) {
            $something = [];
            if ($_somethingId && isset($_somethingTable) && !empty($_somethingTable)) {
                $result = DB::table($_somethingTable)->where('id', '=', $_somethingId)->update([$_somethingName => $_somethingValue]);
                $something = json_decode(json_encode($result), true);
            }
            return $something;
        }

        /**
         * getSomethingById
         * @param  char $_somethingTable
         * @param  int $_somethingId
         * @param  char $_somethingName
         * @return Array
         */
        public static function getSomethingById ($_somethingTable = '', $_somethingId = null, $_somethingName = '*') {
            $something = [];
            if ($_somethingId && isset($_somethingTable) && !empty($_somethingTable)) {
                $result = DB::table($_somethingTable)->where('id', '=', $_somethingId)->select($_somethingName)->get();
                $something = json_decode(json_encode($result), true);
            }
            return $something;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // SellTypes
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /**
         * getAdsSellTypes
         * @return Array
         */
        public static function getAdsSellTypes () {
            $currentLocale  = app()->getLocale();

            $selltypes      = getEnumValues('adscaracts', 'sell_type');
            if (config('app.fallback_locale') != $currentLocale) {
                $selltypes = array_map(function ($v) {
                    return Lang::get('selltype.' . $v);
                }, $selltypes);
            }
            return $selltypes;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Countries
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /**
         * getCountries
         * @return Array
         */
        public static function getCountries () {
            $countries = [];
            $countries   = Countries::orderBy('name', 'asc')->lists("name", "id");
            return $countries;
        }

        /**
         * getCountry
         * @param char or int $_countryCode
         * @return Array
         */
        public static function getCountry ($_countryCode = '', $byName = false, $like = false) {
            $country = [];
            $result = [];
            $case = $byName ? "name" : "code";
            if($_countryCode) {
                if(is_numeric($_countryCode)) {
                    $result = Countries::where('id', '=', $_countryCode)->select('code','name','id')->get();
                } elseif(is_string($_countryCode)) {
                    if ($like) {
                        $result = Countries::where($case, 'LIKE', "%$_countryCode%")->select('code','name','id')->orderBy('name', 'ASC')->get();
                    } else {
                        $result = Countries::where($case, '=', $_countryCode)->select('code','name','id')->get();
                    }
                }

                foreach ($result as $row){
                    $country['code']        = $row->code;
                    $country['name']        = $row->name;
                    $country['id']          = $row->id;
                }
            }
            return $country;
        }

        /**
         * getCountryByName
         * @return Array
         */
        public static function getCountryByName ($_countryName = '') {
            $country = [];
            if($_countryName) {
                $result = Countries::where('name', '=', $_countryName)->select('name','id')->get();

                foreach ($result as $row){
                    $country['name']        = $row->name;
                    $country['id']          = $row->id;
                }
            }
            return $country;
        }

        /**
         * getCountryById
         * @return Array
         */
        public static function getCountryById ($_countryId = '', $byName = true) {
            $country = [];
            $case = $byName ? 'name' : 'code';
            if($_countryId) {
                $result = Countries::where('id', '=', $_countryId)->select($case,'id')->get();

                foreach ($result as $row){
                    if($byName) {
                        $country['name']        = $row->name;
                    } else {
                        $country['code']        = $row->code;
                    }
                    $country['id']          = $row->id;
                }
            }
            return $country;
        }

        /**
         * getCountryLocaleCode
         * @param char or int $_countryCode
         * @return Array
         */
        public static function getCountryLocaleCode ($_countryCode = '') {
            $countrylocale = [];
            $locales = [];
            if($_countryCode) {
                if(is_numeric($_countryCode)) {
                    $result = Countries::where('id', '=', $_countryCode)->select('locales')->get();
                } elseif(is_string($_countryCode)) {
                    $result = Countries::where('code', '=', $_countryCode)->select('locales')->get();
                }
                if(isset($result)) {
                    foreach ($result as $row){
                        list($countrylocale) = explode(',', $row->locales);
                    }
                    $locales[] = str_replace('-', '_', $countrylocale) .'.utf8';
                    $locales[] = str_replace('-', '_', $countrylocale);
                    if(is_string($_countryCode)) {
                        $locales[] = strtolower($_countryCode);
                    }
                }
            }
            return $locales;
        }

        /**
         * getCountryLocaleFull
         * @param char or int $_countryCode
         * @return Array
         */
        public static function getCountryLocaleFull ($_countryCode = '') {
            $countrylocalefull = [];
            if($_countryCode) {
                if(is_numeric($_countryCode)) {
                    $result = Countries::where('id', '=', $_countryCode)->select('code','locales','currency', 'currency_name')->get();
                } elseif(is_string($_countryCode)) {
                    $result = Countries::where('code', '=', $_countryCode)->select('code','locales','currency', 'currency_name')->get();
                }
                if(isset($result)) {
                    foreach ($result as $row){
                        $countrylocalefull['code']          = $row->code;
                        $countrylocalefull['locales']       = $row->locales;
                        $countrylocalefull['currency']      = $row->currency;
                        $countrylocalefull['currency_name'] = $row->currency_name;
                    }
                }
            }
            return $countrylocalefull;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // AdsTypes
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getAdsTypes
         * @return Array
         */
        public static function getAdsTypes ($_status = 'active', $list = false, $excludeIds = []) {
            //$adstypes     = AdsTypes::all();
            $currentLocale      = app()->getLocale();
            $adstypes = [];
            $WhereRaw = '';
            if (config('app.fallback_locale') != $currentLocale) {
                if('active' === $_status) {
                    $WhereRaw .= 'status = "active" ';
                    if(count($excludeIds)>0) {
                        foreach($excludeIds as $id) {
                            $WhereRaw .= 'AND id <> "' . $id .'" ';
                        }
                    }
                    if (!is_bool($list) && $list) {
                        $adstypes   = AdsTypes::whereRaw($WhereRaw)
                            ->where('rewrite_url', 'LIKE', "%$list%")
                            ->orderBy('position', 'asc')
                            ->lists("rewrite_url", "id");
                    } else {
                        $adstypes   = AdsTypes::whereRaw($WhereRaw)
                            ->orderBy('position', 'asc')
                            ->lists("rewrite_url", "id");
                    }
                } else {
                    $adstypes   = AdsTypes::orderBy('position', 'asc')->lists("rewrite_url", "id");
                }
                if(is_bool($list) && !$list) {
                    $array          = json_decode(json_encode($adstypes), true);
                    $adstypes       = array_map(function ($v) {
                        if (Lang::has('adstypes.' . $v)) {
                            return Lang::get('adstypes.' . $v);
                        } else {
                            return $v;
                        }
                    }, $array);
                }
            } else {
                $case = $list ? "rewrite_url" : "name";
                if('active' === $_status) {
                    $WhereRaw .= 'status = "active" ';
                    if(count($excludeIds)>0) {
                        foreach($excludeIds as $id) {
                            $WhereRaw .= 'AND id <> "' . $id .'" ';
                        }
                    }
                    if (!is_bool($list) && $list) {
                        $adstypes   = AdsTypes::whereRaw($WhereRaw)
                            ->where('rewrite_url', 'LIKE', "%$list%")
                            ->orderBy('position', 'asc')
                            ->lists($case, "id");
                    } else {
                        $adstypes = AdsTypes::whereRaw($WhereRaw)
                            ->orderBy('position', 'asc')
                            ->lists($case, "id");
                    }
                } else {
                    $adstypes = AdsTypes::orderBy('position', 'asc')->lists($case, "id");
                }
            }
            return $adstypes;
        }

        /**
         * getAdsType
         *
         * @param char or int $_adsType
         * @return Array
         */
        public static function getAdsType ($_adsType, $byName = false, $like = false) {
            $adstype = [];
            $case = $byName ? "name" : "rewrite_url";
            if ($_adsType) {
                $currentLocale      = app()->getLocale();
                if(is_numeric($_adsType)) {
                    $result = AdsTypes::where('id', '=', $_adsType)->select('name','id','rewrite_url','status')->get();
                } elseif(is_string($_adsType)) {
                    $fallback_locale    = config('app.fallback_locale');
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_adsType       = str_replace('_', '-', array_search($_adsType, $array));
                    }
                    if ($like) {
                        $result = AdsTypes::where($case, 'LIKE', "%$_adsType%")->select('name','id','rewrite_url','status')->orderBy('name', 'ASC')->get();
                        return $result;
                    } else {
                        $result = AdsTypes::where($case, '=', $_adsType)->select('name','id','rewrite_url','status')->orderBy('name', 'ASC')->get();
                    }
                }
                foreach ($result as $row){
                    $adstype['name']        = $row->name;
                    if (config('app.fallback_locale') != $currentLocale) {
                        //$adstype['name']  = trans('adstypes.'. $row->name);
                        $adstype['name']    = trans('adstypes.' . $row->rewrite_url);
                    }

                    $adstype['id']          = $row->id;
                    $adstype['rewrite_url'] = $row->rewrite_url;
                    $adstype['status']      = $row->status;
                }
            }
            return $adstype;
        }

            /**
             * getAdsTypeByName
             *
             * @param  char $_adsTypeName
             * @return Array
             */
            public static function getAdsTypeByName ($_adsTypeName) {
                //debug('>>> getAdsTypeByName <<<');
                $adstype = [];
                if ($_adsTypeName) {
                    $currentLocale      = app()->getLocale();
                    $fallback_locale    = config('app.fallback_locale');
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_adsTypeName   = str_replace('_', '-', array_search($_adsTypeName, $array));
                    }
                    $result = AdsTypes::where('rewrite_url', '=', $_adsTypeName)->select('name','id','rewrite_url','status')->get();
                    foreach ($result as $row){
                        $adstype['name']        = $row->name;
                        if (config('app.fallback_locale') != $currentLocale) {
                            //$adstype['name']  = trans('adstypes.'. $row->name);
                            $adstype['name']    = trans('adstypes.' . $row->rewrite_url);
                        }

                        $adstype['id']          = $row->id;
                        $adstype['rewrite_url'] = $row->rewrite_url;
                        $adstype['status']      = $row->status;
                    }
                }
                return $adstype;
            }

            /**
             * getAdsTypeById
             *
             * @param  int $_adsTypeId
             * @return Array
             */
            public static function getAdsTypeById ($_adsTypeId) {
                //debug('>>> getAdsTypeById <<<');
                $adstype = [];
                if ($_adsTypeId) {
                    $currentLocale      = app()->getLocale();
                    //$adstype = AdsTypes::find($_adsTypeId);
                    $result = AdsTypes::where('id', '=', $_adsTypeId)->select('name','id','rewrite_url','status')->get();
                    foreach ($result as $row){
                        $adstype['name']        = $row->name;
                        if (config('app.fallback_locale') != $currentLocale) {
                            //$adstype['name']  = trans('adstypes.'. $row->name);
                            $adstype['name']    = trans('adstypes.' . $row->rewrite_url);
                        }
                        $adstype['id']          = $row->id;
                        $adstype['rewrite_url'] = $row->rewrite_url;
                        $adstype['status']      = $row->status;
                    }
                }
                return $adstype;
            }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Categories
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /**
         * getCategories
         *
         * @param int $_adsTypeId
         * @return Array
         */
        public static function getCategories ($_adsTypeId = '', $list = false) {
            $currentLocale      = app()->getLocale();
            $fallback_locale    = config('app.fallback_locale');

            $categories         = [];
            if (config('app.fallback_locale') != $currentLocale) {
                if($_adsTypeId) {
                    $categories = Categories::where('adstypes_id', $_adsTypeId)->orderBy('rewrite_url', 'asc')->lists('rewrite_url','id');
                } else {
                    $categories = Categories::orderBy('rewrite_url', 'asc')->lists("rewrite_url", "id");
                }
                if(!$list) {
                    $array = json_decode(json_encode($categories), true);
                    $categories = array_map(function ($v) {
                        if (Lang::has('categories.' . $v)) {
                            return Lang::get('categories.' . $v);
                        } else {
                            return $v;
                        }
                    }, $array);
                }
            } else {
                $case = $list ? "rewrite_url" : "name";
                if($_adsTypeId) {
                    $categories = Categories::where('adstypes_id', $_adsTypeId)->orderBy($case, 'asc')->lists($case, "id");
                } else {
                    $categories = Categories::orderBy($case, 'asc')->lists($case, "id");
                }
            }

            return $categories;
        }

        /**
         * getCategory
         *
         * @param char or int $_category
         * @return Array
         */
        public static function getCategory ($_category, $byName = false, $like = false) {
            $category = [];
            $case = $byName ? "name" : "rewrite_url";
            if ($_category) {
                $currentLocale      = app()->getLocale();
                if(is_numeric($_category)) {
                    $result = Categories::where('id', '=', $_category)->select('name','id','rewrite_url','adstypes_id')->get();
                } elseif(is_string($_category)) {
                    $fallback_locale    = config('app.fallback_locale');
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_category      = str_replace('_', '-', array_search($_category, $array));
                    }
                    if ($like) {
                        $result = Categories::where($case, 'LIKE', "%$_category%")->select('name','id','rewrite_url','adstypes_id')->orderBy('name', 'ASC')->get();
                        return $result;
                    } else {
                        $result = Categories::where($case, '=', $_category)->select('name','id','rewrite_url','adstypes_id')->get();
                    }
                }
                foreach ($result as $row){
                    $category['name']           = $row->name;
                    if (config('app.fallback_locale') != $currentLocale) {
                        //$category['name']  = trans('categories.'. $row->name);
                        $category['name']    = trans('categories.' . $row->rewrite_url);
                    }
                    $category['id']             = $row->id;
                    $category['rewrite_url']    = $row->rewrite_url;
                    $category['adstypes_id']    = $row->adstypes_id;
                }
            }
            return $category;
        }

            /**
             * getCategoryByName
             *
             * @param  char $_categoryName
             * @return Array
             */
            public static function getCategoryByName ($_categoryName) {
                $category = [];
                if ($_categoryName) {
                    $currentLocale      = app()->getLocale();
                    $fallback_locale    = config('app.fallback_locale');
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_categoryName  = str_replace('_', '-', array_search($_categoryName, $array));
                    }
                    $result = Categories::where('rewrite_url', '=', $_categoryName)->select('name','id','rewrite_url','adstypes_id')->get();
                    foreach ($result as $row){
                        $category['name']           = $row->name;
                        if (config('app.fallback_locale') != $currentLocale) {
                            //$category['name']  = trans('categories.'. $row->name);
                            $category['name']    = trans('categories.' . $row->rewrite_url);
                        }
                        $category['id']             = $row->id;
                        $category['rewrite_url']    = $row->rewrite_url;
                        $category['adstypes_id']    = $row->adstypes_id;
                    }
                }
                return $category;
            }

            /**
             * getCategoryById
             *
             * @param  int $_categoryId
             * @return Array
             */
            public static function getCategoryById ($_categoryId) {
                $category = [];
                if ($_categoryId) {
                    //$category = Categories::find($categoryId);
                    $result = Categories::where('id', '=', $_categoryId)->select('name','id','rewrite_url','adstypes_id')->get();
                    foreach ($result as $row){
                        $category['name']           = $row->name;
                        $category['id']             = $row->id;
                        $category['rewrite_url']    = $row->rewrite_url;
                        $category['adstypes_id']    = $row->adstypes_id;
                    }
                }
                return $category;
            }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Subcategories
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /**
         * getSubcategories
         *
         * @param int $_categoryId
         * @return Array
         */
        public static function getSubcategories ($_categoryId = '', $list = false) {
            $currentLocale      = app()->getLocale();
            //$fallback_locale    = config('app.fallback_locale');
            $subcategories      = [];
            if (config('app.fallback_locale') != $currentLocale) {
                if($_categoryId) {
                    $subcategories  = Subcategories::where('categories_id', $_categoryId)->orderBy('rewrite_url', 'asc')->lists("rewrite_url", "id");
                } else {
                    $subcategories  = Subcategories::orderBy('rewrite_url', 'asc')->lists("rewrite_url", "id");
                }
                if(!$list) {
                    $array = json_decode(json_encode($subcategories), true);
                    $subcategories = array_map(function ($v) {
                        if (Lang::has('subcategories.' . $v)) {
                            return Lang::get('subcategories.' . $v);
                        } else {
                            return $v;
                        }
                    }, $array);
                }
            } else {
                $case = $list ? "rewrite_url" : "name";
                if($_categoryId) {
                    $subcategories = Subcategories::where('categories_id', $_categoryId)->orderBy($case, 'asc')->lists($case, "id");
                } else {
                    $subcategories = Subcategories::orderBy($case, 'asc')->lists($case, "id");
                }
            }
            return $subcategories;
        }

        /**
         * getSubcategory
         *
         * @param char or int $_subcategory
         * @return Array
         */
        public static function getSubcategory ($_subcategory, $byName = false, $like = false) {
            $subcategory = [];
            $case = $byName ? "name" : "rewrite_url";
            if ($_subcategory) {
                $currentLocale      = app()->getLocale();
                if(is_numeric($_subcategory)) {
                    $result = Subcategories::where('id', '=', $_subcategory)->select('name','id','rewrite_url','categories_id')->orderBy('name', 'ASC')->get();
                } elseif(is_string($_subcategory)) {
                    //$fallback_locale    = config('app.fallback_locale');
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_subcategory   = str_replace('_', '-', array_search($_subcategory, $array));
                    }
                    if ($like) {
                        $result = Subcategories::where($case, 'LIKE', "%$_subcategory%")->select('name','id','rewrite_url','categories_id')->orderBy('name', 'ASC')->get();
                        return $result;
                    } else {
                        $result = Subcategories::where($case, '=', $_subcategory)->select('name','id','rewrite_url','categories_id')->orderBy('name', 'ASC')->get();
                    }
                }
                foreach ($result as $row){
                    $subcategory['name']            = $row->name;
                    if (config('app.fallback_locale') != $currentLocale) {
                        //$subcategory['name']  = trans('subcategories.'. $row->name);
                        $subcategory['name']    = trans('subcategories.' . $row->rewrite_url);
                    }
                    $subcategory['id']              = $row->id;
                    $subcategory['rewrite_url']     = $row->rewrite_url;
                    $subcategory['categories_id']   = $row->categories_id;
                }
            }
            return $subcategory;
        }

            /**
             * getSubcategoryByName
             *
             * @param  char $_subcategoryName
             * @return Array
             */
            public static function getSubcategoryByName ($_subcategoryName) {
                $subcategory = [];
                if ($_subcategoryName) {
                    $currentLocale      = app()->getLocale();
                    //$fallback_locale    = config('app.fallback_locale');
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_subcategoryName   = str_replace('_', '-', array_search($_subcategoryName, $array));
                    }
                    $result = Subcategories::where('rewrite_url', '=', $_subcategoryName)->select('name','id','rewrite_url','categories_id')->get();
                    foreach ($result as $row){
                        $subcategory['name']            = $row->name;
                        if (config('app.fallback_locale') != $currentLocale) {
                            //$subcategory['name']  = trans('subcategories.'. $row->name);
                            $subcategory['name']    = trans('subcategories.' . $row->rewrite_url);
                        }
                        $subcategory['id']              = $row->id;
                        $subcategory['rewrite_url']     = $row->rewrite_url;
                        $subcategory['categories_id']   = $row->categories_id;
                    }
                }
                return $subcategory;
            }

            /**
             * getSubcategoryById
             *
             * @param  int $_subcategoryId
             * @return Array
             */
            public static function getSubcategoryById ($_subcategoryId) {
                $subcategory = [];
                if ($_subcategoryId) {
                    //$category = Categories::find($categoryId);
                    $result = Subcategories::where('id', '=', $_subcategoryId)->select('name','id','rewrite_url','categories_id')->get();
                    foreach ($result as $row){
                        $subcategory['name']            = $row->name;
                        $subcategory['id']              = $row->id;
                        $subcategory['rewrite_url']     = $row->rewrite_url;
                        $subcategory['categories_id']   = $row->categories_id;
                    }
                }
                return $subcategory;
            }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Manufacturers
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getManufacturers
         *
         * @return Array
         */
        public static function getManufacturers ($list = false) {
            $manufacturers = [];
            $case = $list ? "rewrite_url" : "name";
            $manufacturers = Manufacturers::orderBy($case, 'asc')->lists($case, "id");
            return $manufacturers;
        }

        /**
         * getManufacturer
         *
         * @param char or int $_manufacturer
         * @return Array
         */
        public static function getManufacturer ($_manufacturer, $byName = false, $like = false) {
            $manufacturer = [];
            $case = $byName ? "name" : "rewrite_url";
            if ($_manufacturer) {
                if(is_numeric($_manufacturer)) {
                    //$manufacturer = Manufacturers::find($_manufacturer);
                    $result = Manufacturers::where('id', '=', $_manufacturer)
                        ->select('name','id','rewrite_url')
                        ->orderBy('name', 'ASC')
                        ->get();
                    foreach ($result as $row){
                        $manufacturer['name']           = $row->name;
                        $manufacturer['id']             = $row->id;
                        $manufacturer['rewrite_url']    = $row->rewrite_url;
                    }
                } elseif(is_string($_manufacturer)) {
                    if ($like) {
                        $result = Manufacturers::where($case, 'LIKE', "%$_manufacturer%")
                            ->select('name','id','rewrite_url')
                            ->orderBy('name', 'ASC')
                            ->get();
                        return $result;
                    } else {
                        $result = Manufacturers::where($case, '=', $_manufacturer)
                            ->select('name','id','rewrite_url')
                            ->get();
                    }
                    foreach ($result as $row){
                        $manufacturer['name']           = $row->name;
                        $manufacturer['id']             = $row->id;
                        $manufacturer['rewrite_url']    = $row->rewrite_url;
                    }
                }
            }
            return $manufacturer;
        }

            /**
             * getManufacturerByName
             *
             * @param char $_manufacturerName
             * @return Array
             */
            public static function getManufacturerByName ($_manufacturerName, $byName = false, $like = false) {
                $manufacturer = [];
                $case = $byName ? "name" : "rewrite_url";
                if ($_manufacturerName) {
                    if ($like) {
                        $result = Manufacturers::where($case, 'LIKE', "%$_manufacturerName%")->select('name','id','rewrite_url')->orderBy('name', 'ASC')->get();
                        return $result;
                    } else {
                        $result = Manufacturers::where($case, '=', $_manufacturerName)->select('name','id','rewrite_url')->get();
                    }

                    foreach ($result as $row){
                        $manufacturer['name']           = $row->name;
                        $manufacturer['id']             = $row->id;
                        $manufacturer['rewrite_url']    = $row->rewrite_url;
                    }
                }
                return $manufacturer;
            }

            /**
             * getManufacturerById
             *
             * @param  int $_manufacturerId
             * @return Array
             */
            public static function getManufacturerById ($_manufacturerId) {
                $manufacturer = [];
                if ($_manufacturerId) {
                    //$manufacturer = Manufacturers::find($_manufacturerId);
                    $result = Manufacturers::where('id', '=', $_manufacturerId)->select('name','id','rewrite_url')->get();
                    foreach ($result as $row){
                        $manufacturer['name']           = $row->name;
                        $manufacturer['id']             = $row->id;
                        $manufacturer['rewrite_url']    = $row->rewrite_url;
                    }
                }
                return $manufacturer;
            }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Models
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getModels
         *
         * @param  int $_manufacturersId
         * @return Array
         */
        public static function getModels ($_manufacturersId = '', $list = false, $ajax = false) {
            $models = [];
            $case = $list ? "rewrite_url" : "name";
            if($_manufacturersId) {
                if($ajax) {
                    $models = Models::where('manufacturers_id', '=', $_manufacturersId)
                        ->orderBy($case, 'ASC')
                        ->select($case,'id')->get();
                } else {
                    $models = Models::where('manufacturers_id', '=', $_manufacturersId)
                        ->orderBy($case, 'ASC')
                        ->lists($case, "id");
                }

            } else {
                $models = Models::orderBy($case, 'asc')->lists($case, "id");
            }
            return $models;
        }

            /**
             * getModel
             *
             * @param  char $_modelName
             * @return Array
             */
            public static function getModel ($_model, $byName = false, $like = false) {
                $model = [];
                $case = $byName ? "name" : "rewrite_url";
                if ($_model) {
                    if(is_numeric($_model)) {
                        //$model = Models::find($_model);
                        $result = Models::where('id', '=', $_model)
                            ->select('name','id','rewrite_url')
                            ->orderBy('name', 'ASC')
                            ->get();
                        foreach ($result as $row){
                            $model['name']           = $row->name;
                            $model['id']             = $row->id;
                            $model['rewrite_url']    = $row->rewrite_url;
                        }
                    } elseif(is_string($_model)) {
                        if ($like) {
                            $result = Models::where($case, 'LIKE', "%$_model%")
                                ->select('name','id','rewrite_url')
                                ->orderBy('name', 'ASC')
                                ->get();
                            return $result;
                        } else {
                            $result = Models::where($case, '=', $_model)
                                ->select('name','id','rewrite_url')
                                ->get();
                        }
                        foreach ($result as $row){
                            $model['name']           = $row->name;
                            $model['id']             = $row->id;
                            $model['rewrite_url']    = $row->rewrite_url;
                        }
                    }
                }
                return $model;
            }

            /**
             * getModelByName
             *
             * @param  int $_manufacturersId
             * @param  char $_modelName
             * @return Array
             */
            public static function getModelByName ($_manufacturersId, $_modelName, $byName = false, $like = false) {
                $model = [];
                $case = $byName ? "name" : "rewrite_url";
                if(is_numeric($_modelName)) {
                    $case = 'id';
                }
                if ($_modelName) {
                    if ($like) {
                        $WhereRaw = "`$case` LIKE "%$_modelName%" AND `manufacturers_id` = '" . $_manufacturersId . "'";
                    } else {
                        $WhereRaw = "`$case` = '" . $_modelName . "' AND `manufacturers_id` = '" . $_manufacturersId . "'";
                    }
                    $result = Models::whereRaw($WhereRaw)->select('name','id','rewrite_url','manufacturers_id')->orderBy('name', 'ASC')->get();

                    if ($like) {
                        return $result;
                    }

                    foreach ($result as $row){
                        $model['name']              = $row->name;
                        $model['id']                = $row->id;
                        $model['rewrite_url']       = $row->rewrite_url;
                        $model['manufacturers_id']  = $row->manufacturers_id;
                    }
                }
                return $model;
            }

            /**
             * getModelById
             *
             * @param  int $_modelId
             * @return Array
             */
            public static function getModelById ($_modelId) {
                $model = [];
                if ($_modelId) {
                    //$model = Models::find($_modelId);
                    $result = Models::where('id', '=', $_modelId)->select('name','id','rewrite_url','manufacturers_id')->get();
                    foreach ($result as $row){
                        $model['name']              = $row->name;
                        $model['id']                = $row->id;
                        $model['rewrite_url']       = $row->rewrite_url;
                        $model['manufacturers_id']  = $row->manufacturers_id;
                    }
                }
                return $model;
            }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Manufacturers Engines
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getManufacturersEngines
         *
         * @return Array
         */
        public static function getManufacturersEngines ($list = false) {
            $manufacturersengines = [];
            $case = $list ? "rewrite_url" : "name";
            $manufacturersengines = ManufacturersEngines::orderBy($case, 'asc')->lists($case, "id");
            return $manufacturersengines;
        }

        /**
         * getManufacturerEngine
         *
         * @param char or int $_manufacturerengine
         * @return Array
         */
        public static function getManufacturerEngine ($_manufacturerengine, $byName = false, $like = false) {
            $manufacturerengine = [];
            $case = $byName ? "name" : "rewrite_url";
            if ($_manufacturerengine) {
                if(is_numeric($_manufacturerengine)) {
                    $manufacturerengine = ManufacturersEngines::find($_manufacturerengine);
                } elseif(is_string($_manufacturerengine)) {
                    if ($like) {
                        $result = ManufacturersEngines::where($case, 'LIKE', "%$_manufacturerengine%")
                            ->select('name','id','rewrite_url')
                            ->orderBy('name', 'ASC')
                            ->get();
                        return $result;
                    } else {
                        $result = ManufacturersEngines::where($case, '=', $_manufacturerengine)
                            ->select('name','id','rewrite_url')
                            ->get();
                    }
                    foreach ($result as $row){
                        $manufacturerengine['name']         = $row->name;
                        $manufacturerengine['id']           = $row->id;
                        $manufacturerengine['rewrite_url']  = $row->rewrite_url;
                    }
                }
            }
            return $manufacturerengine;
        }

            /**
             * getManufacturerEngineByName
             *
             * @param char $_manufacturerengineName
             * @return Array
             */
            public static function getManufacturerEngineByName ($_manufacturerengineName, $byName = false, $like = false) {
                $manufacturerengine = [];
                $case = $byName ? "name" : "rewrite_url";
                if ($_manufacturerengineName) {
                    if ($like) {
                        $result = ManufacturersEngines::where($case, 'LIKE', "%$_manufacturerengineName%")->select('name','id','rewrite_url')->orderBy('name', 'ASC')->get();
                        return $result;
                    } else {
                        $result = ManufacturersEngines::where($case, '=', $_manufacturerengineName)->select('name','id','rewrite_url')->get();
                    }
                    foreach ($result as $row){
                        $manufacturerengine['name']         = $row->name;
                        $manufacturerengine['id']           = $row->id;
                        $manufacturerengine['rewrite_url']  = $row->rewrite_url;
                    }
                }
                return $manufacturerengine;
            }

            /**
             * getManufacturerEngineById
             *
             * @param  int $_manufacturerengineId
             * @return Array
             */
            public static function getManufacturerEngineById ($_manufacturerengineId) {
                $manufacturerengine = [];
                if ($_manufacturerengineId) {
                    //$manufacturerengine = ManufacturersEngines::find($_manufacturerengineId);
                    $result = ManufacturersEngines::where('id', '=', $_manufacturerengineId)->select('name','id','rewrite_url')->get();
                    foreach ($result as $row){
                        $manufacturerengine['name']         = $row->name;
                        $manufacturerengine['id']           = $row->id;
                        $manufacturerengine['rewrite_url']  = $row->rewrite_url;
                    }
                }
                return $manufacturerengine;
            }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Models Engines
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getModelsEngines
         *
         * @param  int $_manufacturersenginesId
         * @return Array
         */
        public static function getModelsEngines ($_manufacturersenginesId = '', $list = false) {
            $modelsengines = [];
            $case = $list ? "rewrite_url" : "name";
            if($_manufacturersenginesId) {
                $modelsengines = ModelsEngines::where('manufacturersengines_id', $_manufacturersenginesId)->orderBy($case, 'asc')->lists($case, "id");
            } else {
                $modelsengines = ModelsEngines::lists($case, "id");
            }
            return $modelsengines;
        }
            /**
             * getModelEngine
             *
             * @param  char $_modelEngine
             * @return Array
             */
            public static function getModelEngine ($_modelEngine, $byName = false, $like = false) {
                $modelengine = [];
                $case = $byName ? "name" : "rewrite_url";
                if ($_modelEngine) {
                    if(is_numeric($_modelEngine)) {
                        $model = ModelsEngines::find($_modelEngine);
                    } elseif(is_string($_modelEngine)) {
                        if ($like) {
                            $result = ModelsEngines::where($case, 'LIKE', "%$_modelEngine%")->select('name','id','rewrite_url')->orderBy('name', 'ASC')->get();
                            return $result;
                        } else {
                            $result = ModelsEngines::where($case, '=', $_modelEngine)->select('name','id','rewrite_url')->get();
                        }

                        foreach ($result as $row){
                            $modelengine['name']           = $row->name;
                            $modelengine['id']             = $row->id;
                            $modelengine['rewrite_url']    = $row->rewrite_url;
                        }
                    }
                }
                return $modelengine;
            }

            /**
             * getModelEngineByName
             *
             * @param  int $_manufacturersenginesId
             * @param  char $_modelengineName
             * @return Array
             */
            public static function getModelEngineByName ($_manufacturersenginesId, $_modelengineName, $byName = false, $like = false) {
                $modelengine = [];
                $case = $byName ? "name" : "rewrite_url";
                if(is_numeric($_modelengineName)) {
                    $case = 'id';
                }
                if ($_modelengineName) {
                    //$result = ModelsEngines::where('rewrite_url', '=', $_modelengineName)->select('name','id','rewrite_url','manufacturersengines_id')->get();
                    //$WhereRaw = '`rewrite_url` = "' . $_modelengineName .'" AND `manufacturersengines_id` = "' . $_manufacturersenginesId . '"';
                    if ($like) {
                        $WhereRaw = "`$case` LIKE "%$_modelengineName%" AND `manufacturersengines_id` = '" . $_manufacturersenginesId . "'";
                    } else {
                        $WhereRaw = "`$case` = '" . $_modelengineName . "' AND `manufacturersengines_id` = '" . $_manufacturersenginesId . "'";
                    }
                    $result = ModelsEngines::whereRaw($WhereRaw)->select('name','id','rewrite_url','manufacturersengines_id')->orderBy('name', 'ASC')->get();

                    if ($like) {
                        return $result;
                    }

                    foreach ($result as $row){
                        $modelengine['name']                    = $row->name;
                        $modelengine['id']                      = $row->id;
                        $modelengine['rewrite_url']             = $row->rewrite_url;
                        $modelengine['manufacturersengines_id'] = $row->manufacturersengines_id;
                    }
                }
                return $modelengine;
            }

            /**
             * getModelEngineById
             *
             * @param  int $_modelId
             * @return Array
             */
            public static function getModelEngineById ($_modelId) {
                $modelengine = [];
                if ($_modelId) {
                    //$category = ModelsEngines::find($_modelId);
                    $result = ModelsEngines::where('id', '=', $_modelId)->select('name','id','rewrite_url','manufacturersengines_id')->get();
                    foreach ($result as $row){
                        $modelengine['name']                    = $row->name;
                        $modelengine['id']                      = $row->id;
                        $modelengine['rewrite_url']             = $row->rewrite_url;
                        $modelengine['manufacturersengines_id'] = $row->manufacturersengines_id;
                    }
                }
                return $modelengine;
            }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // getAdsTypeBy
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getAdsTypeByCategory
         *
         * @param char or int $_adsCategory
         * @return Array
         */
        public function getAdsTypeByCategory ($_adsCategory) {
            //debug('>>> getAdsTypeByCategory <<<');
            $adstype = [];
            if ($_adsCategory) {
                $currentLocale  = app()->getLocale();
                if(is_numeric($_adsCategory)) {
                    $result = Categories::where('id', '=', $_adsCategory)->select('adstypes_id')->get();
                } elseif(is_string($_adsCategory)) {
                    //$fallback_locale    = config('app.fallback_locale');
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_adsCategory   = str_replace('_', '-', array_search($_adsCategory, $array));
                    }
                    $result = Categories::where('rewrite_url', '=', $_adsCategory)->select('adstypes_id')->get();
                }
                foreach ($result as $row){
                    $adsTypeId = $row->adstypes_id;
                }
                $adstype        = $this->getAdsTypeById($adsTypeId);
            }
            return $adstype;
        }

            /**
             * getAdsTypeByCategoryId
             *
             * @param int $_adsCategoryId
             * @return Array
             */
            public function getAdsTypeByCategoryId ($_adsCategoryId) {
                //debug('>>> getAdsTypeByCategoryId <<<');
                $adstype = [];
                if ($_adsCategoryId) {
                    $result = Categories::where('id', '=', $_adsCategoryId)->select('adstypes_id')->get();
                    foreach ($result as $row){
                        $adsTypeId = $row->adstypes_id;
                    }
                    $adstype        = $this->getAdsTypeById($adsTypeId);
                }
                return $adstype;
            }

            /**
             * getAdsTypeByCategoryName
             *
             * @param char $_adsCategoryName
             * @return Array
             */
            public function getAdsTypeByCategoryName ($_adsCategoryName) {
                //debug('>>> getAdsTypeByCategoryName <<<');
                $adstype = [];
                if ($_adsCategoryName) {
                    $currentLocale  = app()->getLocale();
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array              = Lang::get('routes');
                        $_adsCategoryName   = str_replace('_', '-', array_search($_adsCategoryName, $array));
                    }
                    $result = Categories::where('rewrite_url', '=', $_adsCategoryName)->select('adstypes_id')->get();
                    foreach ($result as $row){
                        $adsTypeId = $row->adstypes_id;
                    }
                    $adstype        = $this->getAdsTypeById($adsTypeId);
                }
                return $adstype;
            }

        /**
         * getAdsTypeBySubcategory
         *
         * @param char or int $_adsSubcategory
         * @return Array
         */
        public function getAdsTypeBySubcategory ($_adsSubcategory) {
            //debug('>>> getAdsTypeBySubcategory <<<');
            $adstype = [];
            if ($_adsSubcategory) {
                $currentLocale  = app()->getLocale();
                if(is_numeric($_adsSubcategory)) {
                    $result = Subcategories::where('id', '=', $_adsSubcategory)->select('categories_id')->get();
                } elseif(is_string($_adsSubcategory)) {
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_adsSubcategory   = str_replace('_', '-', array_search($_adsSubcategory, $array));
                    }
                    $result = Subcategories::where('rewrite_url', '=', $_adsSubcategory)->select('categories_id')->get();
                }
                foreach ($result as $row){
                    $categoryId = $row->categories_id;
                }
                $adstype        = $this->getAdsTypeByCategoryId($categoryId);
            }
            return $adstype;
        }

            /**
             * getAdsTypeBySubcategoryId
             *
             * @param char or int $_adsSubcategoryId
             * @return Array
             */
            public function getAdsTypeBySubcategoryId ($_adsSubcategoryId) {
                //debug('>>> getAdsTypeBySubcategoryId <<<');
                $adstype = [];
                if ($_adsSubcategoryId) {
                    $result = Subcategories::where('id', '=', $_adsSubcategoryId)->select('categories_id')->get();
                    foreach ($result as $row){
                        $categoryId = $row->categories_id;
                    }
                    $adstype        = $this->getAdsTypeByCategoryId($categoryId);
                }
                return $adstype;
            }

            /**
             * getAdsTypeBySubcategoryName
             *
             * @param char or int $_adsSubcategoryName
             * @return Array
             */
            public function getAdsTypeBySubcategoryName ($_adsSubcategoryName) {
                //debug('>>> getAdsTypeBySubcategoryName <<<');
                $adstype = [];
                if ($_adsSubcategoryName) {
                    $currentLocale  = app()->getLocale();
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array              = Lang::get('routes');
                        $_adsSubcategoryName = str_replace('_', '-', array_search($_adsSubcategoryName, $array));
                    }
                    $result = Subcategories::where('rewrite_url', '=', $_adsSubcategoryName)->select('categories_id')->get();
                    foreach ($result as $row){
                        $categoryId = $row->categories_id;
                    }
                    $adstype        = $this->getAdsTypeByCategoryId($categoryId);
                }
                return $adstype;
            }

        /**
         * getAdsCategoryBySubcategory
         *
         * @param char or int $_adsSubcategory
         * @return Array
         */
        public function getAdsCategoryBySubcategory ($_adsSubcategory) {
            //debug('>>> getAdsCategoryBySubcategory <<<');
            $category = [];
            if ($_adsSubcategory) {
                $currentLocale  = app()->getLocale();
                if(is_numeric($_adsSubcategory)) {
                    $result = Subcategories::where('id', '=', $_adsSubcategory)->select('categories_id')->get();
                } elseif(is_string($_adsSubcategory)) {
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_adsSubcategory   = str_replace('_', '-', array_search($_adsSubcategory, $array));
                    }
                    $result = Subcategories::where('rewrite_url', '=', $_adsSubcategory)->select('categories_id')->get();
                }
                foreach ($result as $row){
                    $categoryId = $row->categories_id;
                }
                $category        = $this->getCategoryById($categoryId);
            }
            return $category;
        }

            /**
             * getAdsCategoryBySubcategoryId
             *
             * @param char or int $_adsSubcategoryId
             * @return Array
             */
            public function getAdsCategoryBySubcategoryId ($_adsSubcategoryId) {
                //debug('>>> getAdsCategoryBySubcategoryId <<<');
                $category = [];
                if ($_adsSubcategoryId) {
                    $result = Subcategories::where('id', '=', $_adsSubcategoryId)->select('categories_id')->get();
                    foreach ($result as $row){
                        $categoryId = $row->categories_id;
                    }
                    $category        = $this->getCategoryById($categoryId);
                }
                return $category;
            }

            /**
             * getAdsCategoryBySubcategoryName
             *
             * @param char or int $_adsSubcategoryName
             * @return Array
             */
            public function getAdsCategoryBySubcategoryName ($_adsSubcategoryName) {
                //debug('>>> getAdsTypeBySubcategoryName <<<');
                $category = [];
                if ($_adsSubcategoryName) {
                    $currentLocale  = app()->getLocale();
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array              = Lang::get('routes');
                        $_adsSubcategoryName = str_replace('_', '-', array_search($_adsSubcategoryName, $array));
                    }
                    $result = Subcategories::where('rewrite_url', '=', $_adsSubcategoryName)->select('categories_id')->get();
                    foreach ($result as $row){
                        $categoryId = $row->categories_id;
                    }
                    $category        = $this->getCategoryById($categoryId);
                }
                return $category;
            }


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // getManufacturerBy
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getManufacturerByModel
         *
         * @param char or int $_adsModel
         * @return Array
         */
        public function getManufacturerByModel ($_adsModel) {
            //debug('>>> getManufacturerByModel <<<');
            $manufacturer = [];
            if ($_adsModel) {
                $currentLocale  = app()->getLocale();
                if(is_numeric($_adsModel)) {
                    $result = Models::where('id', '=', $_adsModel)->select('manufacturers_id')->get();
                } elseif(is_string($_adsModel)) {
                    //$fallback_locale    = config('app.fallback_locale');
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_adsModel   = str_replace('_', '-', array_search($_adsModel, $array));
                    }
                    $result = Models::where('rewrite_url', '=', $_adsModel)->select('manufacturers_id')->get();
                }
                foreach ($result as $row){
                    $manufacturerId = $row->manufacturers_id;
                }
                $manufacturer        = $this->getManufacturerById($manufacturerId);
            }
            return $manufacturer;
        }

        /**
         * getManufacturerByModelId
         *
         * @param int $_adsModelId
         * @return Array
         */
        public function getManufacturerByModelId ($_adsModelId) {
            //debug('>>> getManufacturerByModelId <<<');
            $manufacturer = [];
            if ($_adsModelId) {
                $result = Models::where('id', '=', $_adsModelId)->select('manufacturers_id')->get();
                foreach ($result as $row){
                    $manufacturerId = $row->manufacturers_id;
                }
                $manufacturer        = $this->getManufacturerById($manufacturerId);
            }
            return $manufacturer;
        }

        /**
         * getManufacturerByModelName
         *
         * @param char $_adsModelName
         * @return Array
         */
        public function getManufacturerByModelName ($_adsModelName) {
            //debug('>>> getManufacturerByModel <<<');
            $manufacturer = [];
            if ($_adsModelName) {
                $currentLocale  = app()->getLocale();
                if (config('app.fallback_locale') != $currentLocale) {
                    $array              = Lang::get('routes');
                    $_adsModelName   = str_replace('_', '-', array_search($_adsModelName, $array));
                }
                $result = Models::where('rewrite_url', '=', $_adsModelName)->select('manufacturers_id')->get();
                foreach ($result as $row){
                    $manufacturerId = $row->manufacturers_id;
                }
                $manufacturer        = $this->getManufacturerById($manufacturerId);
            }
            return $manufacturer;
        }


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // getManufacturerEngineBy
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getManufacturerEngineByModelEngine
         *
         * @param char or int $_adsModelEngine
         * @return Array
         */
        public function getManufacturerEngineByModelEngine ($_adsModelEngine) {
            //debug('>>> getManufacturerEngineByModelEngine <<<');
            $manufacturerengine = [];
            if ($_adsModelEngine) {
                $currentLocale  = app()->getLocale();
                if(is_numeric($_adsModelEngine)) {
                    $result = ModelsEngines::where('id', '=', $_adsModelEngine)->select('manufacturersengines_id')->get();
                } elseif(is_string($_adsModelEngine)) {
                    //$fallback_locale    = config('app.fallback_locale');
                    if (config('app.fallback_locale') != $currentLocale) {
                        $array          = Lang::get('routes');
                        $_adsModelEngine   = str_replace('_', '-', array_search($_adsModelEngine, $array));
                    }
                    $result = ModelsEngines::where('rewrite_url', '=', $_adsModelEngine)->select('manufacturersengines_id')->get();
                }
                foreach ($result as $row){
                    $manufacturerengineId = $row->manufacturersengines_id;
                }
                $manufacturerengine        = $this->getManufacturerEngineById($manufacturerengineId);
            }
            return $manufacturerengine;
        }

        /**
         * getManufacturerEngineByModelEngineId
         *
         * @param int $_adsModelEngineId
         * @return Array
         */
        public function getManufacturerEngineByModelEngineId ($_adsModelEngineId) {
            //debug('>>> getManufacturerEngineByModelEngineId <<<');
            $manufacturerengine = [];
            if ($_adsModelEngineId) {
                $result = ModelsEngines::where('id', '=', $_adsModelEngineId)->select('manufacturersengines_id')->get();
                foreach ($result as $row){
                    $manufacturerengineId = $row->manufacturersengines_id;
                }
                $manufacturerengine        = $this->getManufacturerEngineById($manufacturerengineId);
            }
            return $manufacturerengine;
        }

        /**
         * getManufacturerByModelEngineName
         *
         * @param char $_adsModelEngineName
         * @return Array
         */
        public function getManufacturerEngineByModelEngineName ($_adsModelEngineName) {
            //debug('>>> getManufacturerEngineByModelEngine <<<');
            $manufacturerengine = [];
            if ($_adsModelEngineName) {
                $currentLocale  = app()->getLocale();
                if (config('app.fallback_locale') != $currentLocale) {
                    $array              = Lang::get('routes');
                    $_adsModelEngineName   = str_replace('_', '-', array_search($_adsModelEngineName, $array));
                }
                $result = ModelsEngines::where('rewrite_url', '=', $_adsModelEngineName)->select('manufacturersengines_id')->get();
                foreach ($result as $row){
                    $manufacturerengineId = $row->manufacturersengines_id;
                }
                $manufacturerengine        = $this->getManufacturerById($manufacturerengineId);
            }
            return $manufacturerengine;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // getAdsBy
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getAdsByType
         *
         * @param char or int $_adsType
         * @return Array
         */
        public function getAdsByType ($_adsType) {
            $ads = [];
            $adstype = [];
            if ($_adsType) {
                if(is_numeric($_adsType)) {
                    $result = AdsTypes::where('id', '=', $_adsType)->select('name','id','rewrite_url','status')->get();
                } elseif(is_string($_adsType)) {
                    $result = AdsTypes::where('rewrite_url', '=', $_adsType)->select('name','id','rewrite_url','status')->get();
                }
                foreach ($result as $row){
                    $adstype['name']    = $row->name;
                    $adstype['id']      = $row->id;
                    $adstype['rewrite_url']  = $row->rewrite_url;
                    $adstype['status']  = $row->status;
                }
            }
            return $adstype;
        }

        /**
         * getAdsByTypeName
         *
         * @param char $_adsTypeName
         * @return Array
         */
        public function getAdsByTypeName ($_adsTypeName) {
            $adstype = [];
            if ($_adsTypeName) {
                $result = AdsTypes::where('rewrite_url', '=', $_adsTypeName)->select('name','id','rewrite_url','status')->get();
                foreach ($result as $row){
                    $adstype['name']    = $row->name;
                    $adstype['id']      = $row->id;
                    $adstype['rewrite_url']  = $row->rewrite_url;
                    $adstype['status']  = $row->status;
                }
            }
            return compact('adstype');
        }

        /**
         * getAdsByTypeId
         *
         * @param  int $_adsTypeId
         * @return Array
         */
        public static function getAdsByTypeId ($_adsTypeId) {
            $adstype = [];
            if ($_adsTypeId) {
                //$adstype = AdsTypes::find($_adsTypeId);
                $result = AdsTypes::where('id', '=', $_adsTypeId)->select('name','id','rewrite_url','status')->get();
                foreach ($result as $row){
                    $adstype['name']    = $row->name;
                    $adstype['id']      = $row->id;
                    $adstype['rewrite_url']  = $row->rewrite_url;
                    $adstype['status']  = $row->status;
                }
            }
            return compact('adstype');
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getAdsList
         *
         * @param  int $datasRequest
         * @return Array
         */
        public static function getAdsList ($datasRequest, $_status = 'active') {
            //debug('>>getAdsList<<');
            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');

            $ads_list = [];
            $search_query = null;
            if ($datasRequest && !empty($datasRequest['query'])) {
                $search_query = $datasRequest['query'];
            }

            if ($datasRequest) {
                $arrayGetAdsList = $datasRequest;
                if(!is_array($arrayGetAdsList)) {
                    $arrayGetAdsList = json_decode(json_encode($arrayGetAdsList), true);
                    if (is_array($arrayGetAdsList) && isset($arrayGetAdsList[0])) {
                        $arrayGetAdsList = json_decode(json_encode($arrayGetAdsList), true)[0];
                    } else {
                        //return response()->view('errors.ad_not_found', ['error_code'=>404, 'datasRequest'=>request()->route()->parameters()]);
                        /*$error_code = 'ad_not_found';
                        return view('errors.ad_not_found', compact(
                            'error_code',
                            'routeParameters',
                            'ads_list',
                            'datasRequest'
                        ));*/
                    }
                }

                $sell_type = (!empty($arrayGetAdsList['sell_type']) ? $arrayGetAdsList['sell_type'] : null);
                $adstypes_id = (!empty($arrayGetAdsList['adstypes_id']) ? SearchController::getAdsTypeById($arrayGetAdsList['adstypes_id'])['id'] : null);
                $categories_ids = (!empty($arrayGetAdsList['categories_ids']) ? SearchController::getCategoryById($arrayGetAdsList['categories_ids'])['id'] : null);
                $subcategories_ids = (!empty($arrayGetAdsList['subcategories_ids']) ? SearchController::getSubcategoryById($arrayGetAdsList['subcategories_ids'])['id'] : null);

                $manufacturers_id = (!empty($arrayGetAdsList['manufacturers_id']) ? SearchController::getManufacturerById($arrayGetAdsList['manufacturers_id'])['id'] : null);
                $manufacturersengines_id = (!empty($arrayGetAdsList['manufacturersengines_id']) ? SearchController::getManufacturerEngineById($arrayGetAdsList['manufacturersengines_id'])['id'] : null);
                $models_id = (!empty($arrayGetAdsList['models_id']) ? SearchController::getModelById($arrayGetAdsList['models_id'])['id'] : null);
                $modelsengines_id = (!empty($arrayGetAdsList['modelsengines_id']) ? SearchController::getModelEngineById($arrayGetAdsList['modelsengines_id'])['id'] : null);

                $min_length = (!empty($arrayGetAdsList['min_length']) ? $arrayGetAdsList['min_length'] : null);
                $max_length = (!empty($arrayGetAdsList['max_length']) ? $arrayGetAdsList['max_length'] : null);

                $min_width = (!empty($arrayGetAdsList['min_width']) ? $arrayGetAdsList['min_width'] : null);
                $max_width = (!empty($arrayGetAdsList['max_width']) ? $arrayGetAdsList['max_width'] : null);

                $min_draft = (!empty($arrayGetAdsList['min_draft']) ? $arrayGetAdsList['min_draft'] : null);
                $max_draft = (!empty($arrayGetAdsList['max_draft']) ? $arrayGetAdsList['max_draft'] : null);

                $min_year_built = (!empty($arrayGetAdsList['min_year_built']) ? $arrayGetAdsList['min_year_built'] : null);
                $max_year_built = (!empty($arrayGetAdsList['max_year_built']) ? $arrayGetAdsList['max_year_built'] : null);

                $min_ad_price = (!empty($arrayGetAdsList['min_ad_price']) ? $arrayGetAdsList['min_ad_price'] : null);
                $max_ad_price = (!empty($arrayGetAdsList['$max_ad_price']) ? $arrayGetAdsList['$max_ad_price'] : null);

                $min_engine_power = (!empty($arrayGetAdsList['min_engine_power']) ? $arrayGetAdsList['min_engine_power'] : null);
                $max_engine_power = (!empty($arrayGetAdsList['max_engine_power']) ? $arrayGetAdsList['max_engine_power'] : null);
                $type_engine_power = (!empty($arrayGetAdsList['type_engine_power']) ? $arrayGetAdsList['type_engine_power'] : null);

                /*$countries_id = (!empty($arrayGetAdsList['countries_id']) ?
                    SearchController::getCountry($arrayGetAdsList['countries_id'])['id'] :
                    SearchController::getCountry(config('youboat.' . $currentCountryCode . '.country_code'))['id']);*/
                $getCountry = !empty($arrayGetAdsList['countries_id']) ?
                    SearchController::getCountry($arrayGetAdsList['countries_id']) :
                    SearchController::getCountry(config('youboat.' . $currentCountryCode . '.country_code'));
                $countries_id = isset($getCountry) && array_key_exists('id', $getCountry) ? $getCountry['id'] : 77;

                $county_id = (!empty($arrayGetAdsList['county_id']) && '' != $arrayGetAdsList['county_id'] ? $arrayGetAdsList['county_id'] : null);
                $dealerscaracts_id = (!empty($arrayGetAdsList['dealerscaracts_id']) ? $arrayGetAdsList['dealerscaracts_id'] : null);
            }

            //$sort_by                    = 'start_date';
            $sort_by = $sort_by_request = 'updated_at';
            $sort_direction = 'desc';
            if (isset($arrayGetAdsList['sort_by'])) {
                list($sort_by, $sort_direction) = explode("-", $arrayGetAdsList['sort_by']);
                $from = ['year_built', 'model', 'title'];
                $to = ['ad_year_built', 'ad_model_name', 'ad_title'];
                $sort_by_request = str_replace($from, $to, $sort_by);
            }
            $current_page               = isset($arrayGetAdsList['page']) ? $arrayGetAdsList['page'] : 1;
            $results_view               = isset($arrayGetAdsList['results_view']) ? $arrayGetAdsList['results_view'] : 'list';
            $max                        = isset($arrayGetAdsList['max']) ? $arrayGetAdsList['max'] : 20;
            //$max_query                        = (!empty($arrayGetAdsList['results_view']) && 'grid' == $arrayGetAdsList['results_view']) ? $max + ($max == 10 ? 2 : 1) : $max;
            $max_query                  = $max;
            $start                      = $max * ($current_page -1);

            // Need to contruct Where !
            $WhereRaw = '';
            if (!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            //$WhereRaw .= 'AND ad_title <> "" ';
            /*if (isset($datasRequest['no_empty_photo']) && $datasRequest['no_empty_photo']) {
                $WhereRaw .= 'AND (ad_photo IS NOT NULL OR ad_photo <> "") ';
            }*/
            $WhereRaw .= 'AND adstypes_id != 11 '; // exclude Location ad's type
            if($search_query) {
                if (!empty($countries_id)) {
                    $WhereRaw .= 'AND ' . 'countries_id = "' . $countries_id . '" ';
                }
                $WhereRaw .= "AND (";
                $WhereRaw .= "ad_title LIKE '%%$search_query%%' ";
                $WhereRaw .= "OR ";
                //$WhereRaw .= "ad_type LIKE '%%$search_query%%' ";
                //$WhereRaw .= "OR ";
                //$WhereRaw .= "ad_category LIKE '%%$search_query%%' ";
                //$WhereRaw .= "OR ";
                $WhereRaw .= "ad_manufacturer_name LIKE '%%$search_query%%' ";
                $WhereRaw .= "OR ";
                $WhereRaw .= "ad_model_name LIKE '%%$search_query%%' ";
                //$WhereRaw .= "OR ";
                //$WhereRaw .= "ad_dealer_name LIKE '%%$search_query%%' ";
                //$WhereRaw .= "OR ";
                //$WhereRaw .= "ad_location LIKE '%%$search_query%%' ";
                //$WhereRaw .= "OR ";
                //$WhereRaw .= "ad_country LIKE '%%$search_query%%' ";
                //$WhereRaw .= "OR ";
                //$WhereRaw .= "ad_region LIKE '%%$search_query%%' ";
                //$WhereRaw .= "OR ";
                //$WhereRaw .= "ad_county LIKE '%%$search_query%%' ";
                $WhereRaw .= ") ";
            } else {
                //if (empty($min_ad_price) && empty($sell_type) && empty($adstypes_id) && empty($categories_ids) && empty($manufacturers_id)) {
                if (empty($min_ad_price)) {
                    $min_ad_price = 1000;
                }
            }
            //else {
            if (!empty($sell_type)) {
                $WhereRaw .= 'AND ' . 'sell_type = "' . $sell_type . '" ';
            }

            if (!empty($adstypes_id)) {
                $WhereRaw .= 'AND ' . 'adstypes_id = "' . $adstypes_id . '" ';
            }
            if (!empty($categories_ids)) {
                $WhereRaw .= 'AND ' . 'categories_ids = "' . $categories_ids . '" ';
            }
            if (!empty($subcategories_ids)) {
                $WhereRaw .= 'AND ' . 'subcategories_ids = "' . $subcategories_ids . '" ';
            }

            if (!empty($manufacturers_id)) {
                $WhereRaw .= 'AND ' . 'manufacturers_id = "' . $manufacturers_id . '" ';
            }
            if (!empty($manufacturersengines_id)) {
                $WhereRaw .= 'AND ' . 'manufacturersengines_id = "' . $manufacturersengines_id . '" ';
            }

            if (!empty($models_id)) {
                $WhereRaw .= 'AND ' . 'models_id = "' . $models_id . '" ';
            }
            if (!empty($modelsengines_id)) {
                $WhereRaw .= 'AND ' . 'modelsengines_id = "' . $modelsengines_id . '" ';
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
            if (!empty($county_id)) {
                $WhereRaw .= 'AND ' . 'county_id = "' . $county_id . '" ';
            }
            if (!empty($dealerscaracts_id)) {
                $WhereRaw .= 'AND ' . 'dealerscaracts_id = "' . $dealerscaracts_id . '" ';
            }

            // filter by countrycontractsIds
            $countrycontractsIds = [];
            if (!empty($country_code)) {
                $getCountry = SearchController::getCountry($country_code);
                $country_id = isset($getCountry) && array_key_exists('id', $getCountry) ? $getCountry['id'] : 77;
                $countrycontracts = CountryContracts::where('countries_ids', 'LIKE', '%' . $country_id . '%')
                    ->where('status', 'active')
                    ->pluck('id');
                $countrycontractsIds = $countrycontracts->toArray();
            }
            if(!empty($countrycontractsIds)) {
                //$WhereRaw .= 'AND ' . 'countrycontracts_id IN (' . implode(',', $countrycontractsIds) . ')';
                $WhereRaw .= 'AND (' . 'countrycontracts_id IN (' . implode(',', $countrycontractsIds) . ') OR ad_country_code = "' . $currentCountryCode . '")'; //PATCH SCRAPPING

            //}
                // filter by country only on homepage for recent ads
                if (app('request')->route()->getName() == 'homepage' && !empty($countries_id)) {
                    $WhereRaw .= 'AND ' . 'countries_id = "' . $countries_id . '" ';
                }
                $ads_list = Gateway::select(
                    'id', 'ad_referrer',
                    'ad_title', 'ad_photo', 'ad_price', 'ad_description',
                    'sell_type',
                    'ad_category',
                    'ad_width_meter',
                    'ad_length_meter',
                    'ad_length_meter',
                    //'ad_engine_power',
                    //'ad_type_engine_power',
                    'ad_manufacturer_name',
                    'ad_manufacturer_url',
                    'ad_model_name',
                    'ad_model_url',
                    'adstypes_id', 'categories_ids', 'subcategories_ids',
                    'manufacturers_id', 'models_id',
                    'manufacturersengines_id', 'modelsengines_id',
                    'countries_id', 'province', 'region', 'subregion', 'city',
                    'ad_dealer_name', 'dealerscaracts_id'
                )
                ->whereRaw($WhereRaw)
                //->skip($start)
                ->take($max_query)
                ->orderBy($sort_by_request, $sort_direction)
                ->paginate($max_query);
                //->get();
                $ads_list->appends(['sort_by' => $sort_by . '-' . $sort_direction]);
            } else {
                $ads_list = ['data'=>''];
            }
            return compact('ads_list');
        }

        /**
         * getAdDetail
         *
         * @param  int $_adId
         * @return Array
         */
        public function getAdDetail ($_adId) {
            //debug('<< getAdDetail >>');
            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code       = config('youboat.'. $currentCountryCode .'.country_code');

            $ad_detail = [];
            if ($_adId) {
                $ad_detail = Gateway::where('status', 'active')->where('id', '=', $_adId)
                    ->select(
                        'id',
                        'ad_referrer',
                        'ad_country_code',

                        'ad_title',
                        'ad_ref',

                        'ad_type_cat_url',
                        'ad_type',

                        'ad_type_cat_name',
                        'ad_category',

                        'ad_manufacturer_name',
                        'ad_manufacturer_url',

                        'ad_model_name',
                        'ad_model_url',

                        'ad_price',
                        'ad_price_descr',

                        'ad_location',
                        'ad_mooring_country',
                        'ad_country',
                        'ad_region',
                        'ad_county',

                        'ad_dealer_name',
                        'ad_dealer_url',

                        'ad_phones',

                        'ad_sale',
                        'ad_sale_type_condition',
                        'ad_sales_status',

                        'ad_year_built',

                        'ad_width',
                        'ad_length',

                        'ad_width_meter',
                        'ad_length_meter',

                        'ad_description',
                        'ad_description_caracts_labels',
                        'ad_description_caracts_values',

                        'ad_specifications',
                        'ad_specifications_caracts_labels',
                        'ad_specifications_caracts_values',

                        'ad_features',
                        'ad_features_caracts_categories',
                        'ad_features_caracts_values',

                        'ad_photo',
                        //'ad_photos_thumbs',
                        'ad_photos',

                        'ad_propulsion',
                        'ad_nb_engines',

                        'ad_pageUrl',

                        // @TODO

                        'dealerscaracts_id',
                        'adstypes_id',
                        'categories_ids',
                        'subcategories_ids',

                        'manufacturers_id',
                        'models_id',
                        'manufacturersengines_id',
                        'modelsengines_id',

                        'countries_id',
                        'province',
                        'region',
                        'subregion',
                        'city',
                        'zip',

                        'sell_type',

                        'start_date',
                        'end_date',
                        'status'
                    )
                    ->get();
            }
            return $ad_detail;
        }

        /**
         * getTotal
         *
         * @param  int $datasRequest
         * @return Array
         */
        public static function getTotal ($what='status', $value='active', $WhereRaw= '') {
            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');

            $total = '';
            $WhereRawCountryContracts = '';
            $countrycontractsIds = [];
            if (!empty($country_code)) {
                $getCountry = SearchController::getCountry($country_code);
                $country_id = isset($getCountry) && array_key_exists('id', $getCountry) ? $getCountry['id'] : 77;
                $countrycontracts = CountryContracts::where('countries_ids', 'LIKE', '%' . $country_id . '%')
                    ->where('status', 'active')
                    ->pluck('id');
                $countrycontractsIds = $countrycontracts->toArray();
            }
            if(!empty($countrycontractsIds)) {
                $WhereRawCountryContracts = 'countrycontracts_id IN (' . implode(',', $countrycontractsIds) . ')';
            }

            $WhereExclude = ' adstypes_id != 11 '; // exclude Location ad's type

            if(empty($WhereRaw) && empty($WhereRawCountryContracts)) {
                $total = Gateway::where('status', 'active')->where($what, $value)->whereRaw($WhereExclude)->count();
            } else if (!empty($WhereRawCountryContracts)) {
                $WhereRawCountryContracts = !empty($WhereRaw) ? '(' . $WhereRawCountryContracts . $WhereRaw : $WhereRawCountryContracts;
                //$WhereRawCountryContracts .= $WhereRaw;
                $total = Gateway::where('status', 'active')->where($what, $value)->whereRaw($WhereExclude)->whereRaw('(' . $WhereRawCountryContracts . ')')->count();
            }
            return $total;
        }

        ///////////////////////////////////////////////////////////////////////////////
        // getGateWay /////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////
        /**
         * getGateWayAdsTypes
         * @return Array
         */
        public static function getGateWayAdsTypes ($excludeIds = [], $_status = 'active') {
            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code       = config('youboat.'. $currentCountryCode .'.country_code');
            $countries          = SearchController::getCountry($country_code);
            $adstypes           = [];

            //$WhereRaw = "`adstypes_id` <> '' AND `countries_id` =" . $countries['id'];
            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `adstypes_id` <> ''";
            if(count($excludeIds)>0) {
                foreach($excludeIds as $id) {
                    $WhereRaw .= ' AND id <> "' . $id .'"';
                }
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];
            $result = Gateway::select(DB::raw('count(*) as adstypes_count, adstypes_id'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('adstypes_id')
                ->groupby('adstypes_id')
                ->get();

            foreach ($result as $key => $row){
                if (is_numeric($row->adstypes_id) && $row->adstypes_count) {
                    $adstypes[$row->adstypes_id][] = $row->adstypes_id;
                    $adstypes[$row->adstypes_id]['count'] = $row->adstypes_count;
                }
            }
            return $adstypes;
        }

        /**
         * getGateWayAdsCategories
         * @return Array
         */
        public static function getGateWayAdsCategories ($_adsTypeId='', $_status = 'active') {
            $categories = [];
            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `categories_ids` <> ''";
            if (!empty($_adsTypeId)) {
                $WhereRaw .= " AND `adstypes_id` = '" . $_adsTypeId . "'";
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];
            $result = Gateway::select(DB::raw('count(*) as categories_count, categories_ids'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('categories_ids')
                ->groupby('categories_ids')
                ->get();

            foreach ($result as $key => $row) {
                if (is_numeric($row->categories_ids) && $row->categories_count) {
                    $categories[$row->categories_ids][] = $row->categories_ids;
                    $categories[$row->categories_ids]['count'] = $row->categories_count;
                }
            }
            return $categories;
        }

        /**
         * getGateWayAdsSubcategories
         * @return Array
         */
        public static function getGateWayAdsSubcategories ($_adsCategoryId='', $_status = 'active') {
            $subcategories = [];
            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `subcategories_ids` <> ''";
            if (!empty($_adsCategoryId)) {
                $WhereRaw .= " AND `categories_ids` = '" . $_adsCategoryId . "'";
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];
            $result = Gateway::select(DB::raw('count(*) as subcategories_count, subcategories_ids'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('subcategories_ids')
                ->groupby('subcategories_ids')
                ->get();

            foreach ($result as $key => $row) {
                if (is_numeric($row->subcategories_ids) && $row->subcategories_count) {
                    $subcategories[$row->subcategories_ids][] = $row->subcategories_ids;
                    $subcategories[$row->subcategories_ids]['count'] = $row->subcategories_count;
                }
            }
            return $subcategories;
        }

        /**
         * getGateWayManufacturers
         *
         * @return Array
         */
        public static function getGateWayManufacturers ($_status = 'active')
        {
            $manufacturers = [];

            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `ad_manufacturer_name` <> ''";
            $WhereRaw .= " AND `manufacturers_id` <> ''";
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];

            //$manufacturers = Gateway::select(DB::raw('ad_manufacturer_name, manufacturers_id, CONCAT(ad_manufacturer_name, "#", count(*)) AS manufacturer_name'))
            //$manufacturers = Gateway::select(DB::raw('CONCAT(ad_manufacturer_name, "#", count(*)) AS name, manufacturers_id as id'))
            $manufacturers = Gateway::select(DB::raw('ad_manufacturer_name AS name, manufacturers_id as id'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('manufacturers_id')
                ->groupby('manufacturers_id')
                ->lists('name', 'id');

            //debug('$manufacturers');
            //debug($manufacturers);

            return $manufacturers;
        }

        /**
         * getGateWayAdsManufacturers
         *
         * @return Array
         */
        public static function getGateWayAdsManufacturers ($_adsTypeId='', $_adsCategoryId='', $_adsSubcategoryId='', $_status = 'active') {
            //debug('>>> getGateWayAdsManufacturers <<<');
            $manufacturers = [];

            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `ad_manufacturer_name` <> ''";
            $WhereRaw .= " AND `manufacturers_id` <> ''";
            if (!empty($_adsTypeId)) {
                $WhereRaw .= " AND `adstypes_id` = '" . $_adsTypeId . "'";
            }
            if (!empty($_adsCategoryId)) {
                $WhereRaw .= " AND `categories_ids` = '" . $_adsCategoryId . "'";
            }
            if (!empty($_adsSubcategoryId)) {
                $WhereRaw .= " AND `subcategories_ids` = '" . $_adsSubcategoryId . "'";
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];
            $result = Gateway::select(DB::raw('count(*) as manufacturers_count, manufacturers_id'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('manufacturers_id')
                ->orderby('ad_manufacturer_name', 'ASC')
                ->groupby('manufacturers_id')
                ->get();

            foreach ($result as $key => $row) {
                if (is_numeric($row->manufacturers_id) && $row->manufacturers_count) {
                    $manufacturers[$row->manufacturers_id][] = $row->manufacturers_id;
                    $manufacturers[$row->manufacturers_id]['count'] = $row->manufacturers_count;
                }
            }
            //debug('$manufacturers');
            //debug($manufacturers);
            return $manufacturers;
        }

        /**
         * getGateWayAdsManufacturersByName ($_manufacturerName, $like = false
         *
         * @return Array
         */
        public static function getGateWayAdsManufacturersByName ($_manufacturerName, $like = false, $_adsTypeId='', $_adsCategoryId='', $_adsSubcategoryId='', $_status = 'active') {
            //debug('>>> getGateWayAdsManufacturersByName <<<');
            $manufacturer = null;

            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `ad_manufacturer_name` <> ''";
            $WhereRaw .= " AND `manufacturers_id` > 0";
            if (!empty($_adsTypeId)) {
                $WhereRaw .= " AND `adstypes_id` = '" . $_adsTypeId . "'";
            }
            if (!empty($_adsCategoryId)) {
                $WhereRaw .= " AND `categories_ids` = '" . $_adsCategoryId . "'";
            }
            if (!empty($_adsSubcategoryId)) {
                $WhereRaw .= " AND `subcategories_ids` = '" . $_adsSubcategoryId . "'";
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];

            if ($_manufacturerName) {
                $case = is_numeric($_manufacturerName) ? "manufacturers_id" : "ad_manufacturer_name";
                if ($like) {
                    $queryOperator = 'LIKE';
                    $queryValue = "%$_manufacturerName%";
                } else {
                    $queryOperator = '=';
                    $queryValue = $_manufacturerName;
                }
                $result = Gateway::where($case, $queryOperator, $queryValue)
                    ->whereRaw($WhereRaw)
                    ->whereNotNull('manufacturers_id')
                    ->select(DB::raw('ad_manufacturer_name as name, manufacturers_id as id, count(*) as count'))
                    ->orderby('ad_manufacturer_name', 'ASC')
                    ->groupby('ad_manufacturer_name')
                    ->get();
            } else {
                $result = Gateway::whereRaw($WhereRaw)
                    ->whereNotNull('manufacturers_id')
                    ->select(DB::raw('ad_manufacturer_name as name, manufacturers_id as id, count(*) as count'))
                    ->orderby('ad_manufacturer_name', 'ASC')
                    ->groupby('ad_manufacturer_name')
                    ->get();
            }

            if ($like) {
                foreach ($result as $row) {
                    $manufacturer[] = [
                        //'name' => trim($row->name) .' (' . $row->count . ')',
                        'name' => trim($row->name),
                        'id' => $row->id,
                        'rewrite_url' => str_slug(trim($row->name)),
                        'count' => $row->count
                    ];
                }
            } else {
                foreach ($result as $row){
                    $manufacturer = [
                        //'name' => trim($row->name) . ' (' . $row->count . ')',
                        'name' => trim($row->name),
                        'id' => $row->id,
                        'rewrite_url' => str_slug(trim($row->name)),
                        'count' => $row->count
                    ];
                }
            }

            //debug('>>$manufacturer<<');
            //debug($manufacturer);
            return $manufacturer;
        }

        /**
         * getGateWayManufacturerByName
         *
         * @param char $_manufacturerName
         * @return Array
         */
        public static function getGateWayManufacturerByName ($_manufacturerName, $like = false, $_status = 'active') {
            //debug('>>> getGateWayManufacturerByName <<<');

            $manufacturer = [];
            if ($_manufacturerName) {
                $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
                $country_code = config('youboat.' . $currentCountryCode . '.country_code');
                $countries = SearchController::getCountry($country_code);

                $case = is_numeric($_manufacturerName) ? "manufacturers_id" : "ad_manufacturer_name";
                if ($_manufacturerName) {
                    $WhereRaw = "";
                    if(!empty($_status)) {
                        $WhereRaw .= 'status = "' . $_status . '" ';
                    }
                    $WhereRaw .= " AND `manufacturers_id` > 0";
                    $WhereRaw .= " AND `countries_id` =" . $countries['id'];
                    if ($like) {
                        $result = Gateway::where($case, 'LIKE', "%$_manufacturerName%")
                            ->whereRaw($WhereRaw)
                            ->whereNotNull('manufacturers_id')
                            ->select(DB::raw('ad_manufacturer_name as name, manufacturers_id as id, count(*) as count'))
                            ->orderby('ad_manufacturer_name', 'ASC')
                            ->groupby('ad_manufacturer_name')
                            ->get();
                        foreach ($result as $row){
                            $manufacturer[] = [
                                //'name' => trim($row->name) . ' (' . $row->count . ')',
                                'name' => trim($row->name),
                                'id' => $row->id,
                                'rewrite_url' => str_slug(trim($row->name)),
                                'count' => $row->count
                            ];
                        }
                    } else {
                        $result = Gateway::where($case, '=', $_manufacturerName)
                            ->whereRaw($WhereRaw)
                            ->whereNotNull('manufacturers_id')
                            ->select(DB::raw('ad_manufacturer_name as name, manufacturers_id as id, count(*) as count'))
                            ->orderby('ad_manufacturer_name', 'ASC')
                            ->groupby('ad_manufacturer_name')
                            ->get();
                        foreach ($result as $row){
                            $manufacturer = [
                                //'name' => trim($row->name) . ' (' . $row->count . ')',
                                'name' => trim($row->name),
                                'id' => $row->id,
                                'rewrite_url' => str_slug(trim($row->name)),
                                'count' => $row->count
                            ];
                        }
                    }
                }
                //debug('$manufacturer');
                //debug($manufacturer);
            }
            return $manufacturer;
        }

        /**
         * getGateWayManufacturersEngines
         *
         * @return Array
         */
        public static function getGateWayManufacturersEngines () {
            $manufacturers = [];
            $WhereRaw = "`ad_manufacturer_name` <> '' AND `manufacturersengines_id` <> ''";
            $manufacturersengines = Gateway::select('ad_manufacturer_name', 'manufacturersengines_id')
                ->whereRaw($WhereRaw)
                ->orderBy('ad_manufacturer_name', 'ASC')
                ->lists('ad_manufacturer_name', 'manufacturersengines_id');
            return $manufacturersengines;
        }

        /**
         * getGateWayManufacturerEngineByName
         *
         * @param char $_manufacturerengineName
         * @return Array
         */
        public static function getGateWayManufacturerEngineByName ($_manufacturerengineName, $like = false) {
            $manufacturerengine = [];
            $case = "ad_manufacturer_name";
            if ($_manufacturerengineName) {
                if ($like) {
                    $manufacturerengine = Gateway::where($case, 'LIKE', "%$_manufacturerengineName%")
                        ->select('ad_manufacturer_name', 'manufacturersengines_id')
                        ->orderBy('ad_manufacturer_name', 'ASC')
                        ->get();
                    //->lists('ad_manufacturer_name', 'manufacturersengines_id');
                } else {
                    $manufacturerengine = Gateway::where($case, '=', $_manufacturerengineName)
                        ->select('ad_manufacturer_name', 'manufacturersengines_id')
                        ->orderBy('ad_manufacturer_name', 'ASC')
                        ->get();
                    //->lists('ad_manufacturer_name', 'manufacturersengines_id');
                }
            }
            return $manufacturerengine;
        }


        /**
         * getGateWayAdsManufacturersEngines
         *
         * @return Array
         */
        public static function getGateWayAdsManufacturersEngines ($_adsTypeId='', $_adsCategoryId='', $_adsSubcategoryId='', $_status = 'active') {
            //debug('>>> getGateWayAdsManufacturersEngines <<<');

            $manufacturersengines = [];

            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `ad_manufacturer_name` <> ''";
            $WhereRaw .= " AND `manufacturersengines_id` <> ''";
            if (!empty($_adsTypeId)) {
                $WhereRaw .= " AND `adstypes_id` = '" . $_adsTypeId . "'";
            }
            if (!empty($_adsCategoryId)) {
                $WhereRaw .= " AND `categories_ids` = '" . $_adsCategoryId . "'";
            }
            if (!empty($_adsSubcategoryId)) {
                $WhereRaw .= " AND `subcategories_ids` = '" . $_adsSubcategoryId . "'";
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];
            $result = Gateway::select(DB::raw('count(*) as manufacturersengines_count, manufacturersengines_id'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('manufacturersengines_id')
                ->orderby('ad_manufacturer_name', 'ASC')
                ->groupby('manufacturersengines_id')
                ->get();

            foreach ($result as $key => $row) {
                if (is_numeric($row->manufacturersengines_id) && $row->manufacturersengines_count) {
                    $manufacturersengines[$row->manufacturersengines_id][] = $row->manufacturersengines_id;
                    $manufacturersengines[$row->manufacturersengines_id]['count'] = $row->manufacturersengines_count;
                }
            }
            return $manufacturersengines;
        }


        /**
         * getGateWayAdsManufacturersEnginesByName ($_manufacturerName, $like = false
         *
         * @return Array
         */
        public static function getGateWayAdsManufacturersEnginesByName ($_manufacturerName, $like = false, $_adsTypeId='', $_adsCategoryId='', $_adsSubcategoryId='', $_status = 'active') {
            //debug('>>> getGateWayAdsManufacturersByName <<<');
            $manufacturerengine = null;

            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `ad_manufacturer_name` <> ''";
            $WhereRaw .= " AND `manufacturersengines_id` > 0";
            if (!empty($_adsTypeId)) {
                $WhereRaw .= " AND `adstypes_id` = '" . $_adsTypeId . "'";
            }
            if (!empty($_adsCategoryId)) {
                $WhereRaw .= " AND `categories_ids` = '" . $_adsCategoryId . "'";
            }
            if (!empty($_adsSubcategoryId)) {
                $WhereRaw .= " AND `subcategories_ids` = '" . $_adsSubcategoryId . "'";
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];

            if ($_manufacturerName) {
                $case = is_numeric($_manufacturerName) ? "manufacturersengines_id" : "ad_manufacturer_name";
                if ($like) {
                    $queryOperator = 'LIKE';
                    $queryValue = "%$_manufacturerName%";
                } else {
                    $queryOperator = '=';
                    $queryValue = $_manufacturerName;
                }
                $result = Gateway::where($case, $queryOperator, $queryValue)
                    ->whereRaw($WhereRaw)
                    ->whereNotNull('manufacturersengines_id')
                    ->select(DB::raw('ad_manufacturer_name as name, manufacturersengines_id as id, count(*) as count'))
                    ->orderby('ad_manufacturer_name', 'ASC')
                    ->groupby('ad_manufacturer_name')
                    ->get();
            } else {
                $result = Gateway::whereRaw($WhereRaw)
                    ->whereNotNull('manufacturersengines_id')
                    ->select(DB::raw('ad_manufacturer_name as name, manufacturersengines_id as id, count(*) as count'))
                    ->orderby('ad_manufacturer_name', 'ASC')
                    ->groupby('ad_manufacturer_name')
                    ->get();
            }

            if ($like) {
                foreach ($result as $row) {
                    $manufacturerengine[] = [
                        //'name' => trim($row->name) .' (' . $row->count . ')',
                        'name' => trim($row->name),
                        'id' => $row->id,
                        'rewrite_url' => str_slug(trim($row->name)),
                        'count' => $row->count
                    ];
                }
            } else {
                foreach ($result as $row){
                    $manufacturerengine = [
                        //'name' => trim($row->name) . ' (' . $row->count . ')',
                        'name' => trim($row->name),
                        'id' => $row->id,
                        'rewrite_url' => str_slug(trim($row->name)),
                        'count' => $row->count
                    ];
                }
            }

            //debug('>>$manufacturerengine<<');
            //debug($manufacturerengine);
            return $manufacturerengine;
        }


        /**
         * getGateWayAdsModels
         *
         * @return Array
         */
        public static function getGateWayAdsModels ($_adsManufacturersId='', $_status = 'active') {
            //debug('>>> getGateWayAdsModels <<<');
            $models = [];

            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `ad_model_name` <> ''";
            $WhereRaw .= " AND `models_id` <> ''";
            if (!empty($_adsManufacturersId)) {
                $WhereRaw .= " AND `manufacturers_id` = '" . $_adsManufacturersId . "'";
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];
            $result = Gateway::select(DB::raw('count(*) as models_count, models_id'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('models_id')
                ->orderby('ad_model_name', 'ASC')
                ->groupby('models_id')
                ->get();

            foreach ($result as $key => $row) {
                if (is_numeric($row->models_id) && $row->models_count) {
                    $models[$row->models_id][] = $row->models_id;
                    $models[$row->models_id]['count'] = $row->models_count;
                }
            }
            return $models;
        }

        /**
         * getGateWayModelByName
         *
         * @param char $_modelName
         * @return Array
         */
        public static function getGateWayModelByName ($_modelName, $like = false, $_status = 'active') {
            //debug('>>> getGateWayModelByName <<<');

            $model = [];
            if ($_modelName) {
                $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
                $country_code = config('youboat.' . $currentCountryCode . '.country_code');
                $countries = SearchController::getCountry($country_code);

                $case = is_numeric($_modelName) ? "models_id" : "ad_model_name";
                if ($_modelName) {
                    $WhereRaw = "";
                    if(!empty($_status)) {
                        $WhereRaw .= 'status = "' . $_status . '" ';
                    }
                    //$WhereRaw .= " AND `models_id` <> ''";
                    $WhereRaw .= " AND `countries_id` =" . $countries['id'];
                    if ($like) {
                        $result = Gateway::where($case, 'LIKE', "%$_modelName%")
                            ->whereRaw($WhereRaw)
                            ->whereNotNull('models_id')
                            ->select(DB::raw('ad_model_name as name, models_id as id, count(*) as count'))
                            ->orderby('ad_model_name', 'ASC')
                            ->groupby('ad_model_name')
                            ->get();
                        foreach ($result as $row){
                            $model[] = [
                                //'name' => trim($row->name) . ' (' . $row->count . ')',
                                'name' => trim($row->name),
                                'id' => $row->id,
                                'rewrite_url' => str_slug(trim($row->name)),
                                'count' => $row->count
                            ];
                        }
                    } else {
                        $result = Gateway::where($case, '=', $_modelName)
                            ->whereRaw($WhereRaw)
                            ->whereNotNull('models_id')
                            ->select(DB::raw('ad_model_name as name, models_id as id, count(*) as count'))
                            ->orderby('ad_model_name', 'ASC')
                            ->groupby('ad_model_name')
                            ->get();
                        foreach ($result as $row){
                            $model = [
                                //'name' => trim($row->name) . ' (' . $row->count . ')',
                                'name' => trim($row->name),
                                'id' => $row->id,
                                'rewrite_url' => str_slug(trim($row->name)),
                                'count' => $row->count
                            ];
                        }
                    }
                }
                //debug('$model');
                //debug($model);
            }
            return $model;
        }


        /**
         * getGateWayAdsModelsEngines
         *
         * @return Array
         */
        public static function getGateWayAdsModelsEngines ($_adsManufacturersEnginesId='', $_status = 'active') {
            //debug('>>> getGateWayAdsModelsEngines <<<');

            $modelsengines = [];

            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `ad_model_name` <> ''";
            $WhereRaw .= " AND `models_id` <> ''";
            if (!empty($_adsManufacturersId)) {
                $WhereRaw .= " AND `manufacturers_id` = '" . $_adsManufacturersEnginesId . "'";
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];
            $result = Gateway::select(DB::raw('count(*) as modelsengines_count, modelsengines_id'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('modelsengines_id')
                ->orderby('ad_model_name', 'ASC')
                ->groupby('modelsengines_id')
                ->get();

            foreach ($result as $key => $row) {
                if (is_numeric($row->modelsengines_id) && $row->modelsengines_count) {
                    $modelsengines[$row->modelsengines_id][] = $row->modelsengines_id;
                    $modelsengines[$row->modelsengines_id]['count'] = $row->modelsengines_count;
                }
            }
            return $modelsengines;
        }

        /**
         * getGateWayYearsBuilt
         *
         * @return Array
         */
        public static function getGateWayYearsBuilt ($_status = 'active') {
            $years_built = [];
            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `ad_year_built` <> ''";
            $result = Gateway::select(DB::raw('count(*) as year_count, ad_year_built'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('ad_year_built')
                ->groupby('ad_year_built')
                //->distinct()
                ->orderBy('ad_year_built', 'ASC')
                ->get();

            foreach ($result as $key => $row){
                if (is_numeric($row->ad_year_built) && $row->ad_year_built < 2030 && strlen($row->ad_year_built) > 2) {
                    $years_built[$row->ad_year_built][]      = $row->ad_year_built;
                    $years_built[$row->ad_year_built]['count']       = $row->year_count;
                }
            }

            return $years_built;
        }

        /**
         * getGateWayAdPrices
         *
         * @return Array
         */
        public static function getGateWayAdPrices ($min_price=0, $max_price=0, $_status = 'active') {
            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $_countryCode = config('youboat.' . $currentCountryCode . '.country_code') ?: 'GB';
            $locale = SearchController::getCountryLocaleCode($_countryCode);
            setlocale(LC_MONETARY, $locale);

            $ad_prices = [];
            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `ad_price` <> ''";
            if (!empty($min_price)) {
                $WhereRaw .= 'AND ' . 'ad_year_built >= ' . $min_price . ' ';
            }
            if (!empty($max_price)) {
                $WhereRaw .= 'AND ' . 'ad_year_built <= ' . $max_price . ' ';
            }
            $result = Gateway::select(DB::raw('count(*) as ad_price_count, ad_price'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('ad_price')
                ->groupby('ad_price')
                //->distinct()
                ->orderBy('ad_price', 'ASC')
                ->get();

            foreach ($result as $key => $row){
                $ad_price = $row->ad_price;
                if (!is_numeric($ad_price)) {
                    list($price_currency, $price) = explode(" ", $ad_price);
                    $price = str_replace(',', '.', $price);
                    //$price = number_format($price, 2, '.', '');
                    //$price = number_format($price);

                }
                //$ad_prices[$ad_price][]      = $ad_price;
                $ad_prices[$ad_price]['price']      = trim(preg_replace('!\s+!', ' ', $ad_price));
//                $ad_prices[$ad_price]['price_formatted']      = trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $ad_price)));
                $ad_prices[$ad_price]['price_formatted']      = formatPrice($ad_price);
                $ad_prices[$ad_price]['count'] = $row->ad_price_count;
            }

            return $ad_prices;
        }


        /**
         * getGateWaySellType
         *
         * @return Array
         */
        public static function getGateWaySellType ($_adsTypeId='', $_adsCategoryId='', $_adsSubcategoryId='', $_adsManufacturersId='', $_adsModelsId='', $_status = 'active') {
            $sell_type = [];
            $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
            $country_code = config('youboat.' . $currentCountryCode . '.country_code');
            $countries = SearchController::getCountry($country_code);

            $WhereRaw = "";
            if(!empty($_status)) {
                $WhereRaw .= 'status = "' . $_status . '" ';
            }
            $WhereRaw .= " AND `sell_type` <> ''";
            if (!empty($_adsTypeId)) {
                $WhereRaw .= " AND `adstypes_id` = '" . $_adsTypeId . "'";
            }
            if (!empty($_adsCategoryId)) {
                $WhereRaw .= " AND `categories_ids` = '" . $_adsCategoryId . "'";
            }
            if (!empty($_adsSubcategoryId)) {
                $WhereRaw .= " AND `subcategories_ids` = '" . $_adsSubcategoryId . "'";
            }
            if (!empty($_adsManufacturersId)) {
                $WhereRaw .= " AND `manufacturers_id` = '" . $_adsManufacturersId . "'";
            }
            if (!empty($_adsModelsId)) {
                $WhereRaw .= " AND `models_id` = '" . $_adsModelsId . "'";
            }
            $WhereRaw .= " AND `countries_id` =" . $countries['id'];
            $result = Gateway::select(DB::raw('sell_type, count(*) as sell_type_count'))
                ->whereRaw($WhereRaw)
                ->whereNotNull('sell_type')
                ->groupby('sell_type')
                //->distinct()
                ->orderBy('sell_type', 'ASC')
                ->get();

            foreach ($result as $key => $row){
                $sell_type[$row->sell_type][]      = $row->sell_type;
                $sell_type[$row->sell_type]['count'] = $row->sell_type_count;
            }

            return $sell_type;
        }

        /**
         * Store a notification search in storage.
         *
         * @param SearchNotificationFormRequest|Request $request
         */
        public function notification(SearchNotificationRequest $request)
        {
            $viewName       = app('request')->route()->getName();
            $currentLocale = config('app.locale');

            try {
                $rules = SearchNotificationRequest::rules();

                $datasRequest = $request->all();

                $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : '';
                $manufacturers_id = !empty($datasRequest['manufacturers_id']) ? $datasRequest['manufacturers_id'] : '';
                $models_id = !empty($datasRequest['models_id']) ? $datasRequest['models_id'] : '';

                    $SearchNotification = SearchNotification::firstOrNew(array('ci_email' => $datasRequest['ci_email']));
                $validator = Validator::make($datasRequest, $rules);

                if($validator->fails()) {
                    $errors = $validator->errors();
                    $array['errors'] = $errors;
                    return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                } else {
                    $SearchNotification->fill($datasRequest)->save();

                    if ($SearchNotification->save()) {
                        $datasRequest['reference'] = 'sn_' . $country_code . '_' . $_SERVER['REQUEST_TIME'];
                        $inputProspectiveCustomers = array(
                            'ci_email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                            'referrer' => 'search_notification',
                            'manufacturers_id' => !empty($manufacturers_id) ? $manufacturers_id : null,
                            'models_id' => !empty($models_id) ? $models_id : null,
                            'country_code' => !empty($country_code) ? $country_code : null,
                            'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                        );

                        //$ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['ci_email']));
                        //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
                        $ProspectiveCustomers = ProspectiveCustomers::create($inputProspectiveCustomers);
                        if ($ProspectiveCustomers->save()) {
                        }

                        $manufacturer = !empty($manufacturers_id) ? $this->getManufacturerById($manufacturers_id) : '';
                        $manufacturer_name = !empty($manufacturer) && array_key_exists('name', $manufacturer) ? $manufacturer['name'] : '';
                        $manufacturer_rewrite_url = !empty($manufacturer) && array_key_exists('rewrite_url', $manufacturer) ? $manufacturer['rewrite_url'] : '';

                        $model = !empty($models_id) ? $this->getModelById($models_id) : '';
                        $model_name = !empty($model) && array_key_exists('name', $model) ? $model['name'] : '';
                        $model_rewrite_url = !empty($model) && array_key_exists('rewrite_url', $model) ? $model['rewrite_url'] : '';

                        $search_url = !empty($manufacturer_rewrite_url) && !empty($model_rewrite_url) ? trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model') . '/' . $manufacturer_rewrite_url . '/' . $model_rewrite_url : (!empty($manufacturer_rewrite_url) ? trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer') . '/' . $manufacturer_rewrite_url : '');

                        $details = [
                            'manufacturer_name' => $manufacturer_name,
                            'model_name' => $model_name,
                            'search_url' => $search_url
                        ];
                        $datasEmail = array(
                            //'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null,
                            'details' => $details,
                            'website_name' => config('youboat.' . $country_code . '.website_name'),
                            'type_request' => 'a searh notification request',
                            'email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                            'country_code' => $country_code,
                            'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                            'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                            'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                        );

                        $datasEmail['reference'] = !empty($datasRequest['reference']) ? $datasRequest['reference'] : null;
                        Mail::send('emails.search_notification', $datasEmail, function ($message) use ($datasEmail) {
                            $message->subject(trans('emails.search_notification') . ' ' . $datasEmail['website_name']);
                            $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            $message->to($datasEmail['email'], $datasEmail['email']);
                        });
                        $msg = trans('emails.search_notification_confirmation_msg');
                        $msg .= '<br>' . ucfirst(trans('navigation.for')) . ' ' . $manufacturer_name . ' ' . $model_name;
                        $request->session()->put('search_notification_message.text', $msg);
                        $request->session()->put('search_notification_message.type', 'success');

                        $cookieName = 'search_notification';
                        $cookieValue[] = [
                            'manufacturer_name' => $manufacturer_name,
                            'model_name' => $model_name
                        ];
                        $minutes = '';
                        $cookie = cookie($cookieName, $cookieValue, $minutes);
                        return redirect()->back()->cookie($cookie);
                    }
                }
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }

        public function autocomplete(){
            $term = Input::get('query');

            $results = array();

            $queries = Gateway::where('ad_manufacturer_name', 'LIKE', '%' . $query . '%')
                ->orWhere('ad_model_name', 'LIKE', '%' . $query . '%')
                ->take(5)->get();

            foreach ($queries as $query)
            {
                $results[] = [ 'id' => $query->id, 'value' => $query->ad_manufacturer_name . ' ' . $query->ad_model_name ];
            }
            return Response::json($results);
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // getDealerCaracts
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getDealerCaracts
         *
         * @param char or int $_adDealer
         * @return Array
         */
        public static function getDealerCaracts ($_adDealer, $byName = false, $like = false) {
            $dealerscaracts = [];
            if(is_bool($byName)) {
                //$case = $byName ? "name" : "rewrite_url";
                $case = $byName ? "denomination" : "rewrite_url";
            } elseif (is_string($byName)) {
                $case = $byName;
            }
            if ($_adDealer) {
                if(is_numeric($_adDealer)) {
                    $result = DealersCaracts::where('id', '=', $_adDealer)->select('id','user_id','firstname','name','denomination','rewrite_url','status')->get();
                } elseif(is_string($_adDealer)) {
                    if ($like) {
                        $result = DealersCaracts::where($case, 'LIKE', "%$_adDealer%")->select('id','user_id','firstname','name','denomination','rewrite_url','status')->orderBy('name', 'ASC')->get();
                    } else {
                        $result = DealersCaracts::where($case, '=', $_adDealer)->select('id','user_id','firstname','name','denomination','rewrite_url','status')->get();
                    }
                }
                foreach ($result as $row){
                    $dealerscaracts['id']           = $row->id;
                    $dealerscaracts['user_id']           = $row->user_id;
                    $dealerscaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . ' / ' : '') . (!empty($row->denomination) ? $row->denomination . ' ' : '');
                    $dealerscaracts['denomination']  = $row->denomination;
                    $dealerscaracts['rewrite_url']  = $row->rewrite_url;
                    $dealerscaracts['status']       = $row->status;
                }
            }
            return $dealerscaracts;
        }

        /**
         * getDealerCaractsByName
         *
         * @param  char $_adDealerName
         * @return Array
         */
        public static function getDealerCaractsByName ($_adDealerName, $byName = true) {
            //debug('>>> getDealerCaractsByName <<<');
            $dealerscaracts = [];
            if(is_bool($byName)) {
                $case = $byName ? "name" : "rewrite_url";
            } elseif (is_string($byName)) {
                $case = $byName;
            }
            if ($_adDealerName) {
                $result = DealersCaracts::where($case, '=', $_adDealerName)->select('id','user_id','firstname','name','denomination','rewrite_url','status')->get();
                foreach ($result as $row){
                    $dealerscaracts['id']           = $row->id;
                    $dealerscaracts['user_id']           = $row->user_id;
                    $dealerscaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . ' / ' : '') . (!empty($row->denomination) ? $row->denomination . ' ' : '');
                    $dealerscaracts['denomination']  = $row->denomination;
                    $dealerscaracts['rewrite_url']  = $row->rewrite_url;
                    $dealerscaracts['status']       = $row->status;
                }
            }
            return $dealerscaracts;
        }

        /**
         * getDealerCaractsById
         *
         * @param  int $_adDealerId
         * @return Array
         */
        public static function getDealerCaractsById ($_adDealerId) {
            //debug('>>> getDealerCaractsById <<<');
            $dealerscaracts = [];
            if ($_adDealerId) {
                //$dealerscaracts = DealersCaracts::find($_adDealerId);
                $result = DealersCaracts::where('id', '=', $_adDealerId)->select('id','user_id','firstname','name','denomination','address','address', 'address_more', 'zip', 'city', 'province', 'region', 'subregion', 'country_id', 'phone_1','rewrite_url','status')->get();
                foreach ($result as $row){
                    $dealerscaracts['id']           = $row->id;
                    $dealerscaracts['user_id']           = $row->user_id;
                    $dealerscaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . ' / ' : '') . (!empty($row->denomination) ? $row->denomination . ' ' : '');
                    $dealerscaracts['rewrite_url']  = $row->rewrite_url;

                    $address = '';
                    $address = !empty($row->address) ? $row->address : '';
                    $address .= !empty($row->address_more) ? (!empty($address) ? ', ' : '') . $row->address_more : '';
                    $address .= !empty($row->zip) ? (!empty($address) ? ', ' : '') . $row->zip : '';
                    $address .= !empty($row->city) ? (!empty($address) ? ', ' : '') . $row->city : '';
                    $address .= !empty($row->province) ? (!empty($address) ? ', ' : '') . $row->province : '';
                    $address .= !empty($row->region) ? (!empty($address) ? ', ' : '') . $row->region : '';
                    $address .= !empty($row->subregion) ? (!empty($address) ? ', ' : '') . $row->subregion : '';
                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id) : '';

                    $country_name = !empty($country) && array_key_exists('name', $country) && !empty($country['name']) ? $country['name'] : '';
                    $address .= !empty($country_name) ? (!empty($address) ? ', ' : '') . $country_name : '';

                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id, false) : '';
                    $country_code = !empty($country) && array_key_exists('code', $country) && !empty($country['code']) ? $country['code'] : '';

                    $dealerscaracts['address']      = $address;
                    $dealerscaracts['country_code'] = $country_code;

                    $dealerscaracts['phone']        = $row->phone_1;
                    $dealerscaracts['status']       = $row->status;
                }
            }
            return $dealerscaracts;
        }

        /**
         * getDealerCaractsByUserId
         *
         * @param  int $_adDealerId
         * @return Array
         */
        public static function getDealerCaractsByUserId ($_adDealerUserId) {
            //debug('>>> getDealerCaractsByUserId <<<');
            $dealerscaracts = [];
            if ($_adDealerUserId) {
                $result = DealersCaracts::where('user_id', '=', $_adDealerUserId)->select('id','user_id','firstname','name','denomination','address','phone_1','rewrite_url','status')->get();
                foreach ($result as $row){
                    $dealerscaracts['id']           = $row->id;
                    $dealerscaracts['user_id']           = $row->user_id;
                    $dealerscaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . ' / ' : '') . (!empty($row->denomination) ? $row->denomination . ' ' : '');
                    $dealerscaracts['rewrite_url']  = $row->rewrite_url;

                    $address = '';
                    $address = !empty($row->address) ? $row->address : '';
                    $address .= !empty($row->address_more) ? (!empty($address) ? ', ' : '') . $row->address_more : '';
                    $address .= !empty($row->zip) ? (!empty($address) ? ', ' : '') . $row->zip : '';
                    $address .= !empty($row->city) ? (!empty($address) ? ', ' : '') . $row->city : '';
                    $address .= !empty($row->province) ? (!empty($address) ? ', ' : '') . $row->province : '';
                    $address .= !empty($row->region) ? (!empty($address) ? ', ' : '') . $row->region : '';
                    $address .= !empty($row->subregion) ? (!empty($address) ? ', ' : '') . $row->subregion : '';
                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id) : '';

                    $country_name = !empty($country) && array_key_exists('name', $country) && !empty($country['name']) ? $country['name'] : '';
                    $address .= !empty($country_name) ? (!empty($address) ? ', ' : '') . $country_name : '';

                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id, false) : '';
                    $country_code = !empty($country) && array_key_exists('code', $country) && !empty($country['code']) ? $country['code'] : '';

                    $dealerscaracts['address']      = $address;
                    $dealerscaracts['country_code'] = $country_code;

                    $dealerscaracts['phone']        = $row->phone_1;
                    $dealerscaracts['status']       = $row->status;
                }
            }
            return $dealerscaracts;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // getCommercialCaracts
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getCommercialCaracts
         *
         * @param char or int $_adCommercial
         * @return Array
         */
        public static function getCommercialCaracts ($_adCommercial, $byName = false, $like = false) {
            $commercialscaracts = [];
            if(is_bool($byName)) {
                $case = $byName ? "name" : "rewrite_url";
            } elseif (is_string($byName)) {
                $case = $byName;
            }
            if ($_adCommercial) {
                if(is_numeric($_adCommercial)) {
                    $result = CommercialsCaracts::where('id', '=', $_adCommercial)->select('id','country_code','user_id','firstname','name','rewrite_url')->get();
                } elseif(is_string($_adCommercial)) {
                    if ($like) {
                        $result = CommercialsCaracts::where($case, 'LIKE', "%$_adCommercial%")->select('id','country_code','user_id','firstname','name','rewrite_url')->orderBy('name', 'ASC')->get();
                    } else {
                        $result = CommercialsCaracts::where($case, '=', $_adCommercial)->select('id','country_code','user_id','firstname','name','rewrite_url')->get();
                    }
                }
                foreach ($result as $row){
                    $commercialscaracts['id']           = $row->id;
                    $commercialscaracts['country_code']           = $row->country_code;
                    $commercialscaracts['user_id']           = $row->user_id;
                    $commercialscaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . '' : '');
                    $commercialscaracts['rewrite_url']  = $row->rewrite_url;
                }
            }
            return $commercialscaracts;
        }

        /**
         * getCommercialCaractsByName
         *
         * @param  char $_adCommercialName
         * @return Array
         */
        public static function getCommercialCaractsByName ($_adCommercialName, $byName = true) {
            //debug('>>> getCommercialCaractsByName <<<');
            $commercialscaracts = [];
            if(is_bool($byName)) {
                $case = $byName ? "name" : "rewrite_url";
            } elseif (is_string($byName)) {
                $case = $byName;
            }
            if ($_adCommercialName) {
                $result = CommercialsCaracts::where($case, '=', $_adCommercialName)->select('id','country_code','user_id','firstname','name','rewrite_url')->get();
                foreach ($result as $row){
                    $commercialscaracts['id']           = $row->id;
                    $commercialscaracts['country_code']           = $row->country_code;
                    $commercialscaracts['user_id']           = $row->user_id;
                    $commercialscaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . '' : '');
                    $commercialscaracts['rewrite_url']  = $row->rewrite_url;
                }
            }
            return $commercialscaracts;
        }

        /**
         * getCommercialCaractsById
         *
         * @param  int $_adCommercialId
         * @return Array
         */
        public static function getCommercialCaractsById ($_adCommercialId) {
            //debug('>>> getCommercialCaractsById <<<');
            $commercialscaracts = [];
            if ($_adCommercialId) {
                //$commercialscaracts = CommercialsCaracts::find($_adCommercialId);
                $result = CommercialsCaracts::where('id', '=', $_adCommercialId)->select('id','country_code','user_id','firstname','name','address','address', 'address_more', 'zip', 'city', 'province', 'region', 'subregion', 'country_id', 'phone_1','rewrite_url')->get();
                foreach ($result as $row){
                    $commercialscaracts['id']           = $row->id;
                    $commercialscaracts['country_code']           = $row->country_code;
                    $commercialscaracts['user_id']           = $row->user_id;
                    $commercialscaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . '' : '');
                    $commercialscaracts['rewrite_url']  = $row->rewrite_url;

                    $address = '';
                    $address = !empty($row->address) ? $row->address : '';
                    $address .= !empty($row->address_more) ? (!empty($address) ? ', ' : '') . $row->address_more : '';
                    $address .= !empty($row->zip) ? (!empty($address) ? ', ' : '') . $row->zip : '';
                    $address .= !empty($row->city) ? (!empty($address) ? ', ' : '') . $row->city : '';
                    $address .= !empty($row->province) ? (!empty($address) ? ', ' : '') . $row->province : '';
                    $address .= !empty($row->region) ? (!empty($address) ? ', ' : '') . $row->region : '';
                    $address .= !empty($row->subregion) ? (!empty($address) ? ', ' : '') . $row->subregion : '';
                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id) : '';

                    $country_name = !empty($country) && array_key_exists('name', $country) && !empty($country['name']) ? $country['name'] : '';
                    $address .= !empty($country_name) ? (!empty($address) ? ', ' : '') . $country_name : '';

                    $commercialscaracts['address']      = $address;
                    //$country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id, false) : '';
                    //$country_code = !empty($country) && array_key_exists('code', $country) && !empty($country['code']) ? $country['code'] : '';
                    //$commercialscaracts['country_code'] = $country_code;

                    $commercialscaracts['phone']        = $row->phone_1;
                }
            }
            return $commercialscaracts;
        }

        /**
         * getCommercialCaractsByUserId
         *
         * @param  int $_adCommercialId
         * @return Array
         */
        public static function getCommercialCaractsByUserId ($_adCommercialUserId) {
            //debug('>>> getCommercialCaractsByUserId <<<');
            $commercialscaracts = [];
            if ($_adCommercialUserId) {
                $result = CommercialsCaracts::where('user_id', '=', $_adCommercialUserId)->select('id','country_code','user_id','firstname','name','address','phone_1','rewrite_url')->get();
                foreach ($result as $row){
                    $commercialscaracts['id']           = $row->id;
                    $commercialscaracts['country_code']           = $row->country_code;
                    $commercialscaracts['user_id']           = $row->user_id;
                    $commercialscaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . '' : '');
                    $commercialscaracts['rewrite_url']  = $row->rewrite_url;

                    $address = '';
                    $address = !empty($row->address) ? $row->address : '';
                    $address .= !empty($row->address_more) ? (!empty($address) ? ', ' : '') . $row->address_more : '';
                    $address .= !empty($row->zip) ? (!empty($address) ? ', ' : '') . $row->zip : '';
                    $address .= !empty($row->city) ? (!empty($address) ? ', ' : '') . $row->city : '';
                    $address .= !empty($row->province) ? (!empty($address) ? ', ' : '') . $row->province : '';
                    $address .= !empty($row->region) ? (!empty($address) ? ', ' : '') . $row->region : '';
                    $address .= !empty($row->subregion) ? (!empty($address) ? ', ' : '') . $row->subregion : '';
                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id) : '';

                    $country_name = !empty($country) && array_key_exists('name', $country) && !empty($country['name']) ? $country['name'] : '';
                    $address .= !empty($country_name) ? (!empty($address) ? ', ' : '') . $country_name : '';

                    $commercialscaracts['address']      = $address;

                    //$country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id, false) : '';
                    //$country_code = !empty($country) && array_key_exists('code', $country) && !empty($country['code']) ? $country['code'] : '';
                    //$commercialscaracts['country_code'] = $country_code;

                    $commercialscaracts['phone']        = $row->phone_1;
                }
            }
            return $commercialscaracts;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // getCustomerCaracts
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getCustomerCaracts
         *
         * @param char or int $_adCustomer
         * @return Array
         */
        public static function getCustomerCaracts ($_adCustomer, $byName = false, $like = false) {
            $customercaracts = [];
            $case = $byName ? "name" : "rewrite_url";
            if ($_adCustomer) {
                if(is_numeric($_adCustomer)) {
                    //$result = CustomersCaracts::where('customer_id', '=', $_adCustomer)->select('id','firstname','name','origin')->get();
                    $result = CustomersCaracts::where('user_id', '=', $_adCustomer)->select('id','user_id','firstname','name','origin')->get();
                } elseif(is_string($_adCustomer)) {
                    if ($like) {
                        $result = CustomersCaracts::where($case, 'LIKE', "%$_adCustomer%")->select('id','user_id','firstname','name','origin')->orderBy('name', 'ASC')->get();
                    } else {
                        $result = CustomersCaracts::where($case, '=', $_adCustomer)->select('id','user_id','firstname','name','origin')->get();
                    }
                }
                foreach ($result as $row){
                    $customercaracts['firstname']   = $row->firstname;
                    $customercaracts['name']        = $row->name;
                    $customercaracts['id']          = $row->id;
                    $customercaracts['user_id']          = $row->user_id;
                    $customercaracts['origin']      = $row->origin;
                }
            }
            return $customercaracts;
        }

        /**
         * getCustomerCaractsByName
         *
         * @param  char $_adCustomerName
         * @return Array
         */
        public static function getCustomerCaractsByName ($_adCustomerName) {
            //debug('>>> getCustomerCaractsByName <<<');
            $customercaracts = [];
            if ($_adCustomerName) {
                $result = CustomersCaracts::where('rewrite_url', '=', $_adCustomerName)->select('id','user_id','firstname','name','origin')->get();
                foreach ($result as $row){
                    $customercaracts['firstname']   = $row->firstname;
                    $customercaracts['name']        = $row->name;
                    $customercaracts['id']          = $row->id;
                    $customercaracts['user_id']          = $row->user_id;
                    $customercaracts['origin']      = $row->origin;
                }
            }
            return $customercaracts;
        }

        /**
         * getCustomerCaractsById
         *
         * @param  int $_adCustomerId
         * @return Array
         */
        public static function getCustomerCaractsById ($_adCustomerId) {
            //debug('>>> getCustomerCaractsById <<<');
            $customercaracts = [];
            if ($_adCustomerId) {
                //$customercaracts = CustomersCaracts::find($_adCustomerId);
                $result = CustomersCaracts::where('user_id', '=', $_adCustomerId)->select('id','user_id','firstname','name','origin')->get();
                foreach ($result as $row){
                    $customercaracts['firstname']   = $row->firstname;
                    $customercaracts['name']        = $row->name;
                    $customercaracts['id']          = $row->id;
                    $customercaracts['user_id']          = $row->user_id;
                    $customercaracts['origin']      = $row->origin;
                }
            }
            return $customercaracts;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // getPrivateCaracts
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * getPrivateCaracts
         *
         * @param char or int $_adPrivate
         * @return Array
         */
        public static function getPrivateCaracts ($_adPrivate, $byName = false, $like = false) {
            $privatescaracts = [];
            if(is_bool($byName)) {
                //$case = $byName ? "name" : "rewrite_url";
                $case = $byName ? "denomination" : "rewrite_url";
            } elseif (is_string($byName)) {
                $case = $byName;
            }
            if ($_adPrivate) {
                if(is_numeric($_adPrivate)) {
                    $result = PrivatesCaracts::where('id', '=', $_adPrivate)->select('id','user_id','firstname','name','denomination','rewrite_url','status')->get();
                } elseif(is_string($_adPrivate)) {
                    if ($like) {
                        $result = PrivatesCaracts::where($case, 'LIKE', "%$_adPrivate%")->select('id','user_id','firstname','name','denomination','rewrite_url','status')->orderBy('name', 'ASC')->get();
                    } else {
                        $result = PrivatesCaracts::where($case, '=', $_adPrivate)->select('id','user_id','firstname','name','denomination','rewrite_url','status')->get();
                    }
                }
                foreach ($result as $row){
                    $privatescaracts['id']           = $row->id;
                    $privatescaracts['user_id']           = $row->user_id;
                    $privatescaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . ' / ' : '') . (!empty($row->denomination) ? $row->denomination . ' ' : '');
                    $privatescaracts['denomination']  = $row->denomination;
                    $privatescaracts['rewrite_url']  = $row->rewrite_url;
                    $privatescaracts['status']       = $row->status;
                }
            }
            return $privatescaracts;
        }

        /**
         * getPrivateCaractsByName
         *
         * @param  char $_adPrivateName
         * @return Array
         */
        public static function getPrivateCaractsByName ($_adPrivateName, $byName = true) {
            //debug('>>> getPrivateCaractsByName <<<');
            $privatescaracts = [];
            if(is_bool($byName)) {
                $case = $byName ? "name" : "rewrite_url";
            } elseif (is_string($byName)) {
                $case = $byName;
            }
            if ($_adPrivateName) {
                $result = PrivatesCaracts::where($case, '=', $_adPrivateName)->select('id','user_id','firstname','name','denomination','rewrite_url','status')->get();
                foreach ($result as $row){
                    $privatescaracts['id']           = $row->id;
                    $privatescaracts['user_id']           = $row->user_id;
                    $privatescaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . ' / ' : '') . (!empty($row->denomination) ? $row->denomination . ' ' : '');
                    $privatescaracts['denomination']  = $row->denomination;
                    $privatescaracts['rewrite_url']  = $row->rewrite_url;
                    $privatescaracts['status']       = $row->status;
                }
            }
            return $privatescaracts;
        }

        /**
         * getPrivateCaractsById
         *
         * @param  int $_adPrivateId
         * @return Array
         */
        public static function getPrivateCaractsById ($_adPrivateId) {
            //debug('>>> getPrivateCaractsById <<<');
            $privatescaracts = [];
            if ($_adPrivateId) {
                //$privatescaracts = PrivatesCaracts::find($_adPrivateId);
                $result = PrivatesCaracts::where('id', '=', $_adPrivateId)->select('id','user_id','firstname','name','denomination','address','address', 'address_more', 'zip', 'city', 'province', 'region', 'subregion', 'country_id', 'phone_1','rewrite_url','status')->get();
                foreach ($result as $row){
                    $privatescaracts['id']           = $row->id;
                    $privatescaracts['user_id']           = $row->user_id;
                    $privatescaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . ' / ' : '') . (!empty($row->denomination) ? $row->denomination . ' ' : '');
                    $privatescaracts['rewrite_url']  = $row->rewrite_url;

                    $address = '';
                    $address = !empty($row->address) ? $row->address : '';
                    $address .= !empty($row->address_more) ? (!empty($address) ? ', ' : '') . $row->address_more : '';
                    $address .= !empty($row->zip) ? (!empty($address) ? ', ' : '') . $row->zip : '';
                    $address .= !empty($row->city) ? (!empty($address) ? ', ' : '') . $row->city : '';
                    $address .= !empty($row->province) ? (!empty($address) ? ', ' : '') . $row->province : '';
                    $address .= !empty($row->region) ? (!empty($address) ? ', ' : '') . $row->region : '';
                    $address .= !empty($row->subregion) ? (!empty($address) ? ', ' : '') . $row->subregion : '';
                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id) : '';

                    $country_name = !empty($country) && array_key_exists('name', $country) && !empty($country['name']) ? $country['name'] : '';
                    $address .= !empty($country_name) ? (!empty($address) ? ', ' : '') . $country_name : '';

                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id, false) : '';
                    $country_code = !empty($country) && array_key_exists('code', $country) && !empty($country['code']) ? $country['code'] : '';

                    $privatescaracts['address']      = $address;
                    $privatescaracts['country_code'] = $country_code;

                    $privatescaracts['phone']        = $row->phone_1;
                    $privatescaracts['status']       = $row->status;
                }
            }
            return $privatescaracts;
        }

        /**
         * getPrivateCaractsByUserId
         *
         * @param  int $_adPrivateId
         * @return Array
         */
        public static function getPrivateCaractsByUserId ($_adPrivateUserId) {
            //debug('>>> getPrivateCaractsByUserId <<<');
            $privatescaracts = [];
            if ($_adPrivateUserId) {
                $result = PrivatesCaracts::where('user_id', '=', $_adPrivateUserId)->select('id','user_id','firstname','name','denomination','address','phone_1','rewrite_url','status')->get();
                foreach ($result as $row){
                    $privatescaracts['id']           = $row->id;
                    $privatescaracts['user_id']           = $row->user_id;
                    $privatescaracts['name']         = (!empty($row->firstname) ? $row->firstname . ' ' : '') . (!empty($row->name) ? $row->name . ' / ' : '') . (!empty($row->denomination) ? $row->denomination . ' ' : '');
                    $privatescaracts['rewrite_url']  = $row->rewrite_url;

                    $address = '';
                    $address = !empty($row->address) ? $row->address : '';
                    $address .= !empty($row->address_more) ? (!empty($address) ? ', ' : '') . $row->address_more : '';
                    $address .= !empty($row->zip) ? (!empty($address) ? ', ' : '') . $row->zip : '';
                    $address .= !empty($row->city) ? (!empty($address) ? ', ' : '') . $row->city : '';
                    $address .= !empty($row->province) ? (!empty($address) ? ', ' : '') . $row->province : '';
                    $address .= !empty($row->region) ? (!empty($address) ? ', ' : '') . $row->region : '';
                    $address .= !empty($row->subregion) ? (!empty($address) ? ', ' : '') . $row->subregion : '';
                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id) : '';

                    $country_name = !empty($country) && array_key_exists('name', $country) && !empty($country['name']) ? $country['name'] : '';
                    $address .= !empty($country_name) ? (!empty($address) ? ', ' : '') . $country_name : '';

                    $country = !empty($row->country_id) ? SearchController::getCountryById($row->country_id, false) : '';
                    $country_code = !empty($country) && array_key_exists('code', $country) && !empty($country['code']) ? $country['code'] : '';

                    $privatescaracts['address']      = $address;
                    $privatescaracts['country_code'] = $country_code;

                    $privatescaracts['phone']        = $row->phone_1;
                    $privatescaracts['status']       = $row->status;
                }
            }
            return $privatescaracts;
        }
    }