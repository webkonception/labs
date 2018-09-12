<?php
    $metas = '';
    if ($datasRequest) {
        //debug($datasRequest);
        $datasRequest = json_decode(json_encode($datasRequest), true)[0];
    }

    if ($ads_list) {
        $ads_list = json_decode(json_encode($ads_list), true)['ads_list']['data'];
    }
    $pricing_currency = (null !== config('youboat.' . $country_code . '.currency')) ? config('youboat.' . $country_code . '.currency') : trans('pricing.currency');
    $_countryCode = config('youboat.' . $country_code . '.country_code') ?: 'GB';
    $locale = Search::getCountryLocaleCode($_countryCode);
    setlocale(LC_MONETARY, $locale);

    if (is_array($datasRequest) && count($datasRequest) > 0) {
        $ad_updated_at             = isset($ad_updated_at) && !empty($ad_updated_at) ? $ad_updated_at : (!empty($datasRequest['updated_at']) ? $datasRequest['updated_at'] : null);
        $ci_firstname              = isset($ci_firstname) && !empty($ci_firstname) ? $ci_firstname : (!empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null);
        $ci_last_name              = isset($ci_last_name) && !empty($ci_last_name) ? $ci_last_name : (!empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null);
        $ci_email                  = isset($ci_email) && !empty($ci_email) ? $ci_email : (!empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null);
        $ci_phone                  = isset($ci_phone) && !empty($ci_phone) ? $ci_phone : (!empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null);
        $ci_description            = isset($ci_description) && !empty($ci_description) ? $ci_description : (!empty($datasRequest['ci_description']) ? $datasRequest['ci_description'] : null);
        $ci_country                = isset($ci_country) && !empty($ci_country) ? $ci_country : (!empty($datasRequest['ci_countries_id']) ? Search::getCountry ($datasRequest['ci_countries_id']) : Search::getCountry(config('youboat.'. $country_code .'.country_code'))['id']);
        //$ad_location               = isset($ad_location) && !empty($ad_location) ? $ad_location : (!empty($datasRequest['ad_location']) ? $datasRequest['ad_location'] : '');
        $ad_location               = isset($ad_location) && !empty($ad_location) ? $ad_location : (!empty($datasRequest['ad_location']) ? $datasRequest['ad_location'] : '');
        $ad_mooring                = isset($ad_mooring) && !empty($ad_mooring) ? $ad_mooring : (!empty($datasRequest['ad_mooring_country']) ? $datasRequest['ad_mooring_country'] : '');

        $ad_id                     = $datasRequest['id'];
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
        //$ad_location = '';
/*
        if(!empty($datasRequest['city'])) {
            $ad_location .= $datasRequest['city'] . ', ';
            $map = true;
        }
        if(!empty($datasRequest['subregion'])) {
            $ad_location .= $datasRequest['subregion'] . ', ';
            $map = true;
        }
        if(!empty($datasRequest['region'])) {
            $ad_location .= $datasRequest['region'] . ', ';
            $map = true;
        }
        if(!empty($datasRequest['province'])) {
            $ad_location .= $datasRequest['province'] . ', ';
            $map = true;
        }
        if(!empty($datasRequest['countries_id'])) {
            $country = Search::getCountryById($datasRequest['countries_id'])['name'];
            if (!empty($country)) {
                $ad_location .= $country . ', ';
            }
            if($map && !empty($country)) {
                $map = true;
            }
        }
        $ad_location = str_replace([", Unknown"], [''], $ad_location);
        $ad_location = preg_replace("/, $/i", '', $ad_location);
*/
        $tab_zoom = [6,7,7,10,14,14];
        $zoom = $tab_zoom[0];

        if(empty($ad_mooring)) {
            $ad_mooring = $ad_location;
        } else if(strlen($ad_mooring) < strlen($ad_location)) {
            $ad_mooring = $ad_location;
        }

        $ad_country_code = $country_code;
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
            //$ad_mooring = preg_replace("/, $/i", '', $ad_mooring);

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

        $ad_dealer_name = '';
        $dealer = '';
        $ad_dealer_url = '';
        //if (isset($datasRequest['dealerscaracts_id']) && $datasRequest['dealerscaracts_id'] != 0) {
        if (!empty($datasRequest['dealerscaracts_id'])) {
            $dealer = Search::getDealerCaractsById($datasRequest['dealerscaracts_id']);

            if(!empty($dealer['country_code'] && !empty($dealer['rewrite_url']))) {
                //$ad_dealer_url = 'https://dealers.youboat.com/' . mb_strtolower($dealer['country_code']) . '/' . $dealer['rewrite_url'];
                if (count(config('app.languages')) > 1) {
                    $ad_dealer_url = 'https://dealers.youboat.com/' . $currentLocale . '/' . mb_strtolower($country_code) . '/' . $dealer['rewrite_url'];
                } else {
                    $ad_dealer_url = 'https://dealers.youboat.com/' . mb_strtolower($country_code) . '/' . $dealer['rewrite_url'];
                }
            }
        } else if (!empty($datasRequest['ad_dealer_name'])) {
            $ad_dealer_name = $datasRequest['ad_dealer_name'];
        }

        $ad_dealer_address = '';
        if(is_array($dealer) && !empty($dealer['name'])) {
            $ad_dealer_name = $dealer['name'];
            $ad_dealer_address = !empty($dealer['address']) ? $dealer['address'] : '';
        }
        if(empty($datasRequest['ad_phones']) && is_array($dealer) && array_key_exists('phone', $dealer) && !empty($dealer['phone'])) {
            $datasRequest['ad_phones'] = $dealer['phone'];
        }

        $ad_title = '';
        $ad_title_page = '';
        if (!empty($ad_manufacturer) && !empty($ad_model)) {
            $ad_title =  title_case($ad_manufacturer . ' ' . $ad_model);
            $ad_title_page =  title_case($ad_manufacturer) . ' ' . '<span>' . title_case($ad_model) . '</span>';
        } else if (!empty($datasRequest['ad_title'])) {
            $ad_title =  title_case($datasRequest['ad_title']);
            $ad_title_page =  title_case($datasRequest['ad_title']);
        } else if (!empty($ad_manufacturer) || !empty($ad_model)) {
            if (!empty($ad_manufacturer)) {
                $ad_title =  title_case($ad_manufacturer);
                $ad_title_page =  title_case($ad_manufacturer);
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
            $ad_description = nl2br($ad_description);
            //$ad_description = preg_replace("/\. /i", '.<br><br>', $ad_description);
            //$ad_description = preg_replace("/\: /i", ':<br>', $ad_description);
            $ad_description = preg_replace("/\s-/i", '<br>-', $ad_description);
            $ad_description = preg_replace("/<\/p><br \/>/i", '</p>', $ad_description);
            $ad_description = preg_replace('/^About/','', $ad_description);
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
            $datasRequest['ad_photo'] = preg_replace('/;$/', '', $datasRequest['ad_photo']);
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

    //<title>Jeanneau Merry Fisher 635 occasion - Nanni 85 cv - Annonce bateau en vente Côtes d`Armor (22) | Ref. 96735</title>
    //<title>uk.YouBoat.com | For sale Ribtec 12M Shelter Deck Rib</title>
    $metas_title = '' ;
    $metas_title .= !empty($ad_title) ? $ad_title : '';
    $metas_title .= !empty($ad_type) ? ' - ' . ucfirst(trans('metas.ad')) . ' ' . mb_strtolower($ad_type) . ' ' . mb_strtolower(trans('navigation.for_sale')) : '';
    $metas_title .= !empty($ad_mooring) ? ', ' . $ad_mooring  : '';
    //$metas_title .= ' | Ref. ' . $datasRequest['id'];

    //<meta name="description" content="Annonce vente d'un Jeanneau Merry Fisher 635 à 14.900 euros du client GROUPE ROUXEL MARINE Côtes d`Armor (22).
    // vente Bateau Jeanneau Merry Fisher 635 occasion publié dans la catégorie Pêche Promenade de Bateaux à moteurs" />
    $metas_description = '';
    $metas_description .= ucfirst(trans('metas.ad')) . ' ' . mb_strtolower(trans('navigation.for_sale')) . ' ';
    $metas_description .= !empty($ad_title) ? $ad_title : '';
    $metas_description .= !empty($ad_cost_meta) ? ' - ' . trim($ad_cost_meta) . ' -' : '';
    $metas_description .= !empty($ad_dealer_name) ? ' ' . mb_strtolower(trans('navigation.by')) . ' ' . $ad_dealer_name  : '';
    $metas_description .= !empty($ad_mooring) ? ', ' . $ad_mooring  : '';
    $metas_description .= '. ' . trans('navigation.for_sale');
    $metas_description .= !empty($ad_title) ? ' ' . $ad_title : '';
    $metas_description .= !empty($ad_category) ? ' ' . trans('metas.published') . ' ' . trans('metas.in') . ' ' . trans('metas.the_category') . ' ' . $ad_category : '';
    $metas_description .= !empty($ad_type) ? ' ' . trans('metas.in') . ' ' . $ad_type : '';

    /*$metas_keywords = '';
    $metas_keywords .= !empty($ad_type) ? trans('navigation.for_sale') . ' ' . $ad_type : '';
    $metas_keywords .= !empty($ad_category) ? ', ' . $ad_category : '';
    $metas_keywords .= !empty($ad_manufacturer) ? ', ' . $ad_manufacturer : '';
    $metas_keywords .= !empty($ad_model) ? ', ' . $ad_model : '';*/

    $width = 600;
    $height = 450;

    $metas_og = [
            'og_url' => [
                    'property' => 'og:url',
                    'content' => URL::full()
            ],
            'og_type' => [
                    'property' => 'og:type',
                    'content' => 'website'
            ],
            'og_title' => [
                    'property' => 'og:title',
                    'content' => $metas_title
            ],
            'og_description' => [
                    'property' => 'og:description',
                    'content' => $metas_description
            ],
            'og_image' => [
                    'property' => 'og:image',
                    //'content' => url(asset($featured_url_image_ext))
                    'content' => isset($featured_url_image) ? url(asset($featured_url_image)) : ''
            ],
            'og_height' => [
                    'property' => 'og:image:height',
                    'content' => $height
            ],
            'og_width' => [
                    'property' => 'og:image:width',
                    'content' => $width
            ]
    ];
    $metas = [
        'metas_title' => $metas_title,
        'metas_description' => $metas_description,
            'metas_og' => $metas_og,
        //,'metas_keywords' => $metas_keywords
    ];

    if(
            (!empty($manufacturer['rewrite_url']) && isset($routeParameters['manufacturers']) && $routeParameters['manufacturers'] !=  $manufacturer['rewrite_url']) ||
            (!empty($model['rewrite_url']) && isset($routeParameters['models']) && $routeParameters['models'] != $model['rewrite_url']) ||
            (!empty($ad_title) && isset($routeParameters['manufacturers_models']) && $routeParameters['manufacturers_models'] != str_slug($ad_title))

    ) {
        debug('/!\ /!\ /!\ /!\ /!\ /!\ ');
        debug('!!! id annonce incohérent avec url rewritting !!!');
        debug($ad_url);    debug('/!\ /!\ /!\ /!\ /!\ /!\ ');
        //redirect(url($ad_url), 301);
        //return redirect()->route('contact');
        header('Status: 301 Moved Permanently', false, 301);
        header('Location: '. url($ad_url));
        exit();
    } else {
        debug ('I\'m HAPPY :D');
        debug($ad_url);
    }
?>
@section('title_page')
    {!! $ad_title_page !!}
@endsection

@extends('layouts.theme')

@include('theme.partials.elements.search.breadcrumb')

@section('content')
        @include('theme.partials.modals.msg-modal', ['title_modal'=>'<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . ' ' . trans('navigation.for') . ' &laquo; ' . $ad_title . ' &raquo;</h4>','message_modal'=> 'message_ajax', 'modal_backdrop' => 'static', 'message_type'=>'ajax', 'form_referrer' => 'form_enquiry'])
        @include('theme.partials.modals.msg-modal', ['title_modal'=>'<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . ' ' . trans('navigation.for') . ' &laquo; ' . $ad_title . ' &raquo;</h4>','message_modal'=> 'message_ajax', 'modal_backdrop' => 'static', 'message_type'=>'ajax', 'form_referrer' => 'form_bod'])
        @include('theme.partials.modals.msg-modal', ['title_modal'=>'<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . ' ' . trans('navigation.for') . ' &laquo; ' . $ad_title . ' &raquo;</h4>','message_modal'=> 'message_ajax', 'modal_backdrop' => 'static', 'message_type'=>'ajax', 'form_referrer' => 'error'])

        @if (is_array($datasRequest) && count($datasRequest) > 0)
            <article class="single-vehicle-details">
                <div class="single-vehicle-title">
                    @if($ad_premium_listing)<span class="badge-premium-listing">{!! trans('search.premium_listing') !!}</span>@endif
                    {{--<h2 class="post-title">{!! $ad_title_page !!}</h2>--}}
                </div>
                <div class="row single-listing-actions">
                    <div class="col-sm-6 pull-right">
                        <div class="btn-group pull-right" role="group">
                            {{--<a href="#" class="btn btn-default" title="Save this car"><i class="fa fa-star-o"></i> <span>Save this car</span></a>--}}
                            <a href="#send_enquiry" class="btn btn-danger" id="btn_send_enquiry" title="{!! trans('navigation.send_enquiry') !!}"><i class="fa fa-info"></i> <span>{!! trans('navigation.send_enquiry') !!}</span></a>
                            {{--<a href="#" data-toggle="modal" data-target="#infoModal" class="btn btn-default" title="Request more info"><i class="fa fa-info"></i> <span>Request more info</span></a>--}}
                            {{--<a href="#" data-toggle="modal" data-target="#testdriveModal" class="btn btn-default" title="Book a test drive"><i class="fa fa-calendar"></i> <span>Book a test drive</span></a>--}}
                            {{--<a href="#" data-toggle="modal" data-target="#offerModal" class="btn btn-default" title="Make an offer"><i class="fa fa-dollar"></i> <span>Make an offer</span></a>--}}
                            {{--<a href="#" data-toggle="modal" data-target="#sendModal" class="btn btn-default" title="Send to a friend"><i class="fa fa-send"></i> <span>Send to a friend</span></a>--}}
                            {{--<a href="#" class="btn btn-default" title="Download Manual"><i class="fa fa-book"></i> <span>Download Manual</span></a>--}}
                            {{--<a href="javascript:void(0)" onclick="window.print();" class="btn btn-default" title="Print"><i class="fa fa-print"></i> <span>Print</span></a>--}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        {!! !empty($ad_cost) ? '<div class="btn btn-info price">' . $ad_cost . '</div>' : '' !!}
                    </div>
                </div>
                <div class="row">
                    @if (!empty($featuredImage))
                    <div class="col-md-7">
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
                    @endif
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

                                    @if($map)
                                    <div id="destinationForm">
                                        {!! Form::open(array('url'=>'#direction', 'class'=>'form-horizontal well', 'role'=>'form', 'id'=>'form_direction', 'autocomplete'=>'off')) !!}
                                            <input type="hidden" name="destination" id="destination" value="{!! $ad_mooring !!}">
                                            <?php
                                            $label_txt = ucfirst(trans('show_ad_detail.starting_point'));
                                            $placeholder = trans('navigation.form_enter_placeholder');
                                            $attributes = [
                                                    'data-placeholder' => $placeholder,
                                                    'placeholder' => $placeholder,
                                                    'class' => 'form-control',
                                                    'id' => 'origin'
                                            ];
                                            $css_state = '';
                                            if ($errors->has('origin')) {
                                                $css_state = 'has-error';
                                            }
                                            ?>
                                            <div class="form-group {!! $css_state !!}">
                                                {!! Form::label('origin', $label_txt, ['class'=>'col-xs-12 col-sm-4 control-label']) !!}
                                                <div class="col-xs-12 col-sm-8">
                                                    <div class="input-group">
                                                        {!! Form::text('origin', old('origin'), $attributes) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group {!! $css_state !!}">
                                                <div class="text-right col-sm-12   ">
                                                    {!! Form::button(trans('show_ad_detail.calculate_route') . '<i class="fa fa-map-signs fa-fw" aria-hidden="true"></i>', ['type' => 'submit', 'id' => 'calculate_route', 'class' => 'btn btn-md btn-primary btn-exception']) !!}
                                                </div>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                    <div id="panel"></div>

                                    {!! '<div id="map" class="text-center">' . trans('show_ad_detail.loading_map_text') . '</div>'!!}
                                    @endif

                                    {{--<iframe width="100%" height="300px" frameBorder="0" src="//a.tiles.mapbox.com/v3/imicreation.map-zkcdvthf.html?secure"></iframe>--}}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a name="send_enquiry"></a>
                    <div class="col-md-5 vehicle-details-sidebar sidebar">

                        <div class="sidebar-widget widget seller-contact-widget">
                            <div class="">
                                @include('theme.partials.elements.block.enquire-form-block')
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="vehicle-enquiry-foot clearfix">
                                        <span class="vehicle-enquiry-foot-ico col-xs-2"><i class="fa fa-phone"></i></span>
                                        @if (isset($ad_phones) && is_array($ad_phones))
                                        <p class="col-xs-10 view_phones">
                                            {!! link_to('#', trans('navigation.view_phone'), ['title'=>trans('navigation.view_phone'), 'data-ga'=>$view_name . '~' . trans('navigation.view_phone') . '|' . 'Ref. ' . $datasRequest['id'], 'class'=>'col-xs-10 btn btn-block btn-danger']) !!}
                                        </p>
                                        <p class="col-xs-10 phones">
                                            @foreach($ad_phones as $key => $ad_phone)
                                            <strong>{!! link_to('tel:' . $ad_phone,  $ad_phone, ['title'=> trans('navigation.call') . ': ' . $ad_phone]) !!}</strong>
                                            @endforeach
                                        </p>
                                        @endif
                                        @if (!empty($ad_dealer_name))
                                        <div class="col-xs-12 infos">
                                            {!! trans('navigation.seller') !!}:
                                            @if (!empty($ad_dealer_url))
                                            <a href="{{ url($ad_dealer_url) }}" title="{!! $ad_dealer_name !!}" data-ga="{!! $view_name . '~' . trans('navigation.dealer_details') . '|' . 'Ref. ' . $datasRequest['id'] !!}" class="GA_event blank underline">
                                                {!! $ad_dealer_name !!}
                                                <span class="fa fa-external-link fa-fw" aria-hidden="true"></span>
                                            </a>
                                            @else
                                            <u>{!! $ad_dealer_name !!}</u>
                                            @endif
                                            <blockquote class="italic accent-color">
                                                @if (!empty($ad_dealer_address)){!! $ad_dealer_address !!}@endif
                                            </blockquote
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="spacer-10"></div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="well well-white clearfix">
                            <h3>{!!  trans('navigation.search_ads') !!} & {!!  trans('navigation.services') !!}</h3>
                            @include('theme.partials.elements.associated-links', compact(
                                        'ad_type', 'ad_results_type_url',
                                        'ad_category', 'ad_results_category_url',
                                        'ad_subcategory', 'ad_results_subcategory_url',
                                        'ad_manufacturer', 'ad_results_manufacturer_url',
                                        'ad_model', 'ad_results_model_url'
                                ))
                        </div>
                    </div>
                </div>
                @if($ad_banners)
                @if (!$agent->isMobile())
                <div class="row">
                    <div class="col-sm-12 hidden-xs text-center">
                        <hr>
                        @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90'])
                        <hr>
                    </div>
                    <div class="col-sm-12 visible-xs text-center">
                        <hr>
                        @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
                        <hr>
                    </div>
                </div>
                @elseif ($agent->isMobile())
                <div class="row">
                    <div class="col-sm-12 hidden-xs visible-sm text-center">
                        <hr>
                        @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90'])
                        <hr>
                    </div>
                    <div class="col-sm-12 visible-xs text-center">
                        <hr>
                        @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
                        <hr>
                    </div>
                </div>
                @endif
                @endif
                @if (isset($ads_list) && is_array($ads_list) && count($ads_list) > 0)
                <div class="row">
                    <div class="col-md-12">
                        @include('theme.partials.elements.recent-ads', ['data_columns'=>3, 'data_items_desktop'=>3, 'data_items_desktop_small'=>2,
                        'ads_list'=>$ads_list, 'ads_title_block' => trans('navigation.related_ads')])
                    </div>
                </div>
                @endif

            </article>
    @endif
            <div class="clearfix"></div>
@endsection

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('javascript')
@if ($map)
<script async defer>
    $(document).ready(function () {

        $('a[data-toggle="tab"][href="#ad-location"]').on('shown.bs.tab', function (e) {

            if ('undefined' != typeof $('#map')) {

                var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

                var myMarker = null;

                // start coordinates
                var myLatlng = new google.maps.LatLng(-34.397, 150.644);

                var styles = [
                    {
                        featureType: "all",
                        stylers: [
                            { saturation: -80 }
                        ]
                    }, {
                        featureType: "road.arterial",
                        elementType: "geometry",
                        stylers: [
                            { hue: "#0A4B73" },
                            { saturation: 50 }
                        ]
                    }, {
                        featureType: "poi.business",
                        elementType: "labels",
                        stylers: [
                            { visibility: "off" }
                        ]
                    }
                ];

                // map options
                var myOptions = {
                    zoom: {!! $zoom !!},
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI: false,
                    draggable: true,
                    zoomControl: true,
                    mapTypeControl: true,
                    scaleControl: true,
                    streetViewControl: true,
                    rotateControl: true,
                    types: ['establishment'],
                    styles : styles
                };

                // If this is in responsive mobile, disable zoom, scroll, drag and double click zoom
                // -> This effectively makes the map static on mobile
                if (w <= 768) {
                    // allow zoom control
                    myOptions.zoomControl = true;

                    // change zoom control's position
                    myOptions.zoomControlOptions = {
                        position: google.maps.ControlPosition.TOP_RIGHT
                    };

                    // get rid of everything else
                    myOptions.scrollWheel = false;
                    myOptions.scaleControl = false;
                    myOptions.streetViewControl = false;
                    myOptions.rotateControl = false;
                    myOptions.draggable = false;
                    myOptions.disableDoubleClickZoom = true;
                } else {
                    // change zoom control's position
                    myOptions.zoomControlOptions = {
                        position: google.maps.ControlPosition.RIGHT_BOTTOM
                    };
                }

                var myMap = new google.maps.Map(
                        document.getElementById('map'),
                        myOptions
                );

                var myPanel    = document.getElementById('panel');

                // Creating Pin Icon
                var myImage = '/assets/img/marker_blue.png';
                var myMarkerImage = new google.maps.MarkerImage(myImage, null, null, new google.maps.Point(0, 64), new google.maps.Size(64, 64));

                // geocoding adress
                var GeocoderOptions = {
                    'address' : '{!! addslashes(trim($map_address)) !!}',
                    'region' : '{!! strtoupper($ad_country_code) !!}'
                };

                function GeocodingResult(results, status) {
                    // result ok
                    if( status == google.maps.GeocoderStatus.OK ) {

                        // remove existing marker
                        if(myMarker != null) {
                            myMarker.setMap(null);
                        }

                        // create new marker for the address
                        myMarker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: myMap,
                            icon: myMarkerImage,
                            title: '{!! addslashes($ad_dealer_name) !!}'
                        });

                        // center view on this marker
                        myMap.setCenter(results[0].geometry.location);
                    }

                }

                var myGeocoder = new google.maps.Geocoder();
                myGeocoder.geocode( GeocoderOptions, GeocodingResult );


                var direction = new google.maps.DirectionsRenderer({
                    map: myMap,
                    panel: myPanel
                });

                var calculate = function(direction){
                    origin      = document.getElementById('origin').value;
                    destination = document.getElementById('destination').value;
                    if(origin && destination){
                        var request = {
                            origin: origin,
                            destination: destination,
                            travelMode: google.maps.DirectionsTravelMode.DRIVING // Driving Mode
                        };
                        var directionsService = new google.maps.DirectionsService();
                        directionsService.route(request, function(response, status){
                            if(status == google.maps.DirectionsStatus.OK){
                                direction.setDirections(response);
                            }
                        });
                    }
                };

                $('#calculate_route').on('click', function(event) {
                    event.preventDefault();
                    calculate(direction);
                });
            }
        });
    });
</script>
@endif
@endsection
