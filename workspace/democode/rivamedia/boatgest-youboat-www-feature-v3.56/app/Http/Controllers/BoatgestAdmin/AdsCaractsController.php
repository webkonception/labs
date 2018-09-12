<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Ads;
use App\AdsCaracts;
use App\User;
use App\DealersCaracts;
use App\Countries;

use Redirect;
//use Schema;
use Auth;
use File;
use Session;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchController as Search;

use Illuminate\Http\Request;
use App\Http\Requests\CreateAdsCaractsRequest;
use App\Http\Requests\UpdateAdsCaractsRequest;

//use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Controllers\ForsaleController;

use App\Http\Controllers\boatgestAdmin\StatisticsController as StatisticsController;

class AdsCaractsController extends Controller {

	/**
	 * Display a listing of adscaracts
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
		$currentLocale = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
		$country_code       = config('youboat.'. $currentLocale .'.country_code');

		//$AdsCaracts = $this->getAdsList($request);

		$total_ads = 0;
		$dealers = [];
		$user_type = Auth::user()->type;

		$WhereRaw = '(status = "active" OR status = "inactive" OR status = "nok") ';
		if('admin' == $user_type || 'commercial' == $user_type) {
			$dealers = User::where('type', 'dealer')
				->orderBy('username', 'asc')
				->lists('username','id');

			$result = AdsCaracts::whereNotNull('ad_dealer_name')
				->groupby('ad_dealer_name')
				->orderBy('ad_dealer_name', 'ASC')
				->lists('ad_dealer_name','ad_dealer_name');
			$dealers_names = json_decode(json_encode($result), true);
			$total_ads = AdsCaracts::whereRaw($WhereRaw)->count();
		} else if('private' == $user_type || 'dealer' == $user_type) {
			$dealercaracts_id = '';
			$dealercaracts = DealersCaracts::where('user_id', Auth::user()->id)
				->pluck('id')->all();
			if(count($dealercaracts) > 0) {
				$dealercaracts_id = $dealercaracts[0];
				$total_ads = AdsCaracts::where('dealerscaracts_id',$dealercaracts_id)->whereRaw($WhereRaw)->count();
			}
		}
		$result = AdsCaracts::whereNotNull('ad_title')
			->groupby('ad_title')
			->orderBy('ad_title', 'ASC')
			->lists('ad_title','ad_title');
		$ad_titles = json_decode(json_encode($result), true);

		$result = AdsCaracts::whereNotNull('ad_price')
			->whereRaw("ad_price <> ''")
			->groupby('ad_price')
			->orderBy('ad_price', 'ASC')
			->lists('ad_price','ad_price');
		$ad_prices = json_decode(json_encode($result), true);
		asort($ad_prices);

		//is_numeric($ad_price) ? trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $ad_price))) : trim(preg_replace('!\s+!', ' ', $ad_price));
		//$ad_prices = array_map("formatPrice", $ad_prices);
		$ad_prices = array_map("formatPriceCurrency", $ad_prices);

		$result = AdsCaracts::whereNotNull('updated_at')
			->whereRaw("updated_at <> ''")
			->groupby('updated_at')
			->orderBy('updated_at', 'ASC')
			//->lists('updated_at','updated_at');
			->get();
		//$ad_dates = json_decode(json_encode($result), true);
		foreach ($result as $key => $row){
			$date = date_create($row->updated_at);
			$date = date_format($date,"Y-m-d");
			$ad_dates[$date] = $date;
		}

		$result = AdsCaracts::whereNotNull('ad_year_built')
			->whereRaw("ad_year_built <> ''")
			->whereNotNull('ad_year_built')
			->groupby('ad_year_built')
			->orderBy('ad_year_built', 'ASC')
			//->lists('ad_year_built','ad_year_built');
			->get();
		//$ad_years_built = json_decode(json_encode($result), true);
		foreach ($result as $key => $row){
			if (is_numeric($row->ad_year_built) && $row->ad_year_built < 2030 && strlen($row->ad_year_built) > 2) {
				$ad_years_built[$row->ad_year_built][] = $row->ad_year_built;
			}
		}

		$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();
		//$status_states = getEnumValues('gateway_ads_details', 'status');
		//$status_states = ['active'=>'active','inactive'=>'inactive','removed'=>'removed'];;
		////

		$datasRequest 				= $request->all();
		$_adsTypeId                 = isset($datasRequest['adstypes_id']) ? $datasRequest['adstypes_id'] : null;
		$_categoryId                = isset($datasRequest['categories_ids']) ? $datasRequest['categories_ids'] : null;
		$_subcategoryId             = isset($datasRequest['subcategories_ids']) ? $datasRequest['subcategories_ids'] : null;
		$_manufacturersId           = isset($datasRequest['manufacturers_id']) ? $datasRequest['manufacturers_id'] : null;
		$_modelId           		= isset($datasRequest['models_id']) ? $datasRequest['models_id'] : null;
		$_manufacturersenginesId    = isset($datasRequest['manufacturersengines_id']) ? $datasRequest['manufacturersengines_id'] : null;
		$_modelenginesId    		= isset($datasRequest['modelsengines_id']) ? $datasRequest['modelsengines_id'] : null;

		$selltypes = $recovery_selltypes = Search::getAdsSellTypes();
		//$selltypes = $recovery_selltypes = Search::getGateWaySellType();
		/*$array = Search::getGateWaySellType($_adsTypeId, $_categoryId, $_subcategoryId,  $_manufacturersenginesId, $_modelId);
		foreach($array as $key => $val) {
			debug($key);
			debug($val);
			if($count) {
				$array[$val[0]] = trim($val[0]) . ' (' . $val['count'] .')';
			} else {
				$array[$val[0]] = trim($val[0]);
			}
		}
		$selltypes = $array;*/

