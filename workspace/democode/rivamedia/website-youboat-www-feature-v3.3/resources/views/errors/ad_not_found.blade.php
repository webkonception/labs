<?php

$routeParameters = request()->route()->parameters();
if(isset($errors) && !empty($errors)) {
    $errMsg = $errors->messages();
    if(!empty($errMsg)) {
        $routeParameters = $errMsg["routeParameters"];
        $error_code = $routeParameters["error_code"];
    }
}

if (is_array($routeParameters) && count($routeParameters) > 0) {
    $ad_type = '';
    $ad_type_rewrite_url = '';
    $ad_results_type_url = '';
    if (!empty($routeParameters['adstypes'])) {
        $ad_type = Search::getAdsType($routeParameters['adstypes']);
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

    $category = '';
    if (!empty($routeParameters['categories'])) {
        $category = Search::getCategory($routeParameters['categories']);
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
    if (!empty($routeParameters['subcategories'])) {
        $subcategory = Search::getCategory($routeParameters['subcategories']);
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
    if (!empty($routeParameters['ad_title']) && empty($routeParameters['manufacturers'])) {
        $routeParameters['manufacturers'] = $routeParameters['ad_title'];
        $routeParameters['models'] = $routeParameters['ad_id'];
    } else if ($is_engine && !empty($routeParameters['ad_title']) && empty($routeParameters['manufacturersengines'])) {
        $routeParameters['manufacturersengines'] = $routeParameters['ad_title'];
        $routeParameters['modelsengines'] = $routeParameters['ad_id'];
    } else if ($is_engine && !empty($routeParameters['manufacturers'])) {
        $routeParameters['manufacturersengines'] = $routeParameters['manufacturers'];
        $routeParameters['modelsengines'] = $routeParameters['models'];
    }

    /*if (!empty($routeParameters['manufacturers'])) {
        $manufacturer = Search::getGateWayManufacturerByName($routeParameters['manufacturers']);
    } else if ($is_engine && !empty($routeParameters['manufacturers'])) {
        $manufacturer = Search::getGateWayManufacturerEngineByName($routeParameters['manufacturers']);
        $manufacturerengine = $manufacturer;
    } else if (!empty($routeParameters['manufacturersengines'])) {
        $manufacturer = Search::getGateWayManufacturerEngineByName($routeParameters['manufacturersengines']);
        $manufacturerengine = $manufacturer;
    }*/
    if ($is_engine && !empty($routeParameters['manufacturers'])) {
        $manufacturer = Search::getGateWayManufacturerEngineByName($routeParameters['manufacturers']);
        $manufacturerengine = $manufacturer;
    } else if (!empty($routeParameters['manufacturers'])) {
        $manufacturer = Search::getGateWayManufacturerByName($routeParameters['manufacturers']);
    } else if (!empty($routeParameters['manufacturersengines'])) {
        $manufacturer = Search::getGateWayManufacturerEngineByName($routeParameters['manufacturersengines']);
        $manufacturerengine = $manufacturer;
    }

    $ad_manufacturer = '';
    $ad_manufacturer_rewrite_url = '';
    $ad_results_manufacturer_url = '';
    if(is_array($manufacturer) && !empty($manufacturer['name'])) {
        $ad_manufacturer_rewrite_url = $manufacturer['rewrite_url'];
        $ad_manufacturer = $manufacturer['name'];
        $ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer') . '/' . $ad_manufacturer_rewrite_url;
        //$ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . '?query=' . str_slug(str_replace('/',' ', $ad_manufacturer), ' ');
    } else if (!empty($routeParameters['manufacturers'])) {
        $ad_manufacturer_rewrite_url = $routeParameters['manufacturers'];
        $ad_manufacturer = str_slug($ad_manufacturer_rewrite_url, ' ');
        $ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_manufacturer') . '/' . $ad_manufacturer_rewrite_url;
        //$ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . '?query=' . str_slug(str_replace('/',' ', $ad_manufacturer), ' ');
    }

    $model = '';
    if (!empty($routeParameters['models']) && !empty($routeParameters['manufacturers'])) {
        $model_name = str_slug(str_replace('/',' ', $routeParameters['models']), ' ');
        $model = Search::getGateWayModelByName($model_name, true);
        //$model = Search::getModel($routeParameters['models']);
        //$model = Search::getModelByName($manufacturer['id'],$routeParameters['models'],true,false);
    } else if (!empty($routeParameters['models'])) {
        $model_name = str_slug(str_replace('/',' ', $routeParameters['models']), ' ');
        $model = Search::getGateWayModelByName($model_name, true);
        //$model = Search::getModel($routeParameters['models']);
    } else if ($is_engine && !empty($routeParameters['models'])) {
        $model_name = str_slug(str_replace('/',' ', $routeParameters['models']), ' ');
        $model = Search::getGateWayModelByName($model_name, true);
        //$model = Search::getModelEngine($routeParameters['models']);
        $modelengine = $model;
    } else if (!empty($routeParameters['modelsengines'])) {
        $model_name = str_slug(str_replace('/',' ', $routeParameters['modelsengines']), ' ');
        $model = Search::getGateWayModelByName($model_name, true);
        //$model = Search::getModelEngine($routeParameters['modelsengines']);
        $modelengine = $model;
    }
    $ad_model = '';
    $ad_model_rewrite_url = '';
    $ad_results_model_url = '';
    if(is_array($model) && !empty($model['name'])) {
        $ad_model_rewrite_url = $model['rewrite_url'];
        $ad_model = $model['name'];
        $ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model') . '/' . $ad_model_rewrite_url;
        //$ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . '?query=' . str_slug(str_replace('/',' ', $ad_model), ' ');
    } else if (!empty($routeParameters['manufacturers']) && !empty($routeParameters['models'])) {
        $ad_model_rewrite_url = $routeParameters['models'];
        $ad_model = str_slug($ad_model_rewrite_url, ' ');
        //$ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . $ad_type_rewrite_url . '?query=' . str_slug(str_replace('/',' ', $ad_manufacturer), ' ') . '+' .  str_slug(str_replace('/',' ', $ad_model), ' ');
        //$ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . $ad_type_rewrite_url . '?query=' . str_slug(str_replace('/',' ', $ad_model), ' ');
        $ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.by_model') . '/' . $ad_model_rewrite_url;
        //$ad_results_model_url = trans_route($currentLocale, 'routes.for_sale') . '/' . '?query=' . str_slug(str_replace('/',' ', $ad_model), ' ');
    }

    $ad_pontoon_mooring_title = '';
    $ad_results_pontoon_mooring_url = '';
    if (!$is_engine) {
        $ad_pontoon_mooring_title = trans('search.find_pontoon_mooring');
        $ad_results_pontoon_mooring_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.pontoon_mooring');
    };
    $ads_list = Search::getAdsList($routeParameters);
    if ($ads_list) {
        $ads_list = json_decode(json_encode($ads_list), true)['ads_list']['data'];
    }
} else {
    header('Location: '. url(trans_route($currentLocale, 'routes.for_sale')));
}
?>
@extends('layouts.theme')

@section('content')
    <div class="container ad_not_found">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="well well-white clearfix accent-color">
                    @if(isset($error_code))
                    <h3 class="strong text-danger"><i class="fa fa-warning fa-fw"></i>{!! trans('errors/' . $error_code .'.error_message') !!}</h3>
                    {!! trans('errors/' . $error_code .'.error_detail') !!}
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="well well-white clearfix">
                    <h3>{!!  trans('navigation.search_ads') !!} & {!! trans('navigation.services') !!}</h3>
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
        @if (isset($ads_list) && is_array($ads_list) && $ads_list > 0)
            <div class="row">
                <div class="col-md-12">
                    @include('theme.partials.elements.recent-ads', ['data_columns'=>3, 'data_items_desktop'=>3, 'data_items_desktop_small'=>2,
                    'ads_list'=>$ads_list, 'ads_title_block' => 'Related Ads'])
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                {!! htmlspecialchars_decode(link_to(
                    '/',
                    trans('navigation.back_to') . ' ' . trans('navigation.search'),
                    ['class' => 'btn btn-default', 'title' => trans('navigation.back_to') . ' ' . trans('navigation.search')]
                )) !!}
            </div>
        </div>
    </div>
@endsection