<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchController;
use Redirect;
use Schema;
use DB;

use App\ScrappingAdsDetails;
use App\Gateway;
use App\AdsCaracts;
use App\Countries;

use App\Http\Requests\CreateGatewayRequest;
use App\Http\Requests\UpdateGatewaysRequest;
use Illuminate\Http\Request;

class GatewayController extends SearchController {

	public function index(Request $request)
    {
		\Debugbar::disable();

		ini_set('max_execution_time', 360); // Maximum execution time of each script, in seconds (I CHANGED THIS VALUE)
		ini_set('max_input_time', 120); // Maximum amount of time each script may spend parsing request data
		//ini_set('max_input_nesting_level', 64); // Maximum input variable nesting level
		ini_set('memory_limit', '512M'); // Maximum amount of memory a script may consume (128MB by default)
		//ini_set('memory_limit', '-1');

		set_time_limit (0);

		$input = $request->all();

		//$referrer = 'www.boatshop24.co.uk';
		//$referrer = 'www.seaside.fr';
		//$country_code = 'uk';

		$referrer = '';
		$country_code = '';

		$return = 'no dealer referrer setted';
		/*if(isset($input['referrer']) && !empty($input['referrer'])) {
			$referrer = $input['referrer'];
			$country_code = mb_strtolower(isset($input['country_code']) && !empty($input['country_code']) ? $input['country_code'] : 'uk');

			// set Ads's status to removed for $referrer
			AdsCaracts::where('status', 'active')->where('ad_referrer', $referrer)->update(['status' => 'removed']);

			// Run Gateway Ads script
			$return = $this->GatewayAds($referrer, $country_code);
		}*/
		$dealers_referrer = ScrappingAdsDetails::orderBy("ad_referrer", "asc")->groupby('ad_referrer')->pluck('ad_referrer','ad_referrer')->all();
		//$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();
		$websites = ['uk'=>'uk.youboat.com'];

		return view(config('quickadmin.route') . '.gateway.index', compact('return', 'dealers_referrer', 'websites'));
	}

	public function store(Request $request)
    {
		\Debugbar::disable();
		ini_set('max_execution_time', 360); // Maximum execution time of each script, in seconds (I CHANGED THIS VALUE)
		ini_set('max_input_time', 120); // Maximum amount of time each script may spend parsing request data
		//ini_set('max_input_nesting_level', 64); // Maximum input variable nesting level
		ini_set('memory_limit', '512M'); // Maximum amount of memory a script may consume (128MB by default)
		//ini_set('memory_limit', '-1');

		set_time_limit (0);

		$input = $request->all();

		//$referrer = 'www.boatshop24.co.uk';
		//$referrer = 'www.seaside.fr';
		//$country_code = 'uk';

		$referrer = '';
		$country_code = '';

		$return = 'no dealer referrer setted';
		if(isset($input['referrer']) && !empty($input['referrer'])) {
			$referrer = $input['referrer'];
			$country_code = mb_strtolower(isset($input['country_code']) && !empty($input['country_code']) ? $input['country_code'] : 'uk');

			// set Ads's status to removed for $referrer
			AdsCaracts::where('status', 'active')->where('ad_referrer', $referrer)->update(['status' => 'removed']);
			// Run Gateway Ads script
			$forceUpdate = true;
			$return = $this->GatewayAds($referrer, $country_code, $forceUpdate);
		}
		$dealers_referrer = ScrappingAdsDetails::orderBy("ad_referrer", "asc")->groupby('ad_referrer')->pluck('ad_referrer','ad_referrer')->all();
		//$countries = Countries::orderBy("name", "asc")->pluck('name','code')->all();
		$websites = ['uk'=>'uk.youboat.com'];

		return view(config('quickadmin.route') . '.gateway.index', compact('return', 'dealers_referrer', 'websites'));
	}

	// 00
	public function GatewayAds($referrer, $country_code, $forceUpdate = true)
	{
		$return = '';
		$return .= '<h3>GatewayAds</h3>';
		$return .= '<p><strong>' . $referrer . ' / ' . $country_code . '</strong></p>';
		// 01 récupère le liste des id des annonces
		$ScrappingAds=$this->ScrappingAds($referrer, $country_code);
		// 02 récupère le détail des annonces scrapping par id
		foreach($ScrappingAds as $key => $value) {

			//$ScrappingAdsDetails = $this->ScrappingAdsDetailsById($value);
			$ScrappingAdsDetails = $this->ScrappingAdsDetailsByAdRef($value);

			// 03 traitement des datas, formattage des données
			$GatewayFormatDatas = $this->GatewayFormatDatas($ScrappingAdsDetails);
			/*
			// 04 update dans gateway
			$GatewayUpdate = $this->GatewayUpdate($value, $GatewayFormatDatas);
			var_dump($GatewayUpdate);
			die();
			*/
			// 04 create dans gateway
			//$GatewayCreate = $this->GatewayCreate($value, $GatewayFormatDatas);
			//var_dump($GatewayCreate);
			// 04 create dans gateway
			//$GatewayUpdateOrCreate = $this->GatewayUpdateOrCreate(['id'=>$value], $GatewayFormatDatas, $forceUpdate);
			$GatewayUpdateOrCreate = $this->GatewayUpdateOrCreate(['ad_ref'=>$value], $GatewayFormatDatas, $forceUpdate);
			$return .= $GatewayUpdateOrCreate;
			$return .= '<br>';
			unset($ScrappingAdsDetails,$GatewayFormatDatas,$GatewayUpdateOrCreate);
		}
		unset($ScrappingAds,$key,$value);
		return $return;
	}