		//$years_built = $recovery_selltypes = Search::getGateWayYearsBuilt();

		/*$array = Search::getGateWayAdsTypes();
		foreach($array as $key => $val) {
			$result = Search::getAdsType($val[0]);
			$array[$result['id']] = trans('adstypes.' . trim($result['rewrite_url'])) ;
		}
		$adstypes = $array;*/

		//$adstypes               = Search::getAdsTypes('active', false, []); // exclude 4,5,9,10 boat-engines, other, boat-trailers, pontoon-mooring
		$array = Search::getGateWayAdsTypes();
		foreach($array as $key => $val) {
			$result = Search::getAdsType($val[0]);
			$array[$result['id']] = trans('adstypes.' . trim($result['rewrite_url'])) ;
		}
		$adstypes = $array;

		//$categories    = Search::getCategory($_adsTypeId);
		$array = Search::getGateWayAdsCategories($_adsTypeId);
		foreach ($array as $key => $val) {
			$result = Search::getCategory($val[0]);
			$array[$result['id']] = trans('categories.' . trim($result['rewrite_url']));
		}
		$categories = $array;
		//$subcategories          = $_categoryId ? Search::getGateWayAdsSubcategories($_categoryId) : [];
		$subcategories    = [];
		$array = Search::getGateWayAdsSubcategories($_categoryId);
		foreach($array as $key => $val) {
			$result = Search::getSubcategory($val[0]);
			$array[$result['id']] = trans('subcategories.' . trim($result['rewrite_url']));
		}
		$subcategories = $array;

		//$manufacturers          = Search::getManufacturers();
		$array = Search::getGateWayAdsManufacturers($_adsTypeId, $_categoryId, $_subcategoryId);
		foreach($array as $key => $val) {
			$result = Search::getManufacturer($val[0]);
			$array[$result['id']] = ucwords(trim($result['name']));
		}
		$manufacturers = $array;

		//$models                 = $_manufacturersId ? Search::getModels($_manufacturersId) : [];
		$models                 = $_manufacturersId ? Search::getGateWayAdsModels($_manufacturersId) : [];
		$array = Search::getGateWayAdsModels($_manufacturersId);
		foreach($array as $key => $val) {
			$result = Search::getModel($val[0]);
			$array[$result['id']] = ucwords(trim($result['name']));
		}
		$models = $array;

		//$manufacturersengines = Search::getManufacturersEngines();
		//$modelsengines  = $_manufacturersenginesId ? Search::getModelsEngines($_manufacturersenginesId) : [];

