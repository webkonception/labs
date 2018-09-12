<?php
    $pricing_currency = (null !== config('youboat.' . $country_code . '.currency')) ? config('youboat.' . $country_code . '.currency') : trans('pricing.currency');
    $_countryCode = config('youboat.' . $country_code . '.country_code') ?: 'GB';
    $locale = Search::getCountryLocaleCode($_countryCode);
    setlocale(LC_MONETARY, $locale);

    if (is_array($datasRequest) && count($datasRequest) > 0) {
        $ad_updated_at             = isset($ad_updated_at) && !empty($ad_updated_at) ? $ad_updated_at : (!empty($datasRequest['updated_at']) ? $datasRequest['updated_at'] : null);
        $ci_firstname              = isset($ci_firstname) && !empty($ci_firstname) ? ucfirst(mb_strtolower($ci_firstname)) : (!empty($datasRequest['ci_firstname']) ? ucfirst(mb_strtolower($datasRequest['ci_firstname'])) : null);
        $ci_last_name              = isset($ci_last_name) && !empty($ci_last_name) ? mb_strtoupper($ci_last_name) : (!empty($datasRequest['ci_last_name']) ? mb_strtoupper($datasRequest['ci_last_name']) : null);

        //
        $ci_full_name = !empty($ci_firstname) ? $ci_firstname : '';
        $ci_full_name .= !empty($ci_full_name) && !empty($ci_last_name) ? ' ' . $ci_last_name : $ci_last_name;
        //
        $ci_email                  = isset($ci_email) && !empty($ci_email) ? $ci_email : (!empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null);
        $ci_phone                  = isset($ci_phone) && !empty($ci_phone) ? $ci_phone : (!empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null);
        $ci_description            = isset($ci_description) && !empty($ci_description) ? $ci_description : (!empty($datasRequest['ci_description']) ? $datasRequest['ci_description'] : null);
        $ci_country                = isset($ci_country) && !empty($ci_country) ? $ci_country : (!empty($datasRequest['ci_countries_id']) ? Search::getCountry ($datasRequest['ci_countries_id']) : Search::getCountry(config('youboat.'. $country_code .'.country_code'))['id']);
        $ad_location               = isset($ad_location) && !empty($ad_location) ? $ad_location : (!empty($datasRequest['ad_location']) ? $datasRequest['ad_location'] : '');
        $ad_mooring                = isset($ad_mooring) && !empty($ad_mooring) ? $ad_mooring : (!empty($datasRequest['ad_mooring_country']) ? $datasRequest['ad_mooring_country'] : '');

        $ad_id                     = isset($datasRequest['id']) && !empty($datasRequest['id']) ? $datasRequest['id'] : '';
        $ad_url                    = Request::fullUrl();

        $ad_referrer = '';
        if (!empty($datasRequest['ad_referrer'])) {
            $ad_referrer = $datasRequest['ad_referrer'];
        }

        $ad_type = '';
        $ad_type_rewrite_url = '';
        $ad_results_type_url = '';
        //if (isset($datasRequest['adstypes_id']) && $datasRequest['adstypes_id'] != 0) {
        if (!empty($datasRequest['adstypes_id'])) {
            $ad_type = Search::getAdsTypeById($datasRequest['adstypes_id']);
        }
        $is_engine =  false;
        $adstype = $ad_type;
        if(is_array($ad_type) && !empty($ad_type['name'])) {
            $ad_type_rewrite_url = $ad_type['rewrite_url'];
            $ad_type = $ad_type['name'];
            //$ad_type_rewrite_url = str_slug($ad_type, $separator = '-');
            $ad_results_type_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url));
            if (preg_match("/engines/i", $ad_type)) {
                $is_engine = true;
            }
        }

        //debug('$ad_results_type_url');debug($ad_results_type_url);
        $category = '';
        //if (isset($datasRequest['categories_ids']) && $datasRequest['categories_ids'] != 0) {
        if (!empty($datasRequest['categories_ids'])) {
            $category = Search::getCategoryById($datasRequest['categories_ids']);
        }
        $ad_category = '';
        $ad_category_rewrite_url = '';
        $ad_results_category_url = '';
        if(is_array($category) && !empty($category['name'])) {
            $ad_category_rewrite_url = $category['rewrite_url'];
            $ad_category = $category['name'];
            $ad_results_category_url = $ad_results_type_url . '/' . trans('routes.' . str_replace('-', '_', $ad_category_rewrite_url));
        }

        $subcategory = '';
        //if (isset($datasRequest['subcategories_ids']) && $datasRequest['subcategories_ids'] != 0) {
        if (!empty($datasRequest['subcategories_ids'])) {
            $subcategory = Search::getSubcategoryById($datasRequest['subcategories_ids']);
        }
        $ad_subcategory = '';
        $ad_subcategory_rewrite_url = '';
        $ad_results_subcategory_url = '';
        if(is_array($subcategory) && !empty($subcategory['name'])) {
            $ad_subcategory_rewrite_url = $subcategory['rewrite_url'];
            $ad_subcategory = $subcategory['name'];
            $ad_results_subcategory_url = $ad_results_category_url . '/' . trans('routes.' . str_replace('-', '_', $ad_subcategory_rewrite_url));
        }
        $subcategory = $ad_subcategory;

        $manufacturer = '';
        //if (isset($datasRequest['manufacturers_id']) && $datasRequest['manufacturers_id'] != 0) {
        if (!empty($routeParameters['manufacturers']) && empty($datasRequest['manufacturers_id'])) {
            $ad_manufacturer_rewrite_url = $routeParameters['manufacturers'];
            $manufacturer = Search::getManufacturerByName($ad_manufacturer_rewrite_url);
        } else if (!empty($datasRequest['manufacturers_id'])) {
            $manufacturer = Search::getManufacturerById($datasRequest['manufacturers_id']);
            //} else if (isset($datasRequest['manufacturersengines_id']) && $datasRequest['manufacturersengines_id'] != 0) {
        }  else if ($is_engine && !empty($routeParameters['manufacturers'])) {
            $ad_manufacturer_rewrite_url = $routeParameters['manufacturers'];
            $manufacturer = Search::getManufacturerEngineByName($ad_manufacturer_rewrite_url);
            $manufacturerengine = $manufacturer;
        } else if (!empty($datasRequest['manufacturersengines_id'])) {
            $manufacturer = Search::getManufacturerEngineById($datasRequest['manufacturersengines_id']);
            $manufacturerengine = $manufacturer;
        }

        $ad_manufacturer = '';
        $ad_manufacturer_rewrite_url = '';
        $ad_results_manufacturer_url = '';
        if(is_array($manufacturer) && !empty($manufacturer['name'])) {
            $ad_manufacturer_rewrite_url = $manufacturer['rewrite_url'];
            $ad_manufacturer = $manufacturer['name'];
            $ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer') . '/' . $ad_manufacturer_rewrite_url;
        } else if (!empty($datasRequest['ad_manufacturer_name'])) {
            $manufacturer['rewrite_url'] = $ad_manufacturer_rewrite_url = str_slug($datasRequest['ad_manufacturer_name']);
            $manufacturer['name'] = $ad_manufacturer = $datasRequest['ad_manufacturer_name'];
            $ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer') . '/' . $ad_manufacturer_rewrite_url;
        }

        $model = '';
        //if (isset($datasRequest['models_id']) && $datasRequest['models_id'] != 0) {
        if (!empty($routeParameters['models']) && empty($datasRequest['models_id']) && !empty($manufacturer['id'])) {
            $ad_model_rewrite_url = $routeParameters['models'];
            //$model = Search::getModel($ad_model_rewrite_url, true);
            $model = Search::getModelByName($manufacturer['id'], $ad_model_rewrite_url, true);
            /*if(empty($model['rewrite_url'])) {
                $model['rewrite_url'] = $ad_model_rewrite_url;
            }*/

        } else if (!empty($datasRequest['models_id'])) {
            $model = Search::getModelById($datasRequest['models_id']);
            //} else if (isset($datasRequest['modelsengines_id']) && $datasRequest['modelsengines_id'] != 0) {
        } else if (!empty($datasRequest['modelsengines_id'])) {
            $model = Search::getModelEngineById($datasRequest['modelsengines_id']);
            $modelengine = $model;
        }

        $ad_model = '';
        $ad_model_rewrite_url = '';
        $ad_results_model_url = '';
        if(is_array($model) && !empty($model['name'])) {
            $ad_model_rewrite_url = $model['rewrite_url'];
            $ad_model = $model['name'];
            $ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model') . '/' . $ad_manufacturer_rewrite_url . '/' . $ad_model_rewrite_url;
        } else if (!empty($datasRequest['ad_model_name'])) {
            $ad_model_rewrite_url = str_slug($datasRequest['ad_model_name']);
            $ad_model = $datasRequest['ad_model_name'];
            $ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model') . '/' . $ad_manufacturer_rewrite_url . '/' . $ad_model_rewrite_url;
            $model['name'] = $datasRequest['ad_model_name'];
            $model['rewrite_url'] = $ad_model_rewrite_url;
        }
        $map = false;
        $tab_zoom = [6,7,7,10,14,14];
        $zoom = $tab_zoom[0];

        if(empty($ad_mooring)) {
            $ad_mooring = $ad_location;
        } else if(strlen($ad_mooring) < strlen($ad_location)) {
            $ad_mooring = $ad_location;
        }

        $ad_country_code = $country_code;

        $ci_full_address = '';
        if(!empty($datasRequest['ci_zip'])) {
            $ci_zip = $datasRequest['ci_zip'];
            $ci_full_address .= str_replace(['N/A'], [''], $ci_zip) . ', ';
        }
        if(!empty($datasRequest['ci_city'])) {
            $ci_city = $datasRequest['ci_city'];
            $ci_city                   = mb_strtoupper($ci_city);
            $ci_full_address .= str_replace(['N/A'], [''], $ci_city) . ', ';
        }
        if(!empty($datasRequest['ci_countries_id'])) {
            $getCountryById = Search::getCountry($datasRequest['ci_countries_id'], true);
            $ci_country_name = array_key_exists('name', $getCountryById) && !empty($getCountryById['name']) ? $getCountryById['name'] : '';
            if (!empty($ci_country_name)) {
                $ci_full_address .= $ci_country_name . ', ';
            }
        }
        $ci_full_address = str_replace([", Unknown"], [''], $ci_full_address);
        $ci_full_address = preg_replace("/, $/i", '', $ci_full_address);
        $ci_full_address = preg_replace("/,$/", '', $ci_full_address);

        if(empty($ad_mooring)) {
            $ad_mooring = '';
            if(!empty($datasRequest['countries_id'])) {
                $getCountryById = Search::getCountryById($datasRequest['countries_id']);
                $country_name = array_key_exists('name', $getCountryById) && !empty($getCountryById['name']) ? $getCountryById['name'] : '';
                if (!empty($country_name)) {
                    $ad_mooring .= $country_name . ', ';
                    $map = true;
                    $zoom = $tab_zoom[0];
                }
                $getCountryByIdCode = Search::getCountryById($datasRequest['countries_id'], false);
                $ad_country_code = array_key_exists('code', $getCountryByIdCode) && !empty($getCountryByIdCode['code']) ? $getCountryByIdCode['code'] : '';
            }

            if(!empty($datasRequest['province'])) {
                $ad_mooring .= str_replace(['N/A'], [''], $datasRequest['province']) . ', ';
                $map = true;
                $zoom = $tab_zoom[2];
            }
            if(!empty($datasRequest['region'])) {
                $ad_mooring .= str_replace(['N/A'], [''], $datasRequest['region']) . ', ';
                $map = true;
                $zoom = $tab_zoom[3];
            }
            if(!empty($datasRequest['subregion'])) {
                $ad_mooring .= str_replace(['N/A'], [''], $datasRequest['subregion']) . ', ';
                $map = true;
                $zoom = $tab_zoom[4];
            }
            if(!empty($datasRequest['city'])) {
                $ad_mooring .= str_replace(['N/A'], [''], $datasRequest['city']) . ', ';
                $map = true;
                $zoom = $tab_zoom[5];
            }
            $ad_mooring = str_replace([", Unknown"], [''], $ad_mooring);
            $ad_mooring = preg_replace("/, $/i", '', $ad_mooring);
            $ad_mooring = preg_replace("/,$/", '', $ad_mooring);
        } else {
            $index = count(explode(',', $ad_mooring))-1;
            if($index> count($tab_zoom)-1) {
                $index = count($tab_zoom)-1;
            }
            $zoom = $tab_zoom[$index];
            if($index == 0 && !in_array($ad_mooring, $countries->toArray())) {
                $zoom = end($tab_zoom);
            }
        }
        $ad_mooring = preg_replace('/,/', ', ', $ad_mooring);
        $ad_mooring = preg_replace('!\s+!', ' ', $ad_mooring);

        if ('uk' == $ad_country_code) {
            $ad_mooring = preg_replace('/Royaume Uni/i', 'United Kingdom', $ad_mooring);
        }

        if(!empty($ad_mooring)) {
            $map = true;
            $map_address = $ad_mooring;
        }

        $ad_title = '';
        $ad_title_page = '';
        if (!empty($ad_manufacturer) && !empty($ad_model)) {
            $ad_title =  title_case($ad_manufacturer . ' ' . $ad_model);
            $ad_title_page =  '<strong>' . title_case($ad_manufacturer) . '</strong> ' . '<span>' . title_case($ad_model) . '</span>';
        } else if (!empty($datasRequest['ad_title'])) {
            $ad_title =  title_case($datasRequest['ad_title']);
            $ad_title_page =  '<strong>' . title_case($datasRequest['ad_title']) . '</strong> ';
        } else if (!empty($ad_manufacturer) || !empty($ad_model)) {
            if (!empty($ad_manufacturer)) {
                $ad_title =  title_case($ad_manufacturer);
                $ad_title_page =  '<strong>' . title_case($ad_manufacturer) . '</strong> ';
            }
            if (!empty($ad_model)) {
                $ad_title =  title_case($ad_model);
                $ad_title_page =  '<span>' . title_case($ad_model) . '</span>';
            }
        }
        if (empty($ad_manufacturer) && empty($ad_model)) {
            $ad_manufacturer = $ad_title;
        }

        $ad_description = '';
        if (!empty($datasRequest['ad_description'])) {
            $ad_description = $datasRequest['ad_description'];
            //$ad_description = nl2br($ad_description);
            //$ad_description = preg_replace("/\. /i", '.<br><br>', $ad_description);
            //$ad_description = preg_replace("/\: /i", ':<br>', $ad_description);
            $ad_description = preg_replace("/\s-/i", '<br>-', $ad_description);
        }
        $ad_description_caracts_labels = '';
        if (!empty($datasRequest['ad_description_caracts_labels'])) {
            $ad_description_caracts_labels = explode(';', str_replace('; ', ';', preg_replace("/;$/i", '', $datasRequest['ad_description_caracts_labels'])));
        }
        $ad_description_caracts_values = '';
        if (!empty($datasRequest['ad_description_caracts_values'])) {
            $ad_description_caracts_values = explode(';', str_replace('; ', ';', preg_replace("/;$/i", '', $datasRequest['ad_description_caracts_values'])));
        }

        $ad_specifications = '';
        if (!empty($datasRequest['ad_specifications'])) {
            $ad_specifications = preg_replace("/^Specifications/i", '', $datasRequest['ad_specifications']);
        }
        $ad_specifications_caracts_labels = '';
        if (!empty($datasRequest['ad_specifications_caracts_labels'])) {
            $ad_specifications_caracts_labels = explode(';', str_replace('; ', ';', preg_replace("/;$/i", '', $datasRequest['ad_specifications_caracts_labels'])));
        }
        $ad_specifications_caracts_values = '';
        if (!empty($datasRequest['ad_specifications_caracts_values'])) {
            $ad_specifications_caracts_values = explode(';', str_replace('; ', ';', preg_replace("/;$/i", '', $datasRequest['ad_specifications_caracts_values'])));
        }

        $ad_features = '';
        if (!empty($datasRequest['ad_features'])) {
            $ad_features = preg_replace("/^Features/i", '', $datasRequest['ad_features']);
        }
        $ad_features_caracts_categories = '';
        if (!empty($datasRequest['ad_features_caracts_categories'])) {
            $ad_features_caracts_categories = explode(';', str_replace('; ', ';', preg_replace("/;$/i", '', $datasRequest['ad_features_caracts_categories'])));
        }
        $ad_features_caracts_values = '';
        if (!empty($datasRequest['ad_features_caracts_values'])) {
            $ad_features_caracts_values = explode(';', str_replace('; ', ';', preg_replace("/;$/i", '', $datasRequest['ad_features_caracts_values'])));
        }

        $sell_type = '';
        if (!empty($datasRequest['sell_type'])) {
            $sell_type = $datasRequest['sell_type'];
        }

        $ad_img_src = '';
        if (!empty($datasRequest['ad_photo'])) {
            $ad_img_src = $datasRequest['ad_photo'];
            //} else if (isset($datasRequest['id']) && $datasRequest['id'] != 0) {
        } else if (!empty($datasRequest['id'])) {
            $array = Search::getSomethingById('gateway_ads_details', $datasRequest['id'], 'ad_photos');
            if(is_array($array) && count($array) >0) {
                $ad_img_src = getFirstElement(array_get($array[0], 'ad_photos'));
            }
        }

        $ad_cost = '';
        $ad_cost_meta = '';
        if (!empty($datasRequest['ad_price'])) {
            $ad_price = $datasRequest['ad_price'];
            //$ad_cost = trim(preg_replace('!\s+!', ' ', money_format('%.2n', $ad_price))); // with two decimal
            //$ad_cost = is_numeric($ad_price) ? trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $ad_price))) : trim(preg_replace('!\s+!', ' ', $ad_price));
            $ad_cost = is_numeric($ad_price) ? formatPriceCurrency($ad_price, $datasRequest['countries_id']) : trim(preg_replace('!\s+!', ' ', $ad_price));
            $budget = is_numeric($ad_price) ? formatPriceCurrency($ad_price, $datasRequest['countries_id'], true) : trim(preg_replace('!\s+!', ' ', $ad_price));
            $ad_cost_meta = $ad_budget = strip_tags($ad_cost);
        }
        if (!empty($ad_cost) && !empty($datasRequest['ad_price_descr'])) {

            $ad_cost .= ' ' . '<span class="label label-info">' . trim($datasRequest['ad_price_descr']) . '</span>';
            $ad_cost_meta .= ' ' . trim($datasRequest['ad_price_descr']);
        }

        $ad_url = '';
        if (
                isset($datasRequest['id']) &&
                !empty($ad_results_type_url) &&
                !empty($ad_manufacturer_rewrite_url) &&
                !empty($ad_model_rewrite_url)
        ) {
            $ad_url = trans_route($currentLocale, 'routes.buy') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url)) . '/' . $ad_manufacturer_rewrite_url . '/' . $ad_model_rewrite_url . '/' . $datasRequest['id'];
        } else if (
                isset($datasRequest['id']) &&
                !empty($ad_results_type_url) &&
                !empty($ad_manufacturer_rewrite_url) &&
                isset($ad_model_rewrite_url) && empty($ad_model_rewrite_url)
        ) {
            //@TODO : need to check here
            $ad_url = trans_route($currentLocale, 'routes.buy') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url)) . '/' . $ad_manufacturer_rewrite_url . '/' . $ad_model_rewrite_url . '/' . $datasRequest['id'];
        } else if (
                isset($datasRequest['id']) &&
                !empty($ad_results_type_url) &&
                !empty($ad_model_rewrite_url)
        ) {
            $ad_url = trans_route($currentLocale, 'routes.buy') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url)) . '/' . str_slug($ad_title, $separator = '-') . '/' . $datasRequest['id'];
        } else if (
                isset($datasRequest['id']) &&
                !empty($ad_type_rewrite_url) &&
                !empty($ad_title)
        ) {
            $ad_url = trans_route($currentLocale, 'routes.buy') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url)) . '/' . str_slug($ad_title, $separator = '-') . '/' . $datasRequest['id'];
        }

        $ad_img_src = '';
        if (!empty($datasRequest['ad_photo'])) {
            $ad_img_src = $datasRequest['ad_photo'];
            //} else if (isset($datasRequest['id']) && $datasRequest['id'] != 0) {
        } else if (!empty($datasRequest['id'])) {
            $array = Search::getSomethingById('gateway_ads_details', $datasRequest['id'], 'ad_photos');
            if(is_array($array) && count($array) >0) {
                $ad_img_src = getFirstElement(array_get($array[0], 'ad_photos'));
            }
        }
        /*
        $ad_photos_thumbs = '';
        if (!empty($datasRequest['ad_photos_thumbs'])) {
            $ad_photos_thumbs = explode(';', str_replace('; ', ';', preg_replace("/;$/i", '', $datasRequest['ad_photos_thumbs'])));
        }
        */

        $ad_photos = '';
        if (!empty($datasRequest['ad_photos'])) {
            $ad_photos = $datasRequest['ad_photos'];
            $ad_photos = preg_replace("/;$/i", '', $ad_photos);
            $ad_photos = str_replace('; ', ';',  $ad_photos);
            $ad_photos = explode(';', $ad_photos);
            //array_shift($ad_photos);
        }

        $ad_phones = '';
        if (!empty($datasRequest['ad_phones'])) {
            $ad_phones = explode(';', str_replace('; ', ';', preg_replace("/;$/i", '', $datasRequest['ad_phones'])));
        }

        $ad_year_built = '';
        if (!empty($datasRequest['ad_year_built'])) {
            $ad_year_built = $datasRequest['ad_year_built'];
        }

        $ad_width_meter = '';
        if (!empty($datasRequest['ad_width_meter'])) {
            $ad_width_meter = $datasRequest['ad_width_meter'];
        }

        $ad_length_meter = '';
        if (!empty($datasRequest['ad_length_meter'])) {
            $ad_length_meter = $datasRequest['ad_length_meter'];
        }

        $ad_premium_listing = false;
    }

    $featuredImage = '';
    if (!empty($ad_img_src)) {
        if(preg_match("/^(http|https):\/\//i", $ad_img_src)) {
            $ad_img_params = ['ad_id'=>$ad_id, 'ad_title'=>$ad_title, 'image_name'=>'photo-0'];
            $referrer = preg_match("/^(http|https):\/\//i", $ad_img_src) ? '' : 'http://' . $ad_referrer;
            $featured_url_image_ext = url_image_ext($referrer, $ad_img_src, 'photos/' . $country_code . '/', $ad_img_params);
        } else {
            $featured_url_image_ext = $ad_img_src;
        }
        $featured_url_image = '';
        if($featured_url_image_ext) {
            $featured_url_image = preg_replace("/^\/\//", '/', thumbnail($featured_url_image_ext, "100%", "100%", true, false, true, 100));
        }

        if (!empty($featured_url_image)) {
            $featuredImage = '<a href="' . $featured_url_image . '" data-gallery class="media-box thumbnail">' . image($featured_url_image, '', []) . '</a>';
        }
    }
    $additionalImages = '';
    if (isset($ad_photos) && is_array($ad_photos)) {
        foreach($ad_photos as $key => $photo) {
            if(preg_match("/^(http|https):\/\//i", $photo)) {
                $ad_img_params = ['ad_id'=>$ad_id, 'ad_title'=>$ad_title, 'image_name'=>'photo-' . $key];
                $referrer = preg_match("/^(http|https):\/\//i", $photo) ? '' : 'http://' . $ad_referrer;
                $url_image_ext = url_image_ext($referrer, $photo, 'photos/' . $country_code . '/', $ad_img_params);
            } else {
                $url_image_ext = $photo;
            }
            $url_image = '';
            if($url_image_ext) {
                $url_image = thumbnail(preg_replace("/^\//", '', $url_image_ext), "100%", "100%", true, false);
            }
            if(!empty($url_image)) {
                $additionalImages .= '<li class="item format-image">';
                $additionalImages .= '<a href="' . $url_image . '" data-gallery  class="media-box thumbnail">';
                $url_image_ext = thumbnail(preg_replace("/^\//", '', $url_image_ext), 170, 114, true, true);
                if($url_image_ext) {
                    $additionalImages .= $url_image_ext;
                } else {
                    $additionalImages .= '<span class="default-img text-center">' . "\n";
                    $additionalImages .= '   <span class="fa-stack fa-lg fa-4x">' . "\n";
                    $additionalImages .= '       <i class="fa fa-camera fa-stack-1x"></i>' . "\n";
                    $additionalImages .= '       <i class="fa fa-ban fa-stack-2x"></i>' . "\n";
                    $additionalImages .= '   </span>' . "\n";
                    $additionalImages .= '</span>' . "\n";
                }
                $additionalImages .= '</a>';
                $additionalImages .= '</li>';
            }
        }
    }
