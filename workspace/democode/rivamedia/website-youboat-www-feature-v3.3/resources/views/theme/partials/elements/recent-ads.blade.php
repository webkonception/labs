<?php
    $pricing_currency = (null !== config('youboat.' . $country_code . '.currency')) ? config('youboat.' . $country_code . '.currency') : trans('pricing.currency');
    $_countryCode = (null !== config('youboat.' . $country_code . '.country_code')) ? config('youboat.' . $country_code . '.country_code') : 'GB';
    $locale = Search::getCountryLocaleCode($_countryCode);
    setlocale(LC_MONETARY, $locale);
    $ads_title_block = !empty($ads_title_block) ? $ads_title_block : trans('elements.recent-ads.title');
?>
@if (isset($ads_list) && is_array($ads_list) && count($ads_list) > 0)
<section class="listing-block recent-vehicles">
    <div class="listing-header">
        <h3>{!! $ads_title_block !!}</h3>
    </div>
    <div class="listing-container">
        <div class="carousel-wrapper">
            <div class="row">
                <ul class="owl-carousel carousel-fw" id="vehicle-slider" data-columns="{!! isset($data_columns) ? $data_columns : 3 !!}" data-autoplay="{!! isset($data_autoplay) ? $data_autoplay : 4000 !!}" data-pagination="yes" data-arrows="no" data-single-item="no" data-items-desktop="{!! isset($data_items_desktop) ? $data_items_desktop : 3 !!}" data-items-desktop-small="{!! isset($data_items_desktop_small) ? $data_items_desktop_small : 3 !!}" data-items-tablet="{!! isset($data_items_tablet) ? $data_items_tablet : 2 !!}" data-items-mobile="{!! isset($data_items_mobile) ? $data_items_mobile : 1 !!}">
                    @foreach ($ads_list as $ad)
                    @if(isset($ad['id']))
                    <li class="item">
                    <?php
                        $ad_referrer = '';
                        if (isset($ad['ad_referrer']) && !empty($ad['ad_referrer'])) {
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
                            $ad_subcategory = Search::getCategoryById($ad['subcategories_ids']);
                        }
                        if(is_array($ad_subcategory) && isset($ad_subcategory['name'])) {
                            $ad_subcategory_rewrite_url = $ad_subcategory['rewrite_url'];
                            $ad_subcategory = $ad_subcategory['name'];
                            $ad_results_subcategory_url = $ad_results_category_url . '/' . trans('routes.' . str_replace('-', '_', $ad_subcategory_rewrite_url));
                        }

                        $ad_meta = '';

                        $ad_manufacturer = '';
                        $ad_manufacturer_rewrite_url = '';
                        $ad_results_manufacturer_url = '';
                        if (isset($ad['manufacturers_id']) && $ad['manufacturers_id'] != 0) {
                            $ad_manufacturer = Search::getManufacturerById($ad['manufacturers_id']);
                        } else if (isset($ad['manufacturersengines_id']) && $ad['manufacturersengines_id'] != 0) {
                            $ad_manufacturer = Search::getManufacturerEngineById($ad['manufacturersengines_id']);
                        }
                        if(is_array($ad_manufacturer) && isset($ad_manufacturer['name'])) {
                            $ad_manufacturer_rewrite_url = $ad_manufacturer['rewrite_url'];
                            $ad_manufacturer = $ad_manufacturer['name'];
                            $ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.manufacturers') . '/' . $ad_manufacturer_rewrite_url;
                        } else if (isset($ad['ad_manufacturer_name']) && !empty($ad['ad_manufacturer_name'])) {
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
                        }
                        if(is_array($ad_model) && isset($ad_model['name'])) {
                            $ad_model_rewrite_url = $ad_model['rewrite_url'];
                            $ad_model = $ad_model['name'];
                            $ad_results_model_url = $ad_results_manufacturer_url . '/' . $ad_model_rewrite_url;
                        } else if (isset($ad['ad_model_name']) && !empty($ad['ad_model_name'])) {
                            $ad_model_rewrite_url = str_slug($ad['ad_model_name']);
                            $ad_model = $ad['ad_model_name'];
                            $ad_results_model_url = $ad_results_manufacturer_url . '/' . $ad_model_rewrite_url;
                        }
                        $ad_meta = ucwords($ad_meta);

                        $ad_dealer = '';
                        $dealer = '';
                        if (isset($ad['dealerscaracts_id']) && $ad['dealerscaracts_id'] != 0) {
                            $dealer = Search::getDealerCaractsById($ad['dealerscaracts_id']);
                        } else if (isset($ad['ad_dealer_name']) && !empty($ad['ad_dealer_name'])) {
                            $ad_dealer = $ad['ad_dealer_name'];
                        }
                        if(is_array($dealer) && isset($dealer['name'])) {
                            $ad_dealer = $dealer['name'];
                        }

                        $ad_title = '';
                        if (!empty($ad_manufacturer) && !empty($ad_model)) {
                            $ad_title =  title_case($ad_manufacturer . ' ' . $ad_model);
                        } else if (isset($ad['ad_title']) && !empty($ad['ad_title'])) {
                            $ad_title =  title_case($ad['ad_title']);
                        } else if (!empty($ad_manufacturer) || !empty($ad_model)) {
                            if (!empty($ad_manufacturer)) {
                                $ad_title =  title_case($ad_manufacturer);
                            }
                            if (!empty($ad_model)) {
                                $ad_title =  title_case($ad_model);
                            }
                        }

                        $ad_sell_type = '';
                        if (isset($ad['sell_type']) && !empty($ad['sell_type'])) {
                            $ad_sell_type =  $ad['sell_type'];
                        }

                        $ad_img_src = '';
                        if (isset($ad['ad_photo']) && !empty($ad['ad_photo'])) {
                            $ad['ad_photo'] = preg_replace('/;$/', '', $ad['ad_photo']);
                            $ad_img_src = $ad['ad_photo'];
                        } else if (isset($ad['id']) && $ad['id'] != 0) {
                            $array = Search::getSomethingById('gateway_ads_details', $ad['id'], 'ad_photos');
                            if(is_array($array) && count($array) >0) {
                                $ad_img_src = getFirstElement(array_get($array[0], 'ad_photos'));
                            }
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
                        }
                        //debug('$ad_results_type_url');
                        //debug($ad_results_type_url);
                        //debug('$ad_url');
                        //debug($ad_url);

                        $ad_cost = '';
                        if (isset($ad['ad_price']) && !empty($ad['ad_price'])) {
                            $ad_price = $ad['ad_price'];
                            //$ad_cost = trim(preg_replace('!\s+!', ' ', money_format('%.2n', $ad_price))); // with two decimal
                            //$ad_cost = is_numeric($ad_price) ? trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $ad_price))) : trim(preg_replace('!\s+!', ' ', $ad_price));
                            //$ad_cost = is_numeric($ad_price) ? formatPrice($ad_price, $ad['countries_id']) : trim(preg_replace('!\s+!', ' ', $ad_price));
                            $ad_cost = is_numeric($ad_price) ? formatPriceCurrency($ad_price, $ad['countries_id']) : trim(preg_replace('!\s+!', ' ', $ad_price));
                        }
                        $getCountryById = Search::getCountryById($ad['countries_id'], false);
                        $ad_country_code = array_key_exists('code', $getCountryById) && !empty($getCountryById['code']) ? $getCountryById['code'] : '';

                        $ad_premium_listing = false;
                    ?>
                        @include('theme.partials.elements.block.ads-block', [
                            'block_format'=>'format-standard',
                            'ad_country_code'=>$ad_country_code,
                            'ad_id'=>$ad['id'],
                            'ad_url'=>$ad_url,
                            'ad_img_src'=>$ad_img_src,
                            'ad_title'=>$ad_title,
                            'ad_age'=>'2014',
                            'ad_premium_listing'=>$ad_premium_listing,
                            'ad_meta'=>$ad_meta,
                            'ad_dealer'=>$ad_dealer,
                            'ad_sell_type'=>$ad_sell_type,
                            'ad_type'=>$ad_type,
                            'ad_results_type_url'=>$ad_results_type_url,
                            'ad_category'=>$ad_category,
                            'ad_results_category_url'=>$ad_results_category_url,
                            'ad_subcategory'=>$ad_subcategory,
                            'ad_results_subcategory_url'=>$ad_results_subcategory_url,
                            'img_ad_type_src'=>'assets/theme/images/ads_types/' . $ad_type_rewrite_url . '.png',
                            'ad_cost'=>$ad_cost,
                        ])
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
    @endif