		////

		$sell_type              = !empty($sell_type) ? $sell_type : (!empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : null);
		$adstype                = !empty($adstype) ? $adstype : (!empty($datasRequest['adstypes_id']) ? Search::getAdsTypeById($datasRequest['adstypes_id']) : null);
		$category               = !empty($category) ? $category : (!empty($datasRequest['categories_ids']) ? Search::getCategoryById($datasRequest['categories_ids']) : null);
		$subcategory            = !empty($subcategory) ? $subcategory : (!empty($datasRequest['subcategories_ids']) ? Search::getSubcategoryById($datasRequest['subcategories_ids']) : null);

		//$manufacturer           = !empty($manufacturer) ? $manufacturer : (!empty($datasRequest['manufacturers_id']) ? Search::getManufacturerById($datasRequest['manufacturers_id']) : null);
		$manufacturer           = !empty($manufacturer) ? $manufacturer : (!empty($datasRequest['manufacturers_id']) ? Search::getGateWayManufacturerByName($datasRequest['manufacturers_id'], true) : null);

		//@$manufacturerengine     = !empty($manufacturerengine) ? $manufacturerengine : (!empty($datasRequest['manufacturersengines_id']) ? Search::getManufacturerEngineById($datasRequest['manufacturersengines_id']) : null);

		$model                  = !empty($model) ? $model : (!empty($datasRequest['models_id']) ? Search::getModelById($datasRequest['models_id']) : null);
		//@$modelengine            = !empty($modelengine) ? $modelengine : (!empty($datasRequest['modelsengines_id']) ? Search::getModelEngineById($datasRequest['modelsengines_id']) : null);

		$boat_min_length        = !empty($boat_min_length) ? $boat_min_length : (!empty($datasRequest['min_length']) ? $datasRequest['min_length'] : null);
		$boat_max_length        = !empty($boat_max_length) ? $boat_max_length : (!empty($datasRequest['max_length']) ? $datasRequest['max_length'] : null);

		$boat_min_width         = !empty($boat_min_width) ? $boat_min_width : (!empty($datasRequest['min_width']) ? $datasRequest['min_width'] : null);
		$boat_max_width         = !empty($boat_max_width) ? $boat_max_width : (!empty($datasRequest['max_width']) ? $datasRequest['max_width'] : null);

		$min_year_built         = !empty($min_year_built) ? $min_year_built : (!empty($datasRequest['min_year_built']) ? $datasRequest['min_year_built'] : null);
		$max_year_built         = !empty($max_year_built) ? $max_year_built : (!empty($datasRequest['max_year_built']) ? $datasRequest['max_year_built'] : null);

		$min_ad_price           = !empty($min_ad_price) ? $min_ad_price : (!empty($datasRequest['min_ad_price']) ? $datasRequest['min_ad_price'] : null);
		$max_ad_price           = !empty($max_ad_price) ? $max_ad_price : (!empty($datasRequest['max_ad_price']) ? $datasRequest['max_ad_price'] : null);

		$min_engine_power = (!empty($arrayGetAdsList['min_engine_power']) ? $arrayGetAdsList['min_engine_power'] : null);
		$max_engine_power = (!empty($arrayGetAdsList['max_engine_power']) ? $arrayGetAdsList['max_engine_power'] : null);
		$type_engine_power = (!empty($arrayGetAdsList['type_engine_power']) ? $arrayGetAdsList['type_engine_power'] : null);

		$country                = !empty($country) ? $country : (!empty($datasRequest['countries_id']) ? Search::getCountry($datasRequest['countries_id']) : null);

		//$county_id              = !empty($county_id) ? $county_id : (!empty($datasRequest['county_id']) ? $datasRequest['county_id'] : null);

		$page                   = !empty($page) ? $page : (!empty($datasRequest['page']) ? $datasRequest['page'] : '1');
		$sort_by                = !empty($sort_by) ? $sort_by : (!empty($datasRequest['sort_by']) ? $datasRequest['sort_by'] : 'updated_at-desc');

		////

		$return = compact(
			//'AdsCaracts',
			'total_ads',
			'dealers', 'countries',
			'dealers_names','ad_titles', 'ad_prices', 'ad_dates', 'ad_years_built',
		////
			'selltypes',
			'adstypes','categories','subcategories',
			'manufacturers','models',
			'manufacturersengines','modelsengines',
		////
			'sell_type','adstype','category','subcategory',
			'manufacturer','model',
			'boat_min_length','boat_max_length',
			'boat_min_width','boat_max_width',
			'min_year_built','max_year_built',
			'min_ad_price','max_ad_price',
			'status_states',
			'country',
			'page','sort_by'
			,'user_type'
		);

		unset(
			$total_ads,
			$dealers, $countries,
			$dealers_names,$ad_titles, $ad_prices, $ad_dates, $ad_years_built,
			$selltypes,
			$adstypes,$categories,$subcategories,
			$manufacturers,$models,
			$manufacturersengines,$modelsengines,
			$sell_type,$adstype,$category,$subcategory,
			$manufacturer,$model,
			$boat_min_length,$boat_max_length,
			$boat_min_width,$boat_max_width,
			$min_year_built,$max_year_built,
			$min_ad_price,$max_ad_price,
			$country,
			$page,$sort_by,
			$user_type
		);

		return view(config('quickadmin.route') . '.adscaracts.index', $return);
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

			if('admin' == $user_type) {
				$WhereRaw = 'status <> "" ';
			} else {
				//$WhereRaw = 'status <> "removed" ';
				$WhereRaw = '(status = "active" OR status = "inactive" OR status = "nok") ';
			}

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
					//$ad_country_code = $countries_id;
					//$WhereRaw .= 'AND ' . 'countries_id = "' . $countries_id . '" ';
					$ad_country_code = 'uk';
					$getCountryById = Search::getCountryById($input['countries_id'], false);
					if(is_array($getCountryById) && array_key_exists('code', $getCountryById)) {
						$ad_country_code = mb_strtolower($getCountryById['code']);
					}
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

				//
				$WhereRaw .= "OR ";
				$WhereRaw .= "ad_type_cat_name LIKE '%$search_query%' ";
				//

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
								$id = !empty($adstype['id']) ? $adstype['id'] : null;
								$value = $id;
								$key_data = 'adstypes_id';
								$action = '=';
								break;
							case 'categories_name':
								$category = !empty($value) ? (is_numeric($value) ? json_decode(json_encode(Search::getCategory($value, true, true)), true) : json_decode(json_encode(Search::getCategory($value, true, true)), true)[0]) : null;
								$id = !empty($category['id']) ? $category['id'] : null;
								$value = $id;
								$key_data = 'categories_ids';
								$action = '=';
								break;
							case 'subcategories_name':
								$subcategory = !empty($value) ? (is_numeric($value) ? json_decode(json_encode(Search::getSubCategory($value, true, true)), true) : json_decode(json_encode(Search::getSubCategory($value, true, true)), true)[0]) : null;
								$id = !empty($subcategory['id']) ? $subcategory['id'] : null;
								$value = $id;
								$key_data = 'subcategories_ids';
								$action = '=';
								break;
							case 'manufacturers_name':
								$manufacturer = !empty($value) ? json_decode(json_encode(Search::getManufacturerByName($value, true, true)), true) : null;
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

			$currentLocale = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
			$countries_id = (!empty($input['countries_id']) ?
				Search::getCountry($input['countries_id'])['id'] :
				Search::getCountry(config('youboat.' . $currentLocale . '.country_code'))['id']);
			$county_id = !empty($input['county_id']) && '' != $input['county_id'] ? $input['county_id'] : null;