?>
    @if (is_array($datasRequest) && count($datasRequest) > 0)
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified nav-tabs-summary" role="tablist">
            <li role="presentation" class="col-xs-6 col-sm-5 active"><a href="#ad_summary" aria-controls="ad_summary" role="tab" data-toggle="tab" title="{!! trans('sell.ad_preview') !!}"><i class="fa fa-eye fa-fw"></i>{!! trans('sell.ad_preview') !!}</a></li>
            <li role="presentation" class="col-xs-6 col-sm-5"><a href="#account_detail" aria-controls="account_detail" role="tab" data-toggle="tab" title="{!! trans('dashboard.your_account_details') !!}"><i class="fa fa-user fa-fw"></i>{!! trans('dashboard.your_account_details') !!}</a></li>
            <li role="presentation" class="col-xs-2 col-sm-2 text-right">{!! Form::button(ucfirst(trans('navigation.edit')) . '<i class="fa fa-inverse fa-pencil fa-fw" aria-hidden="true"></i>', ['type' => 'button', 'id' => 'btn_modify', 'class' => 'btn btn-md btn-info btn-exception']) !!}</li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <section role="tabpanel" class="well single-vehicle-details tab-pane active" id="ad_summary">
                <div class="spacer-10"></div>
                @if($ad_premium_listing)
                <div>
                    <span class="badge-premium-listing">{!! trans('search.premium_listing') !!}</span>
                </div>
                @endif
                <div class="row">
                    <div class="col-sm-6">
                        {!! !empty($ad_title_page) ? '<h5 class="uppercase lead accent-color-danger">' . $ad_title_page . '</h5>' : '' !!}
                    </div>
                    <div class="col-sm-6 text-right">
                        {!! !empty($ad_cost) ? '<div class="btn btn-info price">' . $ad_cost . '</div>' : '' !!}
                    </div>
                </div>
                @if (!empty($featuredImage))
                <div class="row">
                    <div class="col-md-12">
                            <div class="single-listing-images">
                                <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                                    <div class="slides"></div>
                                    <h3 class="title"></h3>
                                    <a class="prev">‹</a>
                                    <a class="next">›</a>
                                    <a class="close">×</a>
                                    <a class="play-pause"></a>
                                    <ol class="indicator"></ol>
                                </div>

                                <div class="featured-image format-image">
                                    {!! $featuredImage !!}
                                </div>
                                @if (!empty($additionalImages))
                                    <div class="additional-images">
                                        <ul class="owl-carousel"
                                            data-columns="4"
                                            data-pagination="no"
                                            data-arrows="yes"
                                            data-single-item="no"
                                            data-items-desktop="4"
                                            data-items-desktop-small="4" data-items-tablet="3" data-items-mobile="3">
                                            {!! $additionalImages !!}
                                        </ul>
                                    </div>
                                @endif

                                <div class="text-right">
                                    <div class="spacer-10"></div>
                                    @include('theme.partials.elements.block.fb-like-block', [
                                        'url'=> URL::full(),
                                        'title'=> $metas_title,
                                        'description'=> $metas_description,
                                        'img_src'=> isset($featured_url_image) ? url($featured_url_image) : '',
                                        'action' => 'recommend',
                                        'size' => 'large',
                                        'layout' => 'button_count',
                                        'show_faces' => 'true',
                                        'share' => 'true'
                                    ])
                                </div>
                            </div>
                        </div>
                </div>
                @endif

                <div class="spacer-10"></div>

                <div class="row">
                    <div class="col-md-7">
                        <div class="tabs vehicle-details-tabs">
                            <ul class="nav nav-tabs">
                                @if (!empty($ad_description))
                                    <li class="active"><a data-toggle="tab" href="#ad-description">Description</a></li>
                                @endif
                                @if (!empty($ad_specifications) || (isset($ad_specifications_caracts_labels) && is_array($ad_specifications_caracts_labels)))
                                    <li><a data-toggle="tab" href="#ad-specs">Specifications</a></li>
                                @endif
                                @if (!empty($ad_features) || (isset($ad_features_caracts_categories) && is_array($ad_features_caracts_categories)))
                                    <li><a data-toggle="tab" href="#ad-add-features">Additional Features</a></li>
                                @endif
                                @if (!empty($ad_mooring))
                                    <li><a data-toggle="tab" href="#ad-location">Location</a></li>
                                @endif
                            </ul>
                            <div class="tab-content">
                                @if (isset($ad_description) && !empty($ad_description))
                                    <div id="ad-description" class="tab-pane fade in active">
                                        <blockquote>{!! $ad_description !!}</blockquote>
                                    </div>
                                @endif
                                @if (!empty($ad_specifications) || (isset($ad_specifications_caracts_labels) && is_array($ad_specifications_caracts_labels)))
                                    <div id="ad-specs" class="tab-pane fade">
                                        <blockquote>{!! $ad_specifications !!}</blockquote>
                                        @if (isset($ad_specifications_caracts_labels) && is_array($ad_specifications_caracts_labels))
                                            <table class="table-specifications table table-striped table-hover">
                                                <tbody>
                                                @foreach ($ad_specifications_caracts_labels as $key => $label)
                                                    <tr>
                                                        <td>{!! $label !!}</td>
                                                        <td>{!! !empty($ad_specifications_caracts_values[$key]) ? $ad_specifications_caracts_values[$key] : '' !!}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                @endif

                                @if (!empty($ad_features) || (isset($ad_features_caracts_categories) && is_array($ad_features_caracts_categories)))
                                    <div id="ad-add-features" class="tab-pane fade">
                                        <blockquote>{!! $ad_features !!}</blockquote>
                                        @if (isset($ad_features_caracts_categories) && is_array($ad_features_caracts_categories))
                                            <ul class="add-features-list">
                                                @foreach ($ad_features_caracts_categories as $key => $category)
                                                    <li>{!! $category !!} : {!! !empty($ad_features_caracts_values[$key]) ? $ad_features_caracts_values[$key] : '' !!}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endif
                                @if (!empty($ad_mooring))
                                    <div id="ad-location" class="tab-pane fade">
                                        @if(!empty($ad_country_code))
                                            <div class="flag {!! $ad_country_code !!} pull-right"></div>
                                        @endif
                                        <p>
                                            {!! $ad_mooring !!}
                                        </p>

                                        @if(isset($map) && $map)
                                            <div id="panel"></div>

                                            {!! '<div id="map" class="text-center">' . trans('show_ad_detail.loading_map_text') . '</div>'!!}
                                        @endif

                                        {{--<iframe width="100%" height="300px" frameBorder="0" src="//a.tiles.mapbox.com/v3/imicreation.map-zkcdvthf.html?secure"></iframe>--}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="sidebar-widget widget">
                            <ul class="list-group">
                                {!! !empty($ad_sales_status) ? '<li class="list-group-item"> <span class="badge">' . trans('navigation.status') . '</span> ' . $ad_sales_status . '</li>' : '' !!}
                                {!! !empty($sell_type) ? '<li class="list-group-item"> <span class="badge">' . trans('show_ad_detail.condition') . '</span> ' . $sell_type . '</li>' : '' !!}

                                {!! !empty($ad_manufacturer) ? '<li class="list-group-item"> <span class="badge">' . trans('show_ad_detail.make') . '</span> ' . $ad_manufacturer . '</li>' : '' !!}
                                {!! !empty($ad_model) ? '<li class="list-group-item"> <span class="badge">' . trans('show_ad_detail.model') . '</span> ' . $ad_model . '</li>' : '' !!}

                                {!! !empty($ad_type) ? '<li class="list-group-item"> <span class="badge">' . trans('show_ad_detail.type') . '</span> ' . $ad_type . '</li>' : '' !!}
                                {!! !empty($ad_category) ? '<li class="list-group-item"> <span class="badge">' . trans('show_ad_detail.category') . '</span> ' . $ad_category . '</li>' : '' !!}
                                {!! !empty($ad_subcategory) ? '<li class="list-group-item"> <span class="badge">' . trans('show_ad_detail.subcategory') . '</span> ' . $ad_subcategory . '</li>' : '' !!}

                                {!! !empty($ad_year_built) ? '<li class="list-group-item"> <span class="badge">' . trans('show_ad_detail.year') . '</span> ' . $ad_year_built . '</li>' : '' !!}

                                {!! !empty($ad_width_meter) ? '<li class="list-group-item"> <span class="badge">' . trans('show_ad_detail.width') . '</span> ' . $ad_width_meter . ' ' . trans('show_ad_detail.meter') . '</li>' : '' !!}
                                {!! !empty($ad_length_meter) ? '<li class="list-group-item"> <span class="badge">' . trans('show_ad_detail.length') . '</span> ' . $ad_length_meter .'  ' . trans('show_ad_detail.meter') . '</li>' : '' !!}
                                <?php
                                if (isset($ad_description_caracts_labels) && is_array($ad_description_caracts_labels)) {
                                    foreach ($ad_description_caracts_labels as $key => $label) {
                                        $label = preg_replace("/:$/i", '', $label);
                                        switch(mb_strtolower($label)) {
                                            case 'category':
                                            case 'subcategory':
                                            case 'manufacturer':
                                            case 'model':
                                            case 'currency':
                                            case 'year built':
                                            case 'condition':
                                            case 'sale':
                                            case 'sales status':
                                            case 'price':
                                            case 'loa':
                                            case 'length':
                                            case 'width':
                                                break;
                                            default:
                                                $value = !empty($ad_description_caracts_values[$key]) ? $ad_description_caracts_values[$key] : '';
                                                if(!empty($value)) {
                                                    echo '<li class="list-group-item"> <span class="badge">' . $label . '</span> ' . $value . '</li>';
                                                }
                                                break;
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <section role="tabpanel"  class="well tab-pane" id="account_detail">
                <div class="row">
                    <div class="col-sm-12">
                        <h4>{!! trans('dashboard.your_account_details') !!}</h4>
                    </div>

                    <div class="col-sm-6">
                        <?php
                        $label_txt = ucfirst(trans('validation.attributes.name'));
                        $attributes = [
                                'disabled'=>'disabled',
                                'class' => 'form-control',
                        ];
                        $css_state = '';
                        if (!empty($ci_full_name)) {
                            $css_state = 'has-success';
                        }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label('preview[ci_full_name]', $label_txt, ['class'=>'col-xs-12 col-sm-3 control-label']) !!}
                            <div class="col-xs-12 col-sm-9">
                                <div class="input-group">
                                    {!! Form::text('preview[ci_full_name]', $ci_full_name, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $ci_email = !empty($ci_email) ? $ci_email : old('ci_email');
                        $label_txt = ucfirst(trans('validation.attributes.email'));
                        $attributes = [
                                'disabled'=>'disabled',
                                'class' => 'form-control',
                        ];
                        $css_state = '';
                        if (!empty($ci_email)) {
                            $css_state = 'has-success';
                        }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label('preview[ci_email]', $label_txt, ['class'=>'col-xs-12 col-sm-3 control-label']) !!}
                            <div class="col-xs-12 col-sm-9">
                                <div class="input-group">
                                    {!! Form::text('preview[ci_email]', $ci_email, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $username = isset($datasRequest['username']) && !empty($datasRequest['username']) ? $datasRequest['username'] : old('username', '');
                        $label_txt = ucfirst(trans('validation.attributes.username'));
                        $attributes = [
                                'disabled'=>'disabled',
                                'class' => 'form-control',
                        ];
                        $css_state = '';
                        if (!empty($username)) {
                            $css_state = 'has-success';
                        }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label('preview[username]', $label_txt, ['class'=>'col-xs-12 col-sm-3 control-label']) !!}
                            <div class="col-xs-12 col-sm-9">
                                <div class="input-group">
                                    {!! Form::text('preview[username]', $username, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (Auth::guest())
                    <div class="col-sm-6">
                        <?php
                        $ci_password = !empty($ci_password) ? $ci_password : old('ci_password');
                        $label_txt = ucfirst(trans('validation.attributes.password'));
                        $attributes_pwd = [
                                'class' => 'form-control hidden'
                        ];
                        $attributes = [
                                'disabled'=>'disabled',
                                'class' => 'form-control password-input',
                        ];

                        $css_state = '';
                        if (!empty($ci_password)) {
                            $css_state = 'has-success';
                        }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label('preview[ci_password]', $label_txt, ['class'=>'col-xs-12 col-sm-3 control-label']) !!}
                            <div class="col-xs-12 col-sm-9">
                                <div class="input-group">
                                    {!! Form::input('password', 'preview[ci_password]', !empty($ci_password) ? $ci_password : old('ci_password'), $attributes) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!empty($ci_phone))
                        <div class="col-sm-6">
                            <?php
                            $ci_phone = !empty($ci_phone) ? $ci_phone : old('ci_phone');
                            $label_txt = ucfirst(trans('validation.attributes.phone'));
                            $attributes = [
                                    'disabled'=>'disabled',
                                    'class' => 'form-control',
                            ];
                            $css_state = '';
                            if (!empty($ci_phone)) {
                                $css_state = 'has-success';
                            }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label('preview[ci_phone]', $label_txt, ['class'=>'col-xs-12 col-sm-3 control-label']) !!}
                                <div class="col-xs-12 col-sm-9">
                                    <div class="input-group">
                                        {!! Form::text('preview[ci_phone]', $ci_phone, $attributes) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($ci_full_address))
                    <div class="col-sm-12">
                        <?php
                        $label_txt = ucfirst(trans('validation.attributes.address'));
                        $attributes = [
                                'disabled'=>'disabled',
                                'class' => 'form-control',
                        ];
                        $css_state = '';
                        if (!empty($ci_full_address)) {
                            $css_state = 'has-success';
                        }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label('preview[ci_full_address]', $label_txt, ['class'=>'col-xs-12 control-label']) !!}
                            <div class="col-xs-12">
                                <div class="input-group">
                                    {!! Form::text('preview[ci_full_address]', $ci_full_address, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="row">
                    @if(!empty($ci_description))
                    <div class="col-sm-12">
                        <?php
                        $ci_description = !empty($ci_description) ? $ci_description : old('$ci_description');
                        $label_txt = ucfirst(trans('validation.attributes.comment'));
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'rows' => 5,
                                'disabled'=>'disabled',
                                'class' => 'form-control',
                        ];
                        $css_state = '';
                        if (!empty($ci_description)) {
                            $css_state = 'has-success';
                        }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label('preview[ci_description]', $label_txt, ['class'=>'col-sm-12 control-label']) !!}
                            <div class="col-sm-12">
                                <div class="input-group">
                                    {!! Form::textarea('preview[ci_description]', $ci_description, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (Auth::guest())
                    <div class="col-md-6">
                        <?php
                        $agree_emails = !empty($agree_emails) ? $agree_emails : old('agree_emails', false);
                        $label_txt = ucfirst(trans('contact_informations.label_optin_agree_emails'));
                        $css_state = '';
                        if (!empty($agree_emails) && '1' == $agree_emails) {
                            $css_state = 'has-success';
                            $checkbox_attributes = [
                                    'disabled'=>'disabled',
                                    'checked'=>'checked'
                            ];
                        } else {
                            $checkbox_attributes = [
                                    'disabled'=>'disabled',
                            ];
                        }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            <div class="col-xs-12">
                                <div class="checkbox {!! $css_state !!}">
                                    <label for="preview[checkbox_agree_emails]">
                                        {!! Form::checkbox('preview[checkbox_agree_emails]', !empty($agree_emails) ? true : false, $agree_emails, $checkbox_attributes) !!}
                                        {!! $label_txt !!}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <?php
                        $url    = url(trans_route($currentLocale, 'routes.cgv'));
                        $title = trans('navigation.read_the_terms');
                        $terms_link = '(<a href="' . $url . '" title="' . title_case($title) . '" class="accent-color-danger strong blank">' . $title .'</a>)';
                        $label_txt = ucfirst(trans('contact_informations.label_optin_agree_cgv', ['terms'=>htmlspecialchars_decode(title_case(trans('navigation.cgv'))),'website_name'=>$website_name])) ;
                        $css_state = '';
                        $agree_cgv = !empty($agree_cgv) ? $agree_cgv : old('agree_cgv', false);
                        if (!empty($agree_cgv) && '1' == $agree_cgv) {
                            $css_state = 'has-success';
                            $checkbox_attributes = [
                                    'disabled'=>'disabled',
                                    'checked'=>'checked'
                            ];
                        } else {
                            $checkbox_attributes = [
                                    'disabled'=>'disabled',
                            ];
                        }
                        ?>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="checkbox {!! $css_state !!}">
                                    <label class="checkbox" for="preview[checkbox_agree_cgv]">
                                        {!! Form::checkbox('preview[checkbox_agree_cgv]', !empty($agree_cgv) ? true : false, $agree_cgv, $checkbox_attributes) !!}
                                        {!! $label_txt !!}
                                    </label>
                                </div>
                                {!! $terms_link !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </section>
        </div>
    @endif
    <div class="clearfix"></div>

    <?php
    if(isset($map) && $map) {
        $map_script = "<script async defer>" . "\n";
        $map_script .= "$(document).ready(function () {" . "\n";

        $map_script .= "\t" . "$('a[data-toggle=\"tab\"][href=\"#ad-location\"]').on('shown.bs.tab', function (e) {" . "\n";

        $map_script .= "\t" . "    if ('undefined' != typeof $('#map')) {" . "\n";

        $map_script .= "\t" . "        var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;" . "\n";

        $map_script .= "\t" . "        var myMarker = null;" . "\n";

        $map_script .= "\t" . "        // start coordinates" . "\n";
        $map_script .= "\t" . "        var myLatlng = new google.maps.LatLng(-34.397, 150.644);" . "\n";

        $map_script .= "\t" . "        var styles = [" . "\n";
        $map_script .= "\t" . "            {" . "\n";
        $map_script .= "\t" . "                featureType: \"all\"," . "\n";
        $map_script .= "\t" . "                stylers: [" . "\n";
        $map_script .= "\t" . "                    { saturation: -80 }" . "\n";
        $map_script .= "\t" . "                ]" . "\n";
        $map_script .= "\t" . "            }, {" . "\n";
        $map_script .= "\t" . "                featureType: \"road.arterial\"," . "\n";
        $map_script .= "\t" . "                elementType: \"geometry\"," . "\n";
        $map_script .= "\t" . "                stylers: [" . "\n";
        $map_script .= "\t" . "                    { hue: \"#0A4B73\" }," . "\n";
        $map_script .= "\t" . "                    { saturation: 50 }" . "\n";
        $map_script .= "\t" . "                ]" . "\n";
        $map_script .= "\t" . "            }, {" . "\n";
        $map_script .= "\t" . "                featureType: \"poi.business\"," . "\n";
        $map_script .= "\t" . "                elementType: \"labels\"," . "\n";
        $map_script .= "\t" . "                stylers: [" . "\n";
        $map_script .= "\t" . "                    { visibility: \"off\" }" . "\n";
        $map_script .= "\t" . "                ]" . "\n";
        $map_script .= "\t" . "            }" . "\n";
        $map_script .= "\t" . "        ];" . "\n";

        $map_script .= "\t" . "        // map options" . "\n";
        $map_script .= "\t" . "        var myOptions = {" . "\n";
        $map_script .= "\t" . "            zoom: " . $zoom . "," . "\n";
        $map_script .= "\t" . "            center: myLatlng," . "\n";
        $map_script .= "\t" . "            mapTypeId: google.maps.MapTypeId.ROADMAP," . "\n";
        $map_script .= "\t" . "            disableDefaultUI: false," . "\n";
        $map_script .= "\t" . "            draggable: true," . "\n";
        $map_script .= "\t" . "            zoomControl: true," . "\n";
        $map_script .= "\t" . "            mapTypeControl: true," . "\n";
        $map_script .= "\t" . "            scaleControl: true," . "\n";
        $map_script .= "\t" . "            streetViewControl: true," . "\n";
        $map_script .= "\t" . "            rotateControl: true," . "\n";
        $map_script .= "\t" . "            types: ['establishment']," . "\n";
        $map_script .= "\t" . "            styles : styles" . "\n";
        $map_script .= "\t" . "        };" . "\n";

        $map_script .= "\t" . "        // If this is in responsive mobile, disable zoom, scroll, drag and double click zoom" . "\n";
        $map_script .= "\t" . "        // -> This effectively makes the map static on mobile" . "\n";
        $map_script .= "\t" . "        if (w <= 768) {" . "\n";
        $map_script .= "\t" . "            // allow zoom control" . "\n";
        $map_script .= "\t" . "            myOptions.zoomControl = true;" . "\n";

        $map_script .= "\t" . "            // change zoom control's position" . "\n";
        $map_script .= "\t" . "            myOptions.zoomControlOptions = {position: google.maps.ControlPosition.TOP_RIGHT};" . "\n";

        $map_script .= "\t" . "            // get rid of everything else" . "\n";
        $map_script .= "\t" . "            myOptions.scrollWheel = false;" . "\n";
        $map_script .= "\t" . "            myOptions.scaleControl = false;" . "\n";
        $map_script .= "\t" . "            myOptions.streetViewControl = false;" . "\n";
        $map_script .= "\t" . "            myOptions.rotateControl = false;" . "\n";
        $map_script .= "\t" . "            myOptions.draggable = false;" . "\n";
        $map_script .= "\t" . "            myOptions.disableDoubleClickZoom = true;" . "\n";
        $map_script .= "\t" . "        } else {" . "\n";
        $map_script .= "\t" . "            // change zoom control's position" . "\n";
        $map_script .= "\t" . "            myOptions.zoomControlOptions = {position: google.maps.ControlPosition.RIGHT_BOTTOM};" . "\n";
        $map_script .= "\t" . "        }" . "\n";

        $map_script .= "\t" . "        var myMap = new google.maps.Map(document.getElementById('map'),myOptions);" . "\n";

        $map_script .= "\t" . "        var myPanel    = document.getElementById('panel');" . "\n";

        $map_script .= "\t" . "        // Creating Pin Icon" . "\n";
        $map_script .= "\t" . "        var myImage = '/assets/img/marker_blue.png';" . "\n";
        $map_script .= "\t" . "        var myMarkerImage = new google.maps.MarkerImage(myImage, null, null, new google.maps.Point(0, 64), new google.maps.Size(64, 64));" . "\n";

        $map_script .= "\t" . "        // geocoding adress" . "\n";
        $map_script .= "\t" . "        var GeocoderOptions = {" . "\n";
        $map_script .= "\t" . "            'address' : '" . addslashes(trim($map_address)) . "'," . "\n";
        $map_script .= "\t" . "            'region' : '" . strtoupper($ad_country_code) . "'" . "\n";
        $map_script .= "\t" . "        };" . "\n";

        $map_script .= "\t" . "        function GeocodingResult(results, status) {" . "\n";
        $map_script .= "\t" . "            // result ok" . "\n";
        $map_script .= "\t" . "            if( status == google.maps.GeocoderStatus.OK ) {" . "\n";

        $map_script .= "\t" . "                // remove existing marker" . "\n";
        $map_script .= "\t" . "                if(myMarker != null) {myMarker.setMap(null);}" . "\n";

        $map_script .= "\t" . "                // create new marker for the address" . "\n";
        $map_script .= "\t" . "                myMarker = new google.maps.Marker({" . "\n";
        $map_script .= "\t" . "                    position: results[0].geometry.location," . "\n";
        $map_script .= "\t" . "                    map: myMap," . "\n";
        $map_script .= "\t" . "                    icon: myMarkerImage," . "\n";
        $map_script .= "\t" . "                    title: '" . addslashes($ci_full_name) . "'" . "\n";
        $map_script .= "\t" . "                });" . "\n";

        $map_script .= "\t" . "                // center view on this marker" . "\n";
        $map_script .= "\t" . "                myMap.setCenter(results[0].geometry.location);" . "\n";
        $map_script .= "\t" . "            }" . "\n";

        $map_script .= "\t" . "        }" . "\n";

        $map_script .= "\t" . "        var myGeocoder = new google.maps.Geocoder();" . "\n";
        $map_script .= "\t" . "        myGeocoder.geocode( GeocoderOptions, GeocodingResult );" . "\n";


        $map_script .= "\t" . "        var direction = new google.maps.DirectionsRenderer({map: myMap, panel: myPanel});" . "\n";

        $map_script .= "\t" . "        var calculate = function(direction){" . "\n";
        $map_script .= "\t" . "            origin      = document.getElementById('origin').value;" . "\n";
        $map_script .= "\t" . "            destination = document.getElementById('destination').value;" . "\n";
        $map_script .= "\t" . "            if(origin && destination){" . "\n";
        $map_script .= "\t" . "                var request = {" . "\n";
        $map_script .= "\t" . "                    origin: origin," . "\n";
        $map_script .= "\t" . "                    destination: destination," . "\n";
        $map_script .= "\t" . "                    travelMode: google.maps.DirectionsTravelMode.DRIVING // Driving Mode" . "\n";
        $map_script .= "\t" . "                };" . "\n";
        $map_script .= "\t" . "                var directionsService = new google.maps.DirectionsService();" . "\n";
        $map_script .= "\t" . "                directionsService.route(request, function(response, status){" . "\n";
        $map_script .= "\t" . "                    if(status == google.maps.DirectionsStatus.OK){" . "\n";
        $map_script .= "\t" . "                        direction.setDirections(response);" . "\n";
        $map_script .= "\t" . "                    }" . "\n";
        $map_script .= "\t" . "                });" . "\n";
        $map_script .= "\t" . "            }" . "\n";
        $map_script .= "\t" . "        };" . "\n";

        $map_script .= "\t" . "        $('#calculate_route').on('click', function(event) {" . "\n";
        $map_script .= "\t" . "            event.preventDefault();" . "\n";
        $map_script .= "\t" . "            calculate(direction);" . "\n";
        $map_script .= "\t" . "        });" . "\n";
        $map_script .= "\t" . "    }" . "\n";
        $map_script .= "\t" . "});" . "\n";

        $map_script .= "</script>" . "\n";
    }
    ?>