	// 01
	public function ScrappingAds($referrer, $country_code)
	{
		$scrapping_ads_details = ScrappingAdsDetails::
		where('ad_referrer', $referrer)
		->where('ad_country_code', $country_code)
		//->orderBy('id', 'asc')
		//->pluck('id')
		->orderBy('ad_ref', 'asc')
		->pluck('ad_ref')
		->all();
		return $scrapping_ads_details;
	}

	// 02
	public function ScrappingAdsDetailsById($id)
	{
		$scrapping_ads_details_by_id = ScrappingAdsDetails::find($id);

		return $scrapping_ads_details_by_id;
	}
	// 02
	public function ScrappingAdsDetailsByAdRef($ad_ref)
	{
		$scrapping_ads_details_by_ad_ref = ScrappingAdsDetails::where('ad_ref', $ad_ref)->first();

		return $scrapping_ads_details_by_ad_ref;
	}

	// 03
	public function GatewayFormatDatas($scrapping_ads_details)
	{
		$ad_referrer = $scrapping_ads_details->ad_referrer ?: '';
		$ad_country_code = $scrapping_ads_details->ad_country_code ?: '';
		$ad_pageUrl = $scrapping_ads_details->ad_pageUrl ?: '';

		$ad_type = '';
		$ad_category = '';
		$ad_id = '';

		$ad_subcategory_url = $scrapping_ads_details->ad_subcategory_url ?: '';
		if (!empty($ad_subcategory_url)) {
			list($nothing, $ad_type, $ad_category) = explode("/", $ad_subcategory_url);
		}

		$ad_type_cat_url = '';
		if (empty($ad_type)) {
			$ad_type_cat_url = $scrapping_ads_details->ad_type_cat_url ?: '';
			if (!empty($ad_type_cat_url)) {
				list($nothing, $ad_type) = explode("/", $ad_type_cat_url);
			}
		}

		$ad_pageUrl_cleaned = preg_replace("@^https?://[^/]+/@", "", $ad_pageUrl);
		//echo'<pre>';var_dump($ad_pageUrl_cleaned);echo'</pre>';die();
		if (!empty($ad_pageUrl_cleaned)) {
			if (empty($ad_type)) {
				list($ad_type, $ad_title_rewrite_url, $ad_id) = explode("/", $ad_pageUrl_cleaned);
			} else {
				list($nothing, $ad_title_rewrite_url, $ad_id) = explode("/", $ad_pageUrl_cleaned);
			}
		}

		if(empty($ad_type)) {

		}
		if (!empty($ad_type)) {
			$arrayFrom = ['Boat-Moorings','commercials','motorboats','motorboat','sailing','sailboat','Small-boats','bateau-moteur','voiliers','voilier','location'];
			$arrayReplace = ['pontoon-mooring','commercial-boats','power-boats','power-boats','sailing-boats','sailing-boats','ribs','power-boats','sailing-boats','sailing-boats','boat-rental'];
			$ad_type = str_replace($arrayFrom, $arrayReplace, $ad_type);
		}

		if(empty($ad_category)) {

		}
		if (!empty($ad_category)) {
			//trailer
			if (preg_match("/trailer/i", $ad_category) || preg_match("/boattrailer/i", $ad_category) || preg_match("/boat-trailer/i", $ad_category)) {
				if ('power-boats' == $ad_type || 'sailing-boats' == $ad_type ) {
					$ad_category = $ad_type;
					$ad_type = 'boat-trailers';
				}
			}
			//jet-skis
			if (preg_match("/jet-ski/i", $ad_category) || preg_match("/jetski/i", $ad_category)) {
				if ('power-boats' == $ad_type || 'ribs' == $ad_type) {
					$ad_category = 'jet-skis';
					$ad_type = 'pwc';
				}
			}
			//trawler
			if (preg_match("/trawler/i", $ad_category)) {
				if ('power-boats' == $ad_type) {
					$ad_category = 'trawler';
				}
			}
			//house-boats
			if (preg_match("/houseboat/i", $ad_category) || preg_match("/house-boat/i", $ad_category)) {
				if ('power-boats' == $ad_type || 'ribs' == $ad_type) {
					$ad_category = '';
					$ad_type = 'house-boats';
				}
			}
			//bowriders
			if (preg_match("/bowriders/i", $ad_category)) {
				$ad_type = 'power-boats';
				$ad_category = 'day-cruiser';
				$ad_subcategory = 'bow-rider';
			}
			//other
			if (preg_match("/Center-Consoles/i", $ad_category)) {
				if ('power-boats' == $ad_type || 'ribs' == $ad_type) {
					$ad_type = 'other';
				}
			}
			//-motorboats$
			if (preg_match("/-motorboats$/i", $ad_category) || preg_match("/-motor-boats$/i", $ad_category)) {
				if ('power-boats' == $ad_type) {
					$arrayFrom = ['-motorboats','-motor-boats'];
					$ad_category = str_replace($arrayFrom, '', $ad_category);
				}
			}
			//-sailboats$
			if (preg_match("/-sailing-boats$/i", $ad_category) || preg_match("/-sailboats$/i", $ad_category) || preg_match("/-sailingboats$/i", $ad_category)) {
				if ('sailing-boats' == $ad_type) {
					$arrayFrom = ['-sailing-boats','-sailboats','-sailingboats'];
					$ad_category = str_replace($arrayFrom, '', $ad_category);
				}
			}
			//dive
			if (preg_match("/dive/i", $ad_category)) {
				if ('ribs' == $ad_type) {
					$ad_category = 'diving-boats';
				}
			}
			//^rib-
			if (preg_match("/^rib-/i", $ad_category)) {
				if ('ribs' == $ad_type) {
					$arrayFrom = ['rib-'];
					$ad_category = str_replace($arrayFrom, 'ribs-', $ad_category);
				}
			}
			//^taxi-
			if (preg_match("/^taxi-/i", $ad_category)) {
				if ('commercial-boats' == $ad_type) {
					$arrayFrom = ['taxi-'];
					$ad_category = str_replace($arrayFrom, 'taxis-', $ad_category);
				}
			}
			//-boat$
			if (preg_match("/-boat$/i", $ad_category)) {
				$arrayFrom = ['-boat'];
				$ad_category = str_replace($arrayFrom, '-boats', $ad_category);
			}
			//-for-Sale$
			if (preg_match("/-for-sale$/i", $ad_category)) {
				$arrayFrom = ['-for-sale'];
				$ad_category = str_replace($arrayFrom, '', $ad_category);
			}

			$ad_type = strtolower($ad_type);
			$ad_category = strtolower($ad_category);
		}

		$ad_title = $scrapping_ads_details->ad_title ?: '';
		$ad_ref = $scrapping_ads_details->ad_ref ?: '';

		$ad_type_cat_name = $scrapping_ads_details->ad_type_cat_name ?: '';
		if(!empty($ad_type_cat_name)) {
			$arrayFrom = ['Bateau Moteur', 'Bateaux Moteur', 'Voilier', 'Voiliers', 'Location'];
			$arrayReplace = ['Power Boats', 'Power Boats', 'Sailing Boats', 'Sailing Boats', 'Boat Rental'];
			$ad_type_cat_name = str_replace($arrayFrom, $arrayReplace, $ad_type_cat_name);
		}

		$ad_manufacturer_name = $scrapping_ads_details->ad_manufacturer_name ?: '';
		if(empty($ad_manufacturer_name)) {

		}
		if (!empty($ad_manufacturer_name)) {
			$arrayFrom = [' Motor Boats',' Sailing Boats',' RIBs',' Commercial Boats',' Small boats'];
			$ad_manufacturer_name = str_replace($arrayFrom, '', $ad_manufacturer_name);
		}

		$ad_manufacturer_url = $scrapping_ads_details->ad_manufacturer_url ?: str_slug(trim($ad_manufacturer_name));

		$ad_model_name = $scrapping_ads_details->ad_model_name ?: '';
		if(empty($ad_model_name)) {

		}
		if (!empty($ad_model_name)) {
			//$ad_model_name = $scrapping_ads_details->ad_model_name;
			list($ad_model_name) = explode(";", $ad_model_name);
			if (!empty($ad_model_name)) {
				/*if (preg_match("/^- /i", $ad_model_name) || preg_match("/^/ /i", $ad_model_name)) {
					$arrayFrom = ['- ', '/ '];
					$ad_model_name = str_replace($arrayFrom, '', $ad_model_name);
				}*/

				if (preg_match("/^- /i", $ad_model_name)) {
					$ad_model_name = preg_replace('/^- /i', '', $ad_model_name);
				}
				if (preg_match("/^\/ /i", $ad_model_name)) {
					$ad_model_name = preg_replace('/^\/ /i', '', $ad_model_name);
				}

				$arrayFrom = ['-', '  -', '-  ', '/', '  /', '/  '];
				$arrayReplace = [' - ', ' -', '- ', ' / ', ' /', '/ '];
				$ad_model_name = str_replace($arrayFrom, '', $ad_model_name);
			}
		}
		$ad_model_url = $scrapping_ads_details->ad_model_url ?: str_slug(trim($ad_model_name));

		$ad_price = $scrapping_ads_details->ad_price ?: '';
		//$ad_price = is_numeric($ad_price) ? floatval(preg_replace('/[^\d.]/', '', $ad_price)) : $ad_price;
		if(!is_numeric($ad_price) && !empty($ad_price)) {
			if(preg_match('/\s/',$ad_price)) {
				list($price_currency, $price) = explode(" ", $ad_price);
				$ad_price = str_replace(',', '', $price);
			} else {
				$ad_price = str_replace(',', '', $ad_price);
			}
		}
		$ad_price_descr = $scrapping_ads_details->ad_price_descr ?: '';

		$ad_location = '';
		$ad_country = '';
		$ad_region = '';
		$ad_county = '';
		$ad_city = '';

		$ad_location = preg_replace('/\s*,\s*/', ',', $scrapping_ads_details->ad_location) ?: '';
		if (!empty($ad_location)) {
			//$ad_location = $scrapping_ads_details->ad_location;
			$explode = explode(",", $ad_location);
			if (count($explode) > 0) {
				//list($region,$county,$country) = explode(";", $ad_location;
				if (is_array($explode)) {
					$ad_country = array_pop($explode);
				}
				if (is_array($explode)) {
					$ad_county = array_pop($explode);
				}
				if (is_array($explode)) {
					$ad_region = array_pop($explode);
				}
			}
		}

		$ad_mooring_country = $scrapping_ads_details->ad_mooring_country ?: '';

		$ad_dealer_name = $scrapping_ads_details->ad_dealer_name ?: '';

		$dealerscaracts_id = '';
		$dealerscaracts = [];
		if (!empty($ad_dealer_name)) {
			//$dealerscaracts_id = !empty($this->getDealerCaracts ($ad_dealer_name, true)['id']) ? $this->getDealerCaracts ($ad_dealer_name, true)['id'] : '';
			//$dealerscaracts = $this->getDealerCaracts($ad_dealer_name, true);
			$dealerscaracts = $this->getDealerCaracts($ad_dealer_name, 'name', true);
			if(empty($dealerscaracts)) {
				$dealerscaracts = $this->getDealerCaracts($ad_dealer_name, 'denomination', true);
			}
		}
		$ad_dealer_url = '';
		if (!empty($dealerscaracts) && is_array($dealerscaracts)) {
			$ad_dealer_name = !empty($dealerscaracts['denomination']) ? $dealerscaracts['denomination'] : (!empty($dealerscaracts['name']) ? $dealerscaracts['name'] : '');
			$dealerscaracts_id = !empty($dealerscaracts['id']) ? $dealerscaracts['id'] : '';
			$ad_dealer_url = !empty($dealerscaracts['rewrite_url']) ? $dealerscaracts['rewrite_url'] : (!empty($ad_dealer_name) ? str_slug(trim($ad_dealer_name)) : '');
		}

		$ad_phones = $scrapping_ads_details->ad_phones ?: '';

		$ad_sale = $scrapping_ads_details->ad_sale ?: '';
		$ad_sale_type_condition = $scrapping_ads_details->ad_sale_type_condition ?: '';
		$ad_sale_type_condition = strtolower($ad_sale_type_condition);
		$ad_sales_status = $scrapping_ads_details->ad_sales_status ?: '';
		$ad_sales_status = strtolower($ad_sales_status);

		$ad_year_built = $scrapping_ads_details->ad_year_built ?: '';

		$ad_width = $scrapping_ads_details->ad_width ?: '';
		$ad_width_meter = '';
		if (!empty($ad_width)) {
			list($ad_width_meter) = explode("/", $ad_width);
			$ad_width_meter = str_replace(' m', '', $ad_width_meter);
		}

		$ad_length = $scrapping_ads_details->ad_length ?: '';
		$ad_length_meter = '';
		if (!empty($ad_length)) {
			list($ad_length_meter) = explode("/", $ad_length);
			$ad_length_meter = str_replace(' m', '', $ad_length_meter);
		}

		$ad_description_caracts = $scrapping_ads_details->ad_description_caracts ?: '';
		$ad_description_full = $scrapping_ads_details->ad_description_full ?: '';
		$ad_description_caracts_values = $scrapping_ads_details->ad_description_caracts_values ?: '';
		$ad_description_caracts_labels = $scrapping_ads_details->ad_description_caracts_labels ?: '';

		$ad_description = str_replace($ad_description_caracts, '', $ad_description_full); //@TOTO may be need to be removed
		$ad_description = preg_replace('/^About/','', $ad_description);
		$ad_description_caracts_values = str_replace('; ', ';', $ad_description_caracts_values) . ';';
		$ad_description_caracts_labels = str_replace(':; ', ';', $ad_description_caracts_labels) . ';';

		$ad_specifications_caracts = $scrapping_ads_details->ad_specifications_caracts ?: '';
		$ad_specifications_full = $scrapping_ads_details->ad_specifications_full ?: '';
		$ad_specifications = str_replace($ad_specifications_caracts, '', $ad_specifications_full);//@TOTO may be need to be removed

		$ad_specifications_caracts_values = $scrapping_ads_details->ad_specifications_caracts_values ?: '';

		$ad_specifications_caracts_labels = '';
		//$ad_specifications_caracts_values = $scrapping_ads_details->ad_specifications_caracts_values;
		if (!empty($ad_specifications_caracts)) {
			//$ad_specifications_caracts = $scrapping_ads_details->ad_specifications_caracts;
			if (!empty($ad_specifications_caracts_values)) {
				$ad_specifications_caracts_values_array = explode(";", $ad_specifications_caracts_values);
			}
			if (is_array($ad_specifications_caracts_values_array)) {
				$ad_specifications_caracts_labels = $ad_specifications_caracts;
				foreach($ad_specifications_caracts_values_array as $key => $value) {
					$ad_specifications_caracts_labels = str_replace($value, ';', $ad_specifications_caracts_labels);
				}
				$ad_specifications_caracts_labels = str_replace('; ', ';', $ad_specifications_caracts_labels);
				$ad_specifications_caracts_values = str_replace('; ', ';', $ad_specifications_caracts_values) . ';';
				unset($ad_specifications_caracts_values_array,$key,$value);
			}
		}

		$ad_features_caracts = $scrapping_ads_details->ad_features_caracts ?: '';
		$ad_features_full = $scrapping_ads_details->ad_features_full ?: '';
		$ad_features = str_replace($ad_features_caracts, '', $ad_features_full);//@TOTO may be need to be removed

		$ad_features_caracts_categories = $scrapping_ads_details->ad_features_caracts_categories ?: '';

		$ad_features_caracts_values = '';
		if (!empty($ad_features_caracts)) {
			//$ad_features_caracts = $scrapping_ads_details->ad_features_caracts;
			if (!empty($ad_features_caracts_categories)) {
				$ad_features_caracts_categories_array = explode(";", $ad_features_caracts_categories);
			}
			if (is_array($ad_features_caracts_categories_array)) {
				$ad_features_caracts_values = $ad_features_caracts;
				foreach($ad_features_caracts_categories_array as $key => $value) {
					$ad_features_caracts_values = str_replace($value . ' ', '', $ad_features_caracts_values);
				}
				$ad_features_caracts_values .= ';';
				$ad_features_caracts_categories = $ad_features_caracts_categories . ';';
				unset($ad_features_caracts_categories_array,$key,$value);
			}
		}

		$ad_photo = $scrapping_ads_details->ad_photo ?: '';
		$ad_photos_thumbs = $scrapping_ads_details->ad_photos_thumbs ?: '';
		$ad_photos = $scrapping_ads_details->ad_photos ?: '';

		$ad_propulsion = $scrapping_ads_details->ad_propulsion ?: '';
		$ad_nb_engines = $scrapping_ads_details->ad_nb_engines ?: '';

		// @TODO
		// @TODO supprimer doubles appels

		$adstypes_id = '';
		if (!empty($ad_type)) {
			//$adstypes_id = !empty($this->getAdsType ($ad_type, true)['id']) ? $this->getAdsType ($ad_type, true)['id'] : '';
			/*$adstypes = $this->getAdsType ($ad_type, false, true);
			$adstypes = json_decode(json_encode($adstypes), true);*/
			$adstypes = $this->getAdsType ($ad_type);
		}
		if (isset($adstypes) && is_array($adstypes) && !empty($adstypes['id'])) {
			$adstypes_id = $adstypes['id'];
		}

		$categories_ids = '';
		if (!empty($ad_category)) {
			//$categories_ids = !empty($this->getCategoryByName($ad_category)['id']) ? $this->getCategoryByName($ad_category)['id'] : '';
			$categories = $this->getCategoryByName($ad_category);
		}
		if (isset($categories) && is_array($categories) && !empty($categories['id'])) {
			$categories_ids = $categories['id'];
			if(empty($adstypes_id) && !empty($categories['adstypes_id'])) {
				$adstypes_id = $categories['adstypes_id'];
			}
		}

		$subcategories_ids = '';
		if (!empty($ad_subcategory)) {
			//$subcategories_ids = !empty($this->getSubcategoryByName($ad_subcategory)['id']) ? $this->getSubcategoryByName($ad_subcategory)['id'] : '';
			$subcategories = $this->getSubcategoryByName($ad_subcategory);
		}
		if (isset($subcategories) && is_array($subcategories) && !empty($subcategories['id'])) {
			$subcategories_ids = $subcategories['id'];
			if(empty($categories_ids) && !empty($subcategories['categories_ids'])) {
				$categories_ids = $subcategories['categories_ids'];
				if(empty($adstypes_id) && !empty($categories_ids)) {
					$adstypes = $this->getAdsTypeByCategoryId($categories_ids);
					$adstypes_id = is_array($adstypes) && !empty($adstypes['id']) ? $adstypes['id'] : '';
				}
			}
		}

		$manufacturers_id = '';
		$models_id = '';
		$manufacturersengines_id = '';
		$modelsengines_id = '';
		if (preg_match("/engines/i", $ad_type)) {
			/*
            'manufacturersengines_id',
            'modelsengines_id',
            */
			if (!empty($ad_manufacturer_name)) {
				//$manufacturersengines_id = !empty($this->getManufacturerEngine($ad_manufacturer_name, true, true)['id']) ? $this->getManufacturerEngine($ad_manufacturer_name, true, true)['id'] : '';
				$manufacturersengines = $this->getManufacturerEngine($ad_manufacturer_name, true, false);
			} else if (!empty($ad_manufacturer_url)) {
				list($type_name, $ad_manufacturer_name) = explode("/", $ad_manufacturer_url);
				$manufacturersengines = !empty($ad_manufacturer_name) ? $this->getManufacturerEngine($ad_manufacturer_name, true, false) : '';
			} else if (!empty($ad_title) && !empty($ad_model_name)) {
				$ad_manufacturer_name = str_replace($ad_model_name, '', $ad_title);
				$manufacturersengines = !empty($ad_manufacturer_name) ? $this->getManufacturerEngine($ad_manufacturer_name, true, false) : '';
			}
			if (isset($manufacturersengines) && is_array($manufacturersengines) && !empty($manufacturersengines['id'])) {
				$manufacturersengines_id = $manufacturersengines['id'];
			}

			if (!empty($ad_model_name)) {
				//$modelsengines_id = !empty($this->getModelEngine($ad_model_name, true, true)['id']) ? $this->getModelEngine($ad_model_name, true, true)['id'] : '';
				$modelsengines = $this->getModelEngine($ad_model_name, true, false);
			} else if (!empty($ad_model_url)) {
				list($type_name, $ad_model_name) = explode("/", $ad_model_url);
				$modelsengines = !empty($ad_model_name) ? $this->getModelEngine($ad_model_name, true, false) : '';
			}
			if (isset($modelsengines) && is_array($modelsengines) && !empty($modelsengines['id'])) {
				$modelsengines_id = $modelsengines['id'];
			}
		} else {
			/*
            'manufacturers_id', =>$ad_manufacturer_name
            'models_id', => ad_model_name
            */
			if (!empty($ad_manufacturer_name)) {
				//$manufacturers_id = !empty($this->getManufacturer($ad_manufacturer_name, true, true)['id']) ? $this->getManufacturerByName($ad_manufacturer_name, true, true)['id'] : '';
				$manufacturers = $this->getManufacturer($ad_manufacturer_name, true, false);
			} else if (!empty($ad_manufacturer_url)) {
				list($type_name, $ad_manufacturer_name) = explode("/", $ad_manufacturer_url);
				$manufacturers = !empty($ad_manufacturer_name) ? $this->getManufacturer($ad_manufacturer_name, true, false) : '';
			} else if (!empty($ad_title) && !empty($ad_model_name)) {
				$ad_manufacturer_name = str_replace($ad_model_name, '', $ad_title);
				$manufacturers = !empty($ad_manufacturer_name) ? $this->getManufacturer($ad_manufacturer_name, true, false) : '';
			}
			if (isset($manufacturers) && is_array($manufacturers) && !empty($manufacturers['id'])) {
				$manufacturers_id = $manufacturers['id'];
			}

			if (!empty($ad_model_name)) {
				//$models_id = !empty($this->getModel($ad_model_name, true, true)['id']) ? $this->getModel($ad_model_name, true, true)['id'] : '';
				$models = $this->getModel($ad_model_name, true, false);
			} else if (!empty($ad_model_url)) {
				list($type_name, $ad_model_name) = explode("/", $ad_model_url);
				$models = !empty($ad_model_name) ? $this->getModel($ad_model_name, true, false) : '';
			}
			if (isset($models) && is_array($models) && !empty($models['id'])) {
				$models_id = $models['id'];
			}
		}

		$countries_id = '';
		if (!empty($ad_country)) {
			//$countries_id = isset($this->getCountryByName($ad_country)['id']) ? $this->getCountryByName($ad_country)['id'] : '';
			$ad_country = preg_replace('/Royaume Uni/', 'United Kingdom', $ad_country);
			$countries = $this->getCountryByName($ad_country);
		}
		if (!empty($countries) && is_array($countries) && !empty($countries['id'])) {
			$countries_id = $countries['id'];
		}

		if(empty($countries_id) && !empty($ad_mooring_country)) {
			$ad_mooring_country = preg_replace('/Royaume Uni/', 'United Kingdom', $ad_mooring_country);
			$countries = $this->getCountryByName($ad_mooring_country);
			if (!empty($countries) && is_array($countries) && !empty($countries['id'])) {
				$countries_id = $countries['id'];
			}
		}

		$province = '';
		$region = $ad_region;
		$subregion = $ad_county;
		$city = '';
		$zip = '';

		$sell_type = 'used';
		if (preg_match("/new/i", $ad_sale_type_condition) || preg_match("/used/i", $ad_sale_type_condition) || preg_match("/damaged/i", $ad_sale_type_condition)) {
			$sell_type = $ad_sale_type_condition;
		}

		$start_date = '';
		$end_date = '';
		$status = '';
		//SELECT * FROM scrapping_ads_details	 WHERE `ad_sales_status` != 'Sold' AND `ad_sales_status` != 'under offer' AND `ad_sales_status` != 'Delete'
		if (preg_match("/delete/i", $ad_sales_status) ||
			preg_match("/sold/i", $ad_sales_status) ||
			preg_match("/removed/i", $ad_sales_status) ||
			preg_match("/inactive/i", $ad_sales_status
		)) {
			$status = 'inactive';
		} else if ('' == $ad_sales_status ||
			preg_match("/for sale/i", $ad_sales_status) ||
			preg_match("/under offer/i", $ad_sales_status) ||
			preg_match("/available/i", $ad_sales_status) ||
			preg_match("/active/i", $ad_sales_status
		)) {
			$status = 'active';
		} else {
			$status = $ad_sales_status;
		}
		if(empty($ad_ref)) {
			$status = 'removed';
		}

		$gateway_format_datas = [];
		//$gateway_format_datas['id'] = $scrapping_ads_details->id;
		$gateway_format_datas['ad_referrer'] = $ad_referrer;
		$gateway_format_datas['ad_country_code'] = $ad_country_code;

		$gateway_format_datas['ad_title'] = $ad_title;
		//$gateway_format_datas['ad_ref'] = $ad_id . '|' . $ad_ref;
		$gateway_format_datas['ad_ref'] = $ad_ref;

		$gateway_format_datas['ad_type_cat_url'] = $ad_type_cat_url;
		$gateway_format_datas['ad_type'] = $ad_type;

		$gateway_format_datas['ad_type_cat_name'] = $ad_type_cat_name;
		$gateway_format_datas['ad_category'] = $ad_category;

		$gateway_format_datas['ad_manufacturer_name'] = $ad_manufacturer_name;
		$gateway_format_datas['ad_manufacturer_url'] = $ad_manufacturer_url;

		$gateway_format_datas['ad_model_name'] = $ad_model_name;
		$gateway_format_datas['ad_model_url'] = $ad_model_url;

		$gateway_format_datas['ad_price'] = $ad_price;
		$gateway_format_datas['ad_price_descr'] = $ad_price_descr;

		//
		$gateway_format_datas['ad_location'] = $ad_location;
		$gateway_format_datas['ad_mooring_country'] = $ad_mooring_country;
		//
		$gateway_format_datas['ad_country'] = $ad_country;
		//
		$gateway_format_datas['ad_region'] = $ad_region;
		//
		$gateway_format_datas['ad_county'] = $ad_county;

		$gateway_format_datas['ad_dealer_name'] = $ad_dealer_name;
		$gateway_format_datas['ad_dealer_url'] = $ad_dealer_url;

		$gateway_format_datas['ad_phones'] = $ad_phones;

		$gateway_format_datas['ad_sale'] = $ad_sale;
		//
		$gateway_format_datas['ad_sale_type_condition'] = $ad_sale_type_condition;
		//
		$gateway_format_datas['ad_sales_status'] = $ad_sales_status;

		$gateway_format_datas['ad_year_built'] = $ad_year_built;

		//
		$gateway_format_datas['ad_width'] = $ad_width;
		//
		$gateway_format_datas['ad_length'] = $ad_length;

		$gateway_format_datas['ad_width_meter'] = $ad_width_meter;
		$gateway_format_datas['ad_length_meter'] = $ad_length_meter;

		$gateway_format_datas['ad_description'] = $ad_description;
		$gateway_format_datas['ad_description_caracts_labels'] = $ad_description_caracts_labels;
		$gateway_format_datas['ad_description_caracts_values'] = $ad_description_caracts_values;

		$gateway_format_datas['ad_specifications'] = $ad_specifications;
		$gateway_format_datas['ad_specifications_caracts_labels'] = $ad_specifications_caracts_labels;
		$gateway_format_datas['ad_specifications_caracts_values'] = $ad_specifications_caracts_values;

		$gateway_format_datas['ad_features'] = $ad_features;
		$gateway_format_datas['ad_features_caracts_categories'] = $ad_features_caracts_categories;
		$gateway_format_datas['ad_features_caracts_values'] = $ad_features_caracts_values;

		$gateway_format_datas['ad_photo'] = $ad_photo;
		//
		$gateway_format_datas['ad_photos_thumbs'] = $ad_photos_thumbs;
		$gateway_format_datas['ad_photos'] = $ad_photos;

		$gateway_format_datas['ad_propulsion'] = $ad_propulsion;
		$gateway_format_datas['ad_nb_engines'] = $ad_nb_engines;

		$gateway_format_datas['ad_pageUrl'] = $ad_pageUrl;

		// @TODO
		$gateway_format_datas['dealerscaracts_id'] = $dealerscaracts_id;
		$gateway_format_datas['adstypes_id'] = $adstypes_id;
		$gateway_format_datas['categories_ids'] = $categories_ids;
		//
		$gateway_format_datas['subcategories_ids'] = $subcategories_ids;
		$gateway_format_datas['manufacturers_id'] = $manufacturers_id;
		$gateway_format_datas['models_id'] = $models_id;
		$gateway_format_datas['manufacturersengines_id'] = $manufacturersengines_id;
		$gateway_format_datas['modelsengines_id'] = $modelsengines_id;

		$gateway_format_datas['countries_id'] = $countries_id;
		//
		$gateway_format_datas['province'] = $province;
		$gateway_format_datas['region'] = $region;
		$gateway_format_datas['subregion'] = $subregion;
		//
		$gateway_format_datas['city'] = $city;
		//
		$gateway_format_datas['zip'] = $zip;

		$gateway_format_datas['sell_type'] = $sell_type;

		$gateway_format_datas['start_date'] = $start_date;
		$gateway_format_datas['end_date'] = $end_date;
		$gateway_format_datas['status'] = $status;

		unset(
			$ad_referrer,
			$ad_country_code,
			$ad_title,
			$ad_id,
			$ad_ref,
			$ad_type_cat_url,
			$ad_type,
			$ad_type_cat_name,
			$ad_category,
			$ad_manufacturer_name,
			$ad_manufacturer_url,
			$ad_model_name,
			$ad_model_url,
			$ad_price,
			$ad_price_descr,
			$ad_location,
			$ad_mooring_country,
			$ad_country,
			$ad_region,
			$ad_county,
			$ad_dealer_name,
			$ad_dealer_url,
			$ad_phones,
			$ad_sale,
			$ad_sale_type_condition,
			$ad_sales_status,
			$ad_year_built,
			$ad_width,
			$ad_length,
			$ad_width_meter,
			$ad_length_meter,
			$ad_description,
			$ad_description_caracts_labels,
			$ad_description_caracts_values,
			$ad_specifications,
			$ad_specifications_caracts_labels,
			$ad_specifications_caracts_values,
			$ad_features,
			$ad_features_caracts_categories,
			$ad_features_caracts_values,
			$ad_photo,
			$ad_photos_thumbs,
			$ad_photos,
			$ad_propulsion,
			$ad_nb_engines,
			$ad_pageUrl,
			$dealerscaracts_id,
			$adstypes_id,
			$categories_ids,
			$subcategories_ids,
			$manufacturers_id,
			$models_id,
			$manufacturersengines_id,
			$modelsengines_id,
			$countries_id,
			$province,
			$region,
			$subregion,
			$city,
			$zip,
			$sell_type,
			$start_date,
			$end_date,
			$status
		);

		return $gateway_format_datas;
	}