			$countries_code = !empty($input['countries_id']) ? Search::getCountryById($input['countries_id'], false)['code'] : 'uk';

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
		try {
			$input = $request->all();
			$dealerscaracts_id = '';
			$sell_types 	= getEnumValues('adscaracts', 'sell_type');
			$countries 		= Countries::orderBy("name", "asc")->pluck('name','code')->all();
			//$status 		= getEnumValues('gateway_ads_details', 'status');
			//$status = ['active'=>'active','inactive'=>'inactive','removed'=>'removed'];
			$status = ['active'=>'active','inactive'=>'inactive'];
			$getDefaults 	= ForsaleController::getDefaults($input);

			$user_type = Auth::user()->type;

			$user_id = !empty(old('user_id')) ? old('user_id') : null;
			if(empty($user_id) && !empty($input['user_id'])) {
				$user_id = empty($user_id) && !empty($input['user_id']);
			}

			if(empty($user_id) && Auth::user()->id && $user_type != 'admin' && $user_type != 'commercial') {
				$user_id = Auth::user()->id;
			}

			if(
				(!empty($user_id) && ('admin' == $user_type || 'commercial' == $user_type) || 'dealer' == $user_type)
			) {
				/*$usernames = User::leftJoin('dealerscaracts', 'users.id', '=', 'dealerscaracts.user_id')
					->whereNull('dealerscaracts.user_id')
					->where('users.id',$user_id)
					->orderBy('users.username', 'asc')
					->lists('users.username','users.id')
					->prepend('Please select', '');*/
				$usernames = User::where('type', 'dealer')
					//->where('status', 'active')
					->where('users.id',$user_id)
					->orderBy('username', 'asc')
					->lists('username','id');
			} else if('admin' == $user_type || 'commercial' == $user_type) {
				/*$usernames = User::leftJoin('dealerscaracts', 'users.id', '=', 'dealerscaracts.user_id')
					->whereNull('dealerscaracts.user_id')
					->where('users.type', '=', 'dealer')
					->orderBy('users.username', 'asc')
					->lists('users.username','users.id','users.status')
					->prepend('Please select', '');*/
				$usernames = User::where('type', 'dealer')
					->where('status', 'active')
					->orderBy('username', 'asc')
					->lists('username','id');
			} else if('admin' != $user_type || 'commercial' != $user_type ||
				('dealer' == $user_type && $user_id != Auth::user()->id ))
			{
				return redirect()->route(config('quickadmin.route') . '.adscaracts.index');
			}

			if(isset($usernames)) {
				$array = json_decode(json_encode($usernames), true);
				if (is_array($array) && isset($array)) {
					$usernames = $array;
				}
			} else {
				$usernames = [];
			}

			$country_id = '';
			$ad_dealer_name = '';

			if(!empty($user_id)) {
				$user = User::findOrFail($user_id);
				$user_type = $user->type;
				$user_caracts = [];
				switch ($user_type) {
					case 'admin':
						$usercaracts = [];
						break;
					case 'private':
						$usercaracts = PrivatesCaracts::where('user_id', $user_id)->get();
						break;
					case 'dealer':
						$usercaracts = DealersCaracts::where('user_id', $user_id)->get();
						break;
					case 'customer':
						$usercaracts = CustomersCaracts::where('user_id', $user_id)->get();
						break;
					case 'commercial':
						$usercaracts = CommercialsCaracts::where('user_id', $user_id)->get();
						break;
				}
				$array = json_decode(json_encode($usercaracts), true);
				//asort($array);
				if (!empty($array[0])) {
					$user_caracts = $array[0];
				}
				if(is_array($user_caracts) && array_key_exists('id', $user_caracts)) {
					$dealerscaracts_id = $user_caracts['id'];
					$ad_dealer_name = isset($user_caracts['denomination']) ? $user_caracts['denomination'] : '';
					if (empty($ad_dealer_name) && (isset($user_caracts['firstname']) ||  isset($user_caracts['name']))) {
						$ad_dealer_name = $user_caracts['name'] . (!empty($user_caracts['firstname']) ? ' ' . $user_caracts['firstname'] : '');
					}
					$ad_dealer_name = trim(ucwords(mb_strtolower($ad_dealer_name)));
					$country_id = array_key_exists('country_id', $user_caracts) && !empty($user_caracts['country_id']) ? $user_caracts['country_id'] : '';
				} else {
					$message = trans('ads_caracts.dealer_caracts_missing');
					$message .= '<br>' . htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.' . $user_type . 'scaracts.create', '<i class="fa fa-plus-circle fa-fw"></i>Complete my account', ['user_id'=>$user_id], ['class' => 'btn  btn-lg btn-danger']));
					return redirect()->back()->withErrors(['message' => $message]);
				}
			}

			if(empty($country_id) && !empty($input['countries_id'])) {
				$country_id = $input['countries_id'];
			} else if (empty($country_id)) {
				$country_id = 77; // uk by default
			}

			$ad_country_code = 'uk';
			$getCountryById = Search::getCountryById($country_id, false);
			if(is_array($getCountryById) && array_key_exists('code', $getCountryById)) {
				$ad_country_code = mb_strtolower($getCountryById['code']);
			}
			$from = ['gb'];
			$to = ['uk'];
			$ad_country_code = str_replace($from, $to, $ad_country_code);

			$ad_referrer 	= 'YB';

			$datas = compact(
				'user_caracts',
				'usernames',
				'user_id',
				//'ad_dealer_name',
				//'dealerscaracts_id',
				'ad_referrer',
				'ad_country_code',
				'sell_types',
				'country_id',
				'countries',
				'status'
			);

			if(!empty(old('manufacturers_id'))) {
				$getDefaults['models'] = Search::getModels(old('manufacturers_id'));
			}

			$getDefaults['manufacturersengines'] = '';
			$getDefaults['modelsengines'] = '';
			$return = $datas + $getDefaults;

			unset($usernames, $array, $sell_types, $country_id, $countries, $status,
				$ad_country_code,
				$ad_referrer, $user_caracts,
				//$dealerscaracts_id, $ad_dealer_name,
				$datas, $getDefaults);

			return view(config('quickadmin.route') . '.adscaracts.create', $return);

		} catch(\Exception $e) {
			//var_dump($e->getMessage());
			//die();
			return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
		}
	}

	/**
	 * Store a newly created adscaracts in storage.
	 *
     * @param CreateAdsCaractsRequest|Request $request
	 */
	public function store(CreateAdsCaractsRequest $request)
	{
		try {
			$request = $this->saveFiles($request);
			$input = $request->all();
			if((!isset($input["dealerscaracts_id"]) || !isset($input["ad_dealer_name"])) && isset($input["user_id"])) {
				$user_id = $input["user_id"];
				$user = User::findOrFail($user_id);
				$user_type = $user->type;
				$user_caracts = [];
				switch ($user_type) {
					case 'admin':
						$usercaracts = [];
						break;
					case 'private':
						$usercaracts = PrivatesCaracts::where('user_id', $user_id)->get();
						break;
					case 'dealer':
						$usercaracts = DealersCaracts::where('user_id', $user_id)->get();
						break;
					case 'customer':
						$usercaracts = CustomersCaracts::where('user_id', $user_id)->get();
						break;
					case 'commercial':
						$usercaracts = CommercialsCaracts::where('user_id', $user_id)->get();
						break;
				}
				$array = json_decode(json_encode($usercaracts), true);
				if (!empty($array[0])) {
					$user_caracts = $array[0];
				}
				if(is_array($user_caracts) && array_key_exists('id', $user_caracts)) {
					$dealerscaracts_id = $user_caracts['id'];
					$ad_dealer_name = isset($user_caracts['denomination']) ? $user_caracts['denomination'] : '';
					if (empty($ad_dealer_name) && (isset($user_caracts['firstname']) ||  isset($user_caracts['name']))) {
						$ad_dealer_name = $user_caracts['name'] . (!empty($user_caracts['firstname']) ? ' ' . $user_caracts['firstname'] : '');
					}
					$ad_dealer_name = trim(ucwords(mb_strtolower($ad_dealer_name)));

					$input["dealerscaracts_id"] = $dealerscaracts_id;
					$input["ad_dealer_name"] = $ad_dealer_name;
				} else {
					$message = trans('ads_caracts.dealer_caracts_missing');
					$message .= htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.' . $user_type . 'scaracts.create', '<i class="fa fa-plus-circle fa-fw"></i>Complete my account', ['user_id'=>$user_id], ['class' => 'btn btn-block btn-lg btn-success']));
					return redirect()->back()->withErrors(['message' => $message]);
				}
			}

			if(isset($input["dealerscaracts_id"]) && !empty($input["dealerscaracts_id"])) {
				$country_code = !empty($input['countries_id']) ? $input['countries_id'] : '';
				$getCountry = Search::getCountry($country_code);
				$country_id = '';
				$country_name = '';

				if(is_array($getCountry)) {
					if(array_key_exists('id', $getCountry)) {
						$country_id = !empty($country_code) ? $getCountry['id'] : null;
					}
					if(array_key_exists('name', $getCountry)) {
						$country_name = !empty($country_code) ? $getCountry['name'] : null;
					}
				}
				$input['countries_id'] = $country_id;
				$input['ad_location'] = $country_name;
				if(isset($input['old_ad_mooring_country']) && !empty($input['old_ad_mooring_country'])) {
					$input['ad_mooring_country'] = $input['old_ad_mooring_country'];
				} else {
					$input['ad_mooring_country'] = !empty($input['ad_mooring_country']) ? $input['ad_mooring_country'] : '';
				}

				//
				$ad_manufacturer_name = '';
				if(array_key_exists('manufacturers_id', $input)) {
					$getManufacturerById = Search::getManufacturerById($input['manufacturers_id']);
					if(array_key_exists('name', $getManufacturerById)) {
						$ad_manufacturer_name = $getManufacturerById['name'];
					}
				}
				$ad_model_name = '';
				if(array_key_exists('models_id', $input)) {
					$getModelById = Search::getModelById($input['models_id']);
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
				} else if(array_key_exists('upload_photos', $input)) {
					$ad_photos = $input['upload_photos'];
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

				$AdsCaracts = AdsCaracts::create($input);

				if ($AdsCaracts->save()) {
					$message = trans('ads_caracts.ad_successfully_created');
					Session::set('message.text',$message); //Session::flash
					Session::set('message.type', 'success'); //Session::flash
					//return redirect()->route(config('quickadmin.route') . '.adscaracts.index')->withMessage($message);
					return redirect()->route(config('quickadmin.route') . '.adscaracts.edit', ['id'=>$AdsCaracts->id])->withMessage($message);
				}
			} else {
				$message = trans('ads_caracts.dealer_caracts_missing');
				return redirect()->back()->withErrors(['message' => $message]);
			}
		} catch(\Exception $e) {
			//var_dump($e->getMessage());
			//die();
			return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
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
			$sell_types = getEnumValues('adscaracts', 'sell_type');
			$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();
			//$status = getEnumValues('gateway_ads_details', 'status');
			$status = ['active'=>'active','inactive'=>'inactive','removed'=>'removed'];
			//$status = ['active'=>'active','inactive'=>'inactive'];
			$getDefaults = ForsaleController::getDefaults($AdsCaracts);

			$country_id 	= !empty($AdsCaracts->countries_id) ? $AdsCaracts->countries_id : 77; // uk
			$getCountryById = Search::getCountryById($country_id, false);
			if(is_array($getCountryById) && array_key_exists('code', $getCountryById)) {
				$AdsCaracts->country_code = $getCountryById['code'];
			}
			$AdsCaracts->ad_photos_thumbs = '';

			if(empty($AdsCaracts->ad_title) && (!empty($AdsCaracts->manufacturers_id) || !empty($AdsCaracts->models_id))) {
				$manufacturerName = !empty($AdsCaracts->manufacturers_id) ? Search::getSomethingById('manufacturers', $AdsCaracts->manufacturers_id, 'name') : '';
				$modelName = !empty($AdsCaracts->models_id) ? Search::getSomethingById('models', $AdsCaracts->models_id, 'name') : '';

				if(isset($manufacturerName) && !empty($manufacturerName[0]) && array_key_exists('name', $manufacturerName[0])) {
					$manufacturerName = $manufacturerName[0]['name'];
				}
				if(isset($modelName) && !empty($modelName[0]) && array_key_exists('name', $modelName[0])) {
					$modelName = $modelName[0]['name'];
				}
				$AdsCaracts->ad_title = trim ($manufacturerName . ' ' . $modelName);
			}

			if(empty($AdsCaracts->ad_dealer_name) && !empty($AdsCaracts->dealerscaracts_id)) {
				$dealerDenomination = Search::getSomethingById('dealerscaracts', $AdsCaracts->dealerscaracts_id, 'denomination');
				if(isset($dealerDenomination) && !empty($dealerDenomination[0]) && array_key_exists('denomination', $dealerDenomination[0])) {
					$AdsCaracts->ad_dealer_name = $dealerDenomination[0]['denomination'];
				}
			}

			if(empty($AdsCaracts->ad_phones) && !empty($AdsCaracts->dealerscaracts_id)) {
				$dealerPhone = Search::getSomethingById('dealerscaracts', $AdsCaracts->dealerscaracts_id, 'phone_1');
				if(isset($dealerPhone) && !empty($dealerPhone[0]) && array_key_exists('phone_1', $dealerPhone[0])) {
					$AdsCaracts->ad_phones = $dealerPhone[0]['phone_1'];
				}
			}

			//$EffectiveUrl = '/' . $id;
			$currentLocale = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
			$website_youboat_url = config('youboat.' . $currentLocale . '.website_youboat_url');
			$EffectiveUrl = getEffectiveUrl(url($website_youboat_url . '/buy/type/manufacturer/model/' . $id));
			//$EffectiveUrl = '/' . $id;
			$chartTotalVisitorsAndPageViewsByPath = StatisticsController::chartTotalVisitorsAndPageViews($id, ['EffectiveUrl' => str_replace($website_youboat_url, '', $EffectiveUrl)]);
			$adEvents = StatisticsController::adStatsEvents($id);

			$datas = compact(
				'AdsCaracts',
				'sell_types',
				'country_id',
				'countries',
				'status',
				'EffectiveUrl',
				'chartTotalVisitorsAndPageViewsByPath',
				'adEvents'
			);
			$getDefaults['manufacturersengines'] = '';
			$getDefaults['modelsengines'] = '';
			$return = $datas + $getDefaults;
			/*if('edit' != $action) {
				return $return;
			} else {*/
				return view(config('quickadmin.route') . '.adscaracts.' . $action, $return);
			/*}*/
		} catch(\Exception $e) {
			//var_dump($e);
			//var_dump($e->getMessage());
			//die();
			return redirect()->back()->withErrors($e->getMessage());
		}
	}

	/**
	 * Show the specified bodcaracts.
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function show($id)
	{
		$action = 'show';
		$return = $this->edit($id, $action);
		return view(config('quickadmin.route') . '.adscaracts.' . $action, $return);
	}

	/**
	 * Update the specified adscaracts in storage.
     * @param UpdateAdsCaractsRequest|Request $request
     *
	 * @param  int  $id
	 */

	public function update($id, UpdateAdsCaractsRequest $request)
	{
		try {
			//session()->put('current_ad_id', $id);
			$AdsCaracts = AdsCaracts::findOrFail($id);
			//$request = $this->saveFiles($request);

			$input = $request->all();

			$country_code = !empty($input['countries_id']) ? $input['countries_id'] : '';
			$getCountry = Search::getCountry($country_code);
			$country_id = '';
			$country_name = '';

			if(is_array($getCountry)) {
				if(array_key_exists('id', $getCountry)) {
					$country_id = !empty($country_code) ? $getCountry['id'] : null;
				}
				if(array_key_exists('name', $getCountry)) {
					$country_name = !empty($country_code) ? $getCountry['name'] : null;
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
				if(array_key_exists('name', $getManufacturerById)) {
					$ad_manufacturer_name = $getManufacturerById['name'];
				}
			}
			$ad_model_name = '';
			if(array_key_exists('models_id', $input)) {
				$getModelById = Search::getModelById($input['models_id']);
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

	/**
	 * Remove the specified adscaracts from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		AdsCaracts::where('id', $id)->update(['status'=>'removed']);
		//AdsCaracts::destroy($id);
		return redirect()->route(config('quickadmin.route') . '.adscaracts.index');
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
			AdsCaracts::where('id', $toDelete)->update(['status'=>'removed']);
			//AdsCaracts::destroy($toDelete);
        } else {
			//AdsCaracts::whereNotNull('id')->update(['status'=>'removed']);
            //AdsCaracts::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route') . '.adscaracts.index');
    }

}
