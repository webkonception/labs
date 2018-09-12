<?php
    $metas = '';
    if ($ads_list) {
        //$pagination = preg_replace('/a href/i', 'a rel="noindex, follow" href', $ads_list->links());
        $pagination = $ads_list->links();
        $total_ads = $ads_list->total();
        $ads_list = json_decode(json_encode($ads_list), true)['data'];
    }
    $pricing_currency = (null !== config('youboat.' . $country_code . '.currency')) ? config('youboat.' . $country_code . '.currency') : trans('pricing.currency');
    $_countryCode = (null !== config('youboat.' . $country_code . '.country_code')) ? config('youboat.' . $country_code . '.country_code') : 'GB';
    $locale = Search::getCountryLocaleCode($_countryCode);
    setlocale(LC_MONETARY, $locale);

    $ad_counter = 1;
    $modulo = 5;
?>
<div class="results-container-in">

    @if (isset($ads_list) && count($ads_list) == 0)
    <div class="waiting" style="display:none;">
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>
    @endif

    @if (isset($ads_list) && is_array($ads_list) &&count($ads_list) > 0)
    <div id="results-holder" class="{!! !empty($datasRequest['results_view']) ? 'results-' . $datasRequest['results_view'] . '-view' : 'results-grid-view'  !!}">

        <div class="row">
            <div class="col-sm-12 text-center">
            @if (!empty($total_ads) && $total_ads > 0)
                <div class="pull-right"><strong class="total_results">{!! !empty($total_pagination) ? $total_pagination : '' !!}</strong></div>
            @endif
                {!! $pagination !!}
            </div>
        </div>
        @if(empty($pagination))
        <div class="spacer-10"></div>
        @endif
        @foreach ($ads_list as $ad)
        <?php
            $ad_referrer = '';
            if (!empty($ad['ad_referrer'])) {
                $ad_referrer = $ad['ad_referrer'];
            }

            $ad_type = '';
            $ad_type_rewrite_url = '';
            $ad_results_type_url = '';
            if (isset($ad['adstypes_id']) && $ad['adstypes_id'] != 0) {
                $ad_type = Search::getAdsTypeById($ad['adstypes_id']);
            }
            if(is_array($ad_type) && isset($ad_type['name'])) {
                $ad_type_rewrite_url = $ad_type['rewrite_url'];
                $ad_type = $ad_type['name'];
                //$ad_type_rewrite_url = str_slug($ad_type, $separator = '-');
                $ad_results_type_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url));
            }

            //debug('$ad_results_type_url');debug($ad_results_type_url);
            $ad_category = '';
            $ad_category_rewrite_url = '';
            $ad_results_category_url = '';
            if (isset($ad['categories_ids']) && $ad['categories_ids'] != 0) {
                $ad_category = Search::getCategoryById($ad['categories_ids']);
            }
            if(is_array($ad_category) && isset($ad_category['name'])) {
                $ad_category_rewrite_url = $ad_category['rewrite_url'];
                $ad_category = $ad_category['name'];
                $ad_results_category_url = $ad_results_type_url . '/' . trans('routes.' . str_replace('-', '_', $ad_category_rewrite_url));
            }

            $ad_subcategory = '';
            $ad_subcategory_rewrite_url = '';
            $ad_results_subcategory_url = '';
            if (isset($ad['subcategories_ids']) && $ad['subcategories_ids'] != 0) {
                $ad_subcategory = Search::getSubcategoryById($ad['subcategories_ids']);
            }
            if(is_array($ad_subcategory) && isset($ad_subcategory['name'])) {
                $ad_subcategory_rewrite_url = $ad_subcategory['rewrite_url'];
                $ad_subcategory = $ad_subcategory['name'];
                $ad_results_subcategory_url = $ad_results_category_url . '/' . trans('routes.' . str_replace('-', '_', $ad_subcategory_rewrite_url));
            }
            $ad_manufacturer = '';
            $ad_manufacturer_rewrite_url = '';
            $ad_results_manufacturer_url = '';

            if (isset($ad['manufacturers_id']) && $ad['manufacturers_id'] != 0) {
                $ad_manufacturer = Search::getManufacturerById($ad['manufacturers_id']);
            } else if (isset($ad['manufacturersengines_id']) && $ad['manufacturersengines_id'] != 0) {
                $ad_manufacturer = Search::getManufacturerEngineById($ad['manufacturersengines_id']);
                $ad_manufacturerengine = $ad_manufacturer;
            } else if(isset($ad['ad_manufacturer_url']) && !empty($ad['ad_manufacturer_url'])) {
                if(preg_match('/\//',$ad['ad_manufacturer_url'])) {
                    $array = explode("/", $ad['ad_manufacturer_url']);
                    $ad_manufacturer_rewrite_url = $array[2];
                } else {
                    $ad_manufacturer_rewrite_url = $ad['ad_manufacturer_url'];
                }
                $ad_manufacturer = Search::getManufacturerByName($ad_manufacturer_rewrite_url);
            }

            if(is_array($ad_manufacturer) && isset($ad_manufacturer['name'])) {
                $ad_manufacturer_rewrite_url = $ad_manufacturer['rewrite_url'];
                $ad_manufacturer = $ad_manufacturer['name'];
                $ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.manufacturers') . '/' . $ad_manufacturer_rewrite_url;
            } else if ($ad['ad_manufacturer_name'] && !empty($ad['ad_manufacturer_name'])) {
                $ad_manufacturer_rewrite_url = str_slug($ad['ad_manufacturer_name']);
                $ad_manufacturer = $ad['ad_manufacturer_name'];
                $ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.manufacturers') . '/' . $ad_manufacturer_rewrite_url;
            }

            $ad_model = '';
            $ad_model_rewrite_url = '';
            $ad_results_model_url = '';
            if (isset($ad['models_id']) && $ad['models_id'] != 0) {
                $ad_model = Search::getModelById($ad['models_id']);
            } else if (isset($ad['modelsengines_id']) && $ad['modelsengines_id'] != 0) {
                $ad_model = Search::getModelEngineById($ad['modelsengines_id']);
                $ad_modelengine = $ad_model;
            }
            /*if(is_array($ad_model) && isset($ad_model['name'])) {
                $ad_model_rewrite_url = $ad_model['rewrite_url'];
                $ad_model = $ad_model['name'];
                $ad_results_model_url = $ad_results_manufacturer_url . '/' . $ad_model_rewrite_url;
            } else if ($ad['ad_model_name'] && !empty($ad['ad_model_name'])) {
                $ad_model_rewrite_url = str_slug($ad['ad_model_name']);
                $ad_model = $ad['ad_model_name'];
                $ad_results_model_url = $ad_results_manufacturer_url . '/' . $ad_model_rewrite_url;
            }*/
            if(is_array($ad_model) && !empty($ad_model['name'])) {
                $ad_model_rewrite_url = $ad_model['rewrite_url'];
                $ad_model = $ad_model['name'];
                $ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model') . '/' . $ad_manufacturer_rewrite_url . '/' . $ad_model_rewrite_url;
            } else if ($ad['ad_model_name'] && !empty($ad['ad_model_name'])) {
                $ad_model_rewrite_url = str_slug($ad['ad_model_name']);
                $ad_model = $ad['ad_model_name'];
                $ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model') . '/' . $ad_manufacturer_rewrite_url . '/' . $ad_model_rewrite_url;
            }

            $ad_location = '';
            if(!empty($ad['countries_id'])) {
                $country = Search::getCountryById($ad['countries_id'])['name'];
                if (isset($country) && !empty($country)) {
                    $ad_location .= $country . ', ';
                }
            }
            if(!empty($ad['province'])) {
                $ad_location .= str_replace(['N/A'], [''], $ad['province']) . ', ';
            }
            if(!empty($ad['region'])) {
                $ad_location .= str_replace(['N/A'], [''], $ad['region']) . ', ';
            }
            if(!empty($ad['subregion'])) {
                $ad_location .= str_replace(['N/A'], [''], $ad['subregion']) . ', ';
            }
            if(!empty($ad['city'])) {
                $ad_location .= str_replace(['N/A'], [''], $ad['city']) . ', ';
            }
            $ad_location = str_replace([", Unknown"], [''], $ad_location);
            $ad_location = preg_replace("/, $/i", '', $ad_location);

            $ad_dealer = '';
            $dealer = '';
            if (isset($ad['dealerscaracts_id']) && $ad['dealerscaracts_id'] != 0) {
                $dealer = Search::getDealerCaractsById($ad['dealerscaracts_id']);
            } else if (!empty($ad['ad_dealer_name'])) {
                $dealer = $ad['ad_dealer_name'];
            }
            if(is_array($dealer) && isset($dealer['name'])) {
                $ad_dealer = $dealer['name'];
            }

            $ad_title = '';
            if (!empty($ad_model)) {
                $ad_title =  title_case($ad_manufacturer . ' ' . $ad_model);
            } else if (!empty($ad['ad_title'])) {
                $ad_title =  title_case($ad['ad_title']);
            } else if (!empty($ad_manufacturer) || !empty($ad_model)) {
                if (!empty($ad_manufacturer)) {
                    $ad_title =  title_case($ad_manufacturer);
                }
                if (!empty($ad_model)) {
                    $ad_title =  title_case($ad_model);
                }
            }

            $ad_description = '';
            if (!empty($ad['ad_description'])) {
                $ad_description =  str_limit($ad['ad_description'], 200);
            }

            $sell_type = '';
            if (!empty($ad['sell_type'])) {
                $sell_type =  $ad['sell_type'];
            }

            $ad_year_built = '';
            if (!empty($ad['ad_year_built'])) {
                $ad_year_built =  $ad['ad_year_built'];
            }

            $ad_img_src = '';
            if (!empty($ad['ad_photo'])) {
                $ad['ad_photo'] = preg_replace('/;$/', '', $ad['ad_photo']);
                $ad_img_src = $ad['ad_photo'];
            } else if (isset($ad['id']) && $ad['id'] != 0) {
                $array = Search::getSomethingById('gateway_ads_details', $ad['id'], 'ad_photos');
                if(is_array($array) && count($array) >0) {
                    $ad_img_src = getFirstElement(array_get($array[0], 'ad_photos'));
                }
            }

            $ad_cost = '&nbsp;';
            if (!empty($ad['ad_price'])) {
                $ad_price = $ad['ad_price'];

                //$ad_cost = trim(preg_replace('!\s+!', ' ', money_format('%.2n', $ad_price))); // with two decimal
//                $ad_cost = is_numeric($ad_price) ? trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $ad_price))) : $ad_price;
                //$ad_cost = is_numeric($ad_price) ? formatPrice($ad_price, $ad['countries_id']) : $ad_price;
                $ad_cost = is_numeric($ad_price) ? formatPriceCurrency($ad_price, $ad['countries_id']) : $ad_price;
                $ad_cost = str_replace(['POA'], ['&nbsp;'], $ad_cost);
            }

            $ad_url = '';
            if (
                    isset($ad['id']) &&
                    isset($ad_results_type_url) && !empty($ad_results_type_url) &&
                    isset($ad_manufacturer_rewrite_url) && !empty($ad_manufacturer_rewrite_url) &&
                    isset($ad_model_rewrite_url) && !empty($ad_model_rewrite_url)
            ) {
                $ad_url = trans_route($currentLocale, 'routes.buy') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url)) . '/' . $ad_manufacturer_rewrite_url . '/' . $ad_model_rewrite_url . '/' . $ad['id'];
            } else if (
                    isset($ad['id']) &&
                    isset($ad_results_type_url) && !empty($ad_results_type_url) &&
                    isset($ad_manufacturer_rewrite_url) && !empty($ad_manufacturer_rewrite_url) &&
                    empty($ad_model_rewrite_url)
            ) {
                $ad_url = trans_route($currentLocale, 'routes.buy') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url)) . '/' . $ad_manufacturer_rewrite_url . '/' . $ad['id'];
            } else if (
                    isset($ad['id']) &&
                    isset($ad_results_type_url) && !empty($ad_results_type_url) &&
                    isset($ad_model_rewrite_url) && !empty($ad_model_rewrite_url)
            ) {
                $ad_url = trans_route($currentLocale, 'routes.buy') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url)) . '/' . str_slug($ad_title, $separator = '-') . '/' . $ad['id'];
            } else if (
                    isset($ad['id']) &&
                    isset($ad_type_rewrite_url) && !empty($ad_type_rewrite_url) &&
                    isset($ad_title) && !empty($ad_title)
            ) {
                $ad_url = trans_route($currentLocale, 'routes.buy') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url)) . '/' . str_slug($ad_title, $separator = '-') . '/' . $ad['id'];
            } else {
                $ad_url = trans_route($currentLocale, 'routes.buy') . '/' . trans('routes.manufacturers') . '/' . trans('routes.models') . '/' . $ad['id'];
            }

            $getCountryById = Search::getCountryById($ad['countries_id'], false);
            $ad_country_code = array_key_exists('code', $getCountryById) && !empty($getCountryById['code']) ? $getCountryById['code'] : '';

            $ad_premium_listing = false;

            $ad_img_params = ['ad_id'=>$ad['id'], 'ad_title'=>$ad_title, 'image_name'=>'photo-0'];
        ?>
            {{--@if($ad_counter == 1 && $ad_banners && isset($datasRequest['results_view']) && 'list' == $datasRequest['results_view'])--}}
            @if($ad_counter == 1 && $ad_banners)
                <div class="advertising advertising_first">
                    <div class="row">
                        <div class="col-sm-12 hidden-xs text-center">
                            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90'])
                        </div>
                        @if(!$agent->isMobile())
                        <div class="col-sm-12 visible-xs text-center">
                            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
                        </div>
                        @endif
                    </div>
                </div>
            @endif
            @include('theme.partials.elements.block.results-block', [
                'block_format'=>'format-standard',
                'ad_country_code'=>$ad_country_code,
                'ad_referrer'=>$ad_referrer,
                'ad_img_params'=>$ad_img_params,
                'ad_id'=>$ad['id'],
                'ad_url'=>$ad_url,
                'ad_img_src'=>$ad_img_src,
                'ad_title'=>$ad_title,
                'ad_description'=>$ad_description,
                'ad_sell_type'=>$sell_type,
                'ad_year_built'=>$ad_year_built,
                'ad_premium_listing'=>$ad_premium_listing,
                'ad_type'=>$ad_type,
                'ad_results_type_url'=>$ad_results_type_url,
                'ad_category'=>$ad_category,
                'ad_results_category_url'=>$ad_results_category_url,
                'ad_subcategory'=>$ad_subcategory,
                'ad_results_subcategory_url'=>$ad_results_subcategory_url,
                'ad_manufacturer'=>$ad_manufacturer,
                'ad_results_manufacturer_url'=>$ad_results_manufacturer_url,
                'ad_model'=>$ad_model,
                'ad_results_model_url'=>$ad_results_model_url,
                'ad_location'=>$ad_location,
                'ad_dealer'=>$ad_dealer,
                'img_ad_type_src'=>'/assets/theme/images/types/' . $ad_type_rewrite_url . '.png',
                'ad_cost'=>$ad_cost,
            ])
            {{--@if($ad_counter == ceil(count($ads_list)/$modulo) && $ad_banners && isset($datasRequest['results_view']) && 'list' == $datasRequest['results_view'])--}}
            {{--@if($ad_counter % $modulo == 0 && $ad_banners && isset($datasRequest['results_view']) && 'list' == $datasRequest['results_view'])
                <div class="result-item format-standard advertising">
                    <div class="row">
                        <div class="col-sm-12 hidden-xs text-center">
                            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90'])
                        </div>
                    </div>
                </div>
            @endif--}}
            <?php
                $ad_counter ++;
            ?>
        @endforeach

        <div class="row">
            <div class="col-sm-12 text-center">{!! $pagination !!}</div>
        </div>

        @if($ad_banners && $agent->isMobile())
        <div class="advertising">
            <div class="row">
                <div class="col-sm-12 visible-xs text-center">
                    @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
                </div>
            </div>
        </div>
        @endif

    </div>
    @endif

</div>