	// 04
	public function GatewayUpdateOrCreate(array $attributes, array $gateway_format_datas = array(), $forceUpdate = false)
	{
		//DB::disableQueryLog();
		//$gateway_ads_details = Gateway::find($attributes['id']);
		$gateway_ads_details = Gateway::where('ad_ref', $attributes['ad_ref'])->first();
		//$gateway_ads_details = Gateway::firstOrNew($attributes);
		if ($gateway_ads_details) {
			$attributes['id'] = $gateway_ads_details->id;
			$return = 'Gateway Update for [' . $attributes['id'] . '] [' . $attributes['ad_ref'] . ']';
			// record exists
			//$gateway_ads_details->update($gateway_format_datas);
			$return .= ' already exists';
			if($forceUpdate) {
				$return = $this->GatewayUpdate($attributes, $gateway_format_datas);
			}
		} else {
			//$gateway_ads_details->fill($gateway_format_datas);
			$return = 'Gateway Create for [' . $attributes['ad_ref'] . ']';
			$gateway_ads_details = Gateway::create($gateway_format_datas);

			if ($gateway_ads_details->save()) {
				$return .= ' was successful';
			} else {
				$return .= ' failed';
			}
		}

		//return $gateway_ads_details;
		return $return;
	}

	// 05
	public function GatewayCreate(array $attributes, array $gateway_format_datas = array())
	{
		$gateway_ads_details = Gateway::create($gateway_format_datas);
		//var_dump($gateway_ads_details);

		$return = 'GatewayCreate for [' . $attributes['id'] . ']';
		if ($gateway_ads_details->save()) {
			$return .= ' was successful';
		} else {
			$return .= ' failed';
		}
		//return $gateway_ads_details;
		return $return;
	}

	// 06
	public function GatewayUpdate(array $attributes, array $gateway_format_datas = array())
	{
		//$gateway_ads_details = Gateway::findOrFail($id);
		$gateway_ads_details = Gateway::findOrFail($attributes['id']);

		$return = 'GatewayUpdate for [' . $attributes['id'] . ']';
		if ($gateway_ads_details->update($gateway_format_datas)) {
			$return .= ' was successful';
		} else {
			$return .= ' failed';
		}
		return $return;
	}

	// 07
	public function details($id)
	{
		//$gateway_details = Gateway::all();
		$gateway_details = Gateway::
			where('id', $id)
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
				'ad_photos_thumbs',
				'ad_photos',

				'ad_propulsion',
				'ad_nb_engines',

				'ad_pageUrl',

				// @TODO

				'dealerscaracts_id',
				'adstypes_id',
				'categories_ids',
				'subcategories_ids',

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
			->orderBy('id', 'asc')
			->get();

		return view(config('quickadmin.route') . '.gateway.details', compact('gateway_details'));
	}

}
