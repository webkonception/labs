@extends('layouts.theme')

<?php
    $search_query = '';
    if (count($datasRequest)>0) {
        //debug('$datasRequest');
        //debug($datasRequest);

        $search_query           = !empty($datasRequest['query']) ? $datasRequest['query'] : null;

        $sell_type              = !empty($sell_type) ? $sell_type : (!empty($datasRequest['type']) ? $datasRequest['type'] : null);

        $adstype                = !empty($adstype) ? $adstype : (!empty($datasRequest['adstypes_id']) ? Search::getAdsTypeById ($datasRequest['adstypes_id']) : null);
        $category               = !empty($category) ? $category : (!empty($datasRequest['categories_ids']) ? Search::getCategoryById ($datasRequest['categories_ids']) : null);
        $subcategory            = !empty($subcategory) ? $subcategory : (!empty($datasRequest['subcategories_ids']) ? Search::getSubcategoryById ($datasRequest['subcategories_ids']) : null);

        $manufacturer           = !empty($manufacturer) ? $manufacturer : (!empty($datasRequest['manufacturers_id']) ? Search::getManufacturerById ($datasRequest['manufacturers_id']) : null);
        //$manufacturer           = !empty($manufacturer) ? $manufacturer : (!empty($datasRequest['manufacturers_id']) ? Search::getGateWayManufacturerByName($datasRequest['manufacturers_id'], true) : null);
        //@$manufacturerengine     = !empty($manufacturerengine) ? $manufacturerengine : (!empty($datasRequest['manufacturersengines_id']) ? Search::getManufacturerEngineById ($datasRequest['manufacturersengines_id']) : null);
        $model                  = !empty($model) ? $model : (!empty($datasRequest['models_id']) ? Search::getModelById ($datasRequest['models_id']) : null);
        //@$modelengine            = !empty($modelengine) ? $modelengine : (!empty($datasRequest['modelsengines_id']) ? Search::getModelEngineById ($datasRequest['modelsengines_id']) : null);

        $min_year_built            = !empty($min_year_built) ? $min_year_built : (!empty($datasRequest['min_year_built']) ? $datasRequest['min_year_built'] : null);
        $max_year_built            = !empty($max_year_built) ? $max_year_built : (!empty($datasRequest['max_year_built']) ? $datasRequest['max_year_built'] : null);

        $min_length            = !empty($min_length) ? $min_length : (!empty($datasRequest['min_length']) ? $datasRequest['min_length'] : null);
        $max_length            = !empty($max_length) ? $max_length : (!empty($datasRequest['max_length']) ? $datasRequest['max_length'] : null);

        $min_width            = !empty($min_width) ? $min_width : (!empty($datasRequest['min_width']) ? $datasRequest['min_width'] : null);
        $max_width            = !empty($max_width) ? $max_width : (!empty($datasRequest['max_width']) ? $datasRequest['max_width'] : null);
    }

    $total_ads = '';
    $current_page = '';
    if ($ads_list) {
        if(is_object($ads_list['ads_list'])) {
            $total_ads = $ads_list['ads_list']->total();
            $total_pagination = $total_ads . ' ' . trans('pagination.results');
            $current_page = $ads_list['ads_list']->currentPage();
        }
    }
    $title = '';

    if (!empty($search_query)) {
        $title .= '&nbsp;&raquo;&nbsp;' . ucwords($search_query);
    } else {
        $title .= !empty($manufacturer['name']) ? '&nbsp;&raquo;&nbsp;' . $manufacturer['name'] : '';
        $title .= !empty($model['name']) ? '&nbsp;&raquo;&nbsp;' . $model['name'] : '';
        //@$title .= !empty($manufacturerengine['name']) ? '&nbsp;&raquo;&nbsp;' . $manufacturerengine['name'] : '';
        //@$title .= !empty($modelengine['name']) ? '&nbsp;&raquo;&nbsp;' . $modelengine['name'] : '';
    }

    $title .= !empty($adstype['name']) ? '&nbsp;&raquo;&nbsp;' . ucwords($adstype['name']) : '';
    $title .= !empty($category['name']) ? '&nbsp;&raquo;&nbsp;' . ucwords($category['name']) : '';
    $title .= !empty($subcategory['name']) ? '&nbsp;&raquo;&nbsp;' . ucwords($subcategory['name']) : '';

    $title .= !empty($sell_type) ? '&nbsp;&raquo;&nbsp;' . $sell_type . ' ' . trans('metas.sales') : '';

/*
    $title .= !empty($min_year_built) || !empty($max_year_built) ? '&nbsp;&raquo;&nbsp;' . trans('boat_caracts.year') . ' ' : '';
    $title .= !empty($min_year_built) ? trans('boat_caracts.greater_than_or_equal_to_sign') . ' ' . $min_year_built : '';
    $title .= !empty($max_year_built) ? (!empty($min_year_built) ? ' ' . trans('navigation.and_sign') . ' ' : '') . trans('boat_caracts.less_or_equal_to_sign') . ' ' . $max_year_built : '';

    $title .= !empty($min_length) || !empty($max_length) ? '&nbsp;&raquo;&nbsp;' . trans('boat_caracts.length') . ' ' : '';
    $title .= !empty($min_length) ? trans('boat_caracts.greater_than_or_equal_to_sign') . ' ' . $min_length . ' ' . trans('boat_caracts.meter') : '';
    $title .= !empty($max_length) ? (!empty($min_length) ? ' ' . trans('navigation.and_sign') . ' ' : '') . trans('boat_caracts.less_or_equal_to_sign') . ' ' . $max_length . ' ' .trans('boat_caracts.meter') : '';

    $title .= !empty($min_width) || !empty($max_width) ? '&nbsp;&raquo;&nbsp;' . trans('boat_caracts.length') . ' ' : '';
    $title .= !empty($min_width) ? trans('boat_caracts.greater_than_or_equal_to_sign') . ' ' . $min_length . ' ' . trans('boat_caracts.meter') : '';
    $title .= !empty($max_width) ? (!empty($min_width) ? ' ' . trans('navigation.and_sign') . ' ' : '') . trans('boat_caracts.less_or_equal_to_sign') . ' ' . $max_width . ' ' .trans('boat_caracts.meter') : '';
*/
    $title .= !empty($current_page) && $current_page > 1 ?'&nbsp;&raquo;&nbsp;' . 'page ' . $current_page : '';

    $search_critreria = '<ul class="chevrons accent-color clearfix">';
    if (!empty($search_query)) {
        $search_critreria .= '<li class="lead accent-color-danger">' . ucwords($search_query) . '</li>';
    } else {
        $search_critreria .= !empty($manufacturer['name']) ? '<li>' . ucwords($manufacturer['name']) . '</li>' : '';
        $search_critreria .= !empty($model['name']) ? '<li>' . ucwords($model['name']) . '</li>' : '';
        //@$search_critreria .= !empty($manufacturerengine['name']) ? '<li>' . $manufacturerengine['name'] . '</li>' : '';
        //@$search_critreria .= !empty($modelengine['name']) ? '<li>' . $modelengine['name'] . '</li>' : '';
    }
    $search_critreria .= !empty($sell_type) ? '<li>' . ucfirst($sell_type) . ' ' . trans('metas.sales') . '</li>' : '';

    $search_critreria .= !empty($adstype['name']) ? '<li>' . ucwords($adstype['name']) . '</li>' : '';
    $search_critreria .= !empty($category['name']) ? '<li>' . ucwords($category['name']) . '</li>' : '';
    $search_critreria .= !empty($subcategory['name']) ? '<li>' . ucwords($subcategory['name']) . '</li>' : '';

    $search_critreria .= !empty($min_year_built) || !empty($max_year_built) ? '<li>' . trans('boat_caracts.year') . ' ' : '';
    $search_critreria .= !empty($min_year_built) ? trans('boat_caracts.greater_than_or_equal_to') . ' ' . $min_year_built : '';
    $search_critreria .= !empty($max_year_built) ? (!empty($min_year_built) ? ' ' . trans('navigation.and') . ' ' : '') . trans('boat_caracts.less_or_equal_to') . ' ' . $max_year_built : '';
    $search_critreria .= !empty($min_year_built) || !empty($max_year_built) ? '</li>' : '';

    $search_critreria .= !empty($min_length) || !empty($max_length) ? '<li>' . trans('boat_caracts.length') . ' ' : '';
    $search_critreria .= !empty($min_length) ? trans('boat_caracts.greater_than_or_equal_to') . ' ' . $min_length . ' ' . trans('boat_caracts.unit_meter') : '';
    $search_critreria .= !empty($max_length) ? (!empty($min_length) ? ' ' . trans('navigation.and') . ' ' : '') . trans('boat_caracts.less_or_equal_to') . ' ' . $max_length . ' ' . trans('boat_caracts.unit_meter') : '';
    $search_critreria .= !empty($min_length) || !empty($max_length) ? '</li>' : '';

    $search_critreria .= !empty($min_width) || !empty($max_width) ? '<li>' . trans('boat_caracts.width') . ' ' : '';
    $search_critreria .= !empty($min_width) ? trans('boat_caracts.greater_than_or_equal_to') . ' ' . $min_width . ' ' . trans('boat_caracts.unit_meter') : '';
    $search_critreria .= !empty($max_width) ? (!empty($min_width) ? ' ' . trans('navigation.and') . ' ' : '') . trans('boat_caracts.less_or_equal_to') . ' ' . $max_width . ' ' . trans('boat_caracts.unit_meter') : '';
    $search_critreria .= !empty($min_width) || !empty($max_width) ? '</li>' : '';
    $search_critreria .= '</ul>';

    $ad_type = $ad_results_type_url = '';
    $ad_category = $ad_results_category_url = '';
    $ad_subcategory = $ad_subcategory_rewrite_url = '';
    $ad_manufacturer = $ad_results_manufacturer_url = '';
    $ad_model = $ad_results_model_url = '';

    if (is_array($adstype) && !empty($adstype)) {
        $ad_type = $adstype['name'];
        $ad_type_rewrite_url = $adstype['rewrite_url'];
        $ad_results_type_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.' . str_replace('-', '_', $ad_type_rewrite_url));
    }
    if (is_array($category) && !empty($category)) {
        $ad_category = $category['name'];
        $ad_category_rewrite_url = $category['rewrite_url'];
        $ad_results_category_url = $ad_results_type_url . '/' . trans('routes.' . str_replace('-', '_', $ad_category_rewrite_url));
    }
    if (is_array($subcategory) && !empty($subcategory)) {
        $ad_subcategory = $subcategory['name'];
        $ad_subcategory_rewrite_url = $subcategory['rewrite_url'];
        $ad_results_subcategory_url = $ad_results_category_url . '/' . trans('routes.' . str_replace('-', '_', $ad_subcategory_rewrite_url));
    }
    if (is_array($manufacturer) && !empty($manufacturer)) {
        $ad_manufacturer = $manufacturer['name'];
        $ad_manufacturer_rewrite_url = $manufacturer['rewrite_url'];
        $ad_results_manufacturer_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.manufacturers') . '/' . $ad_manufacturer_rewrite_url;
    }
    if (is_array($model) && !empty($model)) {
        $ad_model = $model['name'];
            $ad_model_rewrite_url = $model['rewrite_url'];
        $ad_results_model_url = $ad_results_manufacturer_url . '/' . $ad_model_rewrite_url;
    }
    $ad_pontoon_mooring_title = '';
    $ad_results_pontoon_mooring_url = '';
    if (!preg_match('/engine/i', $ad_type)) {
        $ad_pontoon_mooring_title = trans('search.find_pontoon_mooring');
        $ad_results_pontoon_mooring_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.pontoon_mooring');
    };
?>

@include('theme.partials.elements.search.breadcrumb')

@section('title_page')
    {!! mb_strtoupper(trans('navigation.for_sale')) !!}
    <span>{!! $title !!}</span>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3 search-filters" id="Search-Filters">
        <div class="tbsticky filters-sidebar">
            <h3>{!! trans('navigation.refine_search') !!}</h3>

            {{--@include('theme.partials.elements.search.filters-form', ['block_format'=>'filters-sidebar'])--}}
            @include('theme.partials.elements.search.filters-form-search', ['block_format'=>'filters-sidebar'])

            {{--@include('theme.partials.elements.search.filters-toggle', ['block_format'=>'filters-sidebar'])--}}
            @if($ad_banners)
            @if (!$agent->isMobile())
            <div class="text-center">
                @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
                <hr>
            </div>
            @elseif ($agent->isMobile())
            <div class="text-center hidden-xs hidden-sm">
                @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
                <hr>
            </div>
            @endif
            @endif
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-9 results-container">
        @if($total_ads>0)
            @if (!$agent->isMobile())
            <div class="row">
                <div class="col-md-12">
                    @include('theme.partials.elements.search.search-form-light')
                    <hr>
                </div>
            </div>
            @endif
            @if (!$agent->isMobile())
            @include('theme.partials.elements.block.bod-push-block')
            @endif
            @include('theme.partials.elements.results', $ads_list)
            @if($current_page > 1)
            @include('theme.partials.elements.block.bod-push-block')
            @endif
        @else
            @if (!$agent->isMobile())
        <div class="row">
            <div class="col-md-12">
                @include('theme.partials.elements.search.search-form-light')
                <hr>
            </div>
        </div>
            @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="well">
                    <p>
                        <strong class="accent-color-danger">{!! trans('search.no_offer_available') !!}</strong>
                    </p>

                    <div class="well well-white clearfix">
                        {!! trans('search.you_are_looking_for', ['search_criteria'=> $search_critreria]) !!}

                        <p>
                            {!! trans('search.you_can_submit_a_search') !!} {!! trans('navigation.on') !!} <strong class="strong lead accent-color-danger uppercase">{!! trans('navigation.boat_on_demand') !!}</strong>
                            <span class="pull-right">{!! link_trans_route('boat_on_demand', 'navigation.submit_your_search', ['class'=>'btn btn-block btn-primary']) !!}</span>
                            <br>
                            {!! trans('search.customers_will_contact') !!}.
                        </p>
                    </div>
                    <div class="spacer-20"></div>
                    <div class="well well-white clearfix">
                        <h3>{!!  trans('search.perform_new_search') !!}</h3>
                        <ul class="chevrons">
                            {!! !empty($search_query) ? '<li class="col-sm-6"><a href="'. trans_route($currentLocale, 'routes.for_sale') . '/' . '?query='. $search_query . '" title="' . trans('search.global_search') . ' ' . trans('navigation.for') . ' ' . $search_query . '">' . trans('search.global_search') . ' ' . trans('navigation.for') . ' &laquo; ' . $search_query . ' &raquo;</a></li>' : '' !!}

                            {!! isset($ad_results_manufacturer_url) && !empty($ad_results_manufacturer_url) ? '<li class="col-sm-6"><a href="' . $ad_results_manufacturer_url . '" title="' . trans('search.manufacturer_ads') . ' ' . $ad_manufacturer . '">' . trans('search.manufacturer_ads') . ' ' . $ad_manufacturer . '</a></li>': '' !!}

                            {!! isset($ad_results_model_url) && !empty($ad_results_model_url) ? '<li class="col-sm-6"><a href="' . $ad_results_model_url . '" title="' . trans('search.model_ads') . ' ' . $ad_model . '">' . trans('search.model_ads') . ' ' . $ad_model . '</a></li>' : '' !!}

                            {!! isset($ad_pontoon_mooring_title) && !empty($ad_pontoon_mooring_title) ? '<li class="col-sm-6"><a href="' . $ad_results_pontoon_mooring_url . '" title="' . $ad_pontoon_mooring_title . '">' . $ad_pontoon_mooring_title . '</a></li>' : '' !!}

                            {!! isset($ad_type) && !empty($ad_type) ? '<li class="col-sm-6"><a href="' . $ad_results_type_url . '" title="' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_type . '">' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_type . '</a></li>' : '' !!}

                            {!! isset($ad_category) && !empty($ad_category) ? '<li class="col-sm-6"><a href="' . $ad_results_category_url . '" title="' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_category . '">' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_category . '</a></li>' : '' !!}

                            {!! isset($ad_subcategory) && !empty($ad_subcategory) ? '<li class="col-sm-6"><a href="' . $ad_results_subcategory_url . '" title="' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_subcategory . '">' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_subcategory . '</a></li>' : '' !!}

                            {{--{!! isset($ad_manufacturer) && !empty($ad_manufacturer) && isset($ad_model) && !empty($ad_model) ? '<li class="col-sm-6">' . link_trans_route('sell', trans('navigation.sell') . ' ' . $ad_manufacturer . ' ' . $ad_model) . '</li>' : '' !!}--}}
                            {{--{!! isset($ad_manufacturer) && !empty($ad_manufacturer) && !isset($ad_model) ? '<li class="col-sm-6">' . link_trans_route('sell', trans('navigation.sell') . ' ' . $ad_manufacturer) . '</li>' : '' !!}--}}
                            {{--{!! !isset($ad_manufacturer) && isset($ad_model) && !empty($ad_model) ? '<li class="col-sm-6">' . link_trans_route('sell', trans('navigation.sell') . ' ' . $ad_model) . '</li>' : '' !!}--}}
                            {{--<li class="col-sm-6"><a href="{{ url(trans_route($currentLocale, 'routes.sell')) }}" title="{!! trans('navigation.sell') . ' ' . $ad_manufacturer . ' ' . $ad_model !!}">{!! trans('navigation.sell') . ' ' . $ad_manufacturer . ' ' . $ad_model !!}</a></li>--}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

<?php
    $_countryCode = (null !== config('youboat.' . $country_code . '.country_code')) ? config('youboat.' . $country_code . '.country_code') : 'GB';
    $country = Search::getCountry($_countryCode)['name'];

    $ad_type = '';
    if (isset($adstype) && is_array($adstype) && !empty($adstype['name'])) {
        $ad_type = $adstype['name'];
    }

    $ad_category = '';
    if (isset($category) && is_array($category) && !empty($category['name'])) {
        $ad_category = $category['name'];
    }

    $ad_subcategory = '';
    if (isset($subcategory) && is_array($subcategory) && !empty($subcategory['name'])) {
        $ad_subcategory = $subcategory['name'];
    }

    $ad_manufacturer = '';
    if (isset($manufacturer) && is_array($manufacturer) && !empty($manufacturer['name'])) {
        $ad_manufacturer = $manufacturer['name'];
    } /*@else if (isset($manufacturerengine) && is_array($manufacturerengine) && !empty($manufacturerengine['name'])) {
        $ad_manufacturer = $manufacturerengine['name'];
    }@*/

    $ad_model = '';
    if (isset($model) && is_array($model) && !empty($model['name'])) {
        $ad_model = $model['name'];
    } /*@else if (isset($modelengine) && is_array($modelengine) && !empty($modelengine['name'])) {
        $ad_model = $modelengine['name'];
    }@*/

    $total_ads = $total_ads > 0 ? $total_ads : '';

    //<title>Jeanneau Merry Fisher 635 occasion - Annonces de bateau en vente</title>
    //<title>uk.YouBoat.com | For sale Ribtec 12M Shelter Deck Rib</title>
    $metas_title = '' ;
    $metas_title .= !empty($ad_manufacturer) ? $ad_manufacturer : '';
    $metas_title .= !empty($ad_model) ? ' ' . $ad_model : '';
    $metas_title .= !empty($ad_type) ? (!empty($metas_title) ? ' - ' : '') . $ad_type : '';
    $metas_title .= !empty($ad_category) ? (!empty($metas_title) ? ' - ' : '') . $ad_category : '';
    $metas_title .= !empty($ad_subcategory) ? (!empty($metas_title) ? ' - ' : '') . $ad_subcategory : '';

    /*$metas_title .= !empty($ad_type) ?
            (!empty($metas_title) ? ' -> ' : '') . $ad_type . (!empty($ad_category) ? ' ' . trans('metas.in') . ' ' . $ad_category : '') . (!empty($ad_subcategory) ? ' - ' . $ad_subcategory: '' ) :
            (!empty($metas_title) ? ' - ' : '') . (!empty($ad_category) ? $ad_category : '') . (!empty($ad_subcategory) ? ' - ' . $ad_subcategory : '' );
    */
    $metas_title .= !empty($min_length) ? (!empty($metas_title) ? ' - ' : '') . trans('boat_caracts.less_or_equal_to') . ' ' . $min_length . ' ' . trans('boat_caracts.unit_meter') : '';
    $metas_title .= !empty($sell_type) ? (!empty($metas_title) ? ' - ' : '') . $sell_type : '';
    $metas_title .= (!empty($metas_title) ? ' | ' : '') . ucfirst(trans('metas.ads')) . ' ' . mb_strtolower(trans('navigation.for_sale'));
    $metas_title .= (!empty($metas_title) ? ' - ' : '') . $website_name;
    $metas_title .= !empty($current_page) && $current_page > 1 ? ' - page ' . $current_page : '';
    $metas_title .= !empty($search_query) ? ' - ' . $search_query : '';

    //<meta name="description" content="Vente de &#8618; Jeanneau Merry Fisher 635.
    // Consultez les 12 meilleures offres de bateau Jeanneau Merry Fisher 635 occasion à prix imbattable en vente auprès des professionnels du nautisme Francais." />
    $metas_description = '';
    $metas_description .= (!empty($ad_manufacturer) || !empty($ad_model)) ? ucfirst(mb_strtolower(trans('navigation.for_sale'))) . (!empty($ad_manufacturer) ? ' ' . $ad_manufacturer : '') . (!empty($ad_model) ? ' ' . $ad_model : '') . '. ' : '';
    $metas_description .= (!empty($ad_manufacturer) || !empty($ad_model)) ?
    ucfirst(trans_choice('metas.see_the_best_deals', ($total_ads == 0) ? 1 : $total_ads, ['number'=>$total_ads])) . ' ' . trans('metas.of') . ' ' . $ad_manufacturer . (!empty($ad_model) ? ' ' . $ad_model : '') . (!empty($sell_type) ? ' ' . trans('metas.for') . ' ' . $sell_type . ' ' . trans('metas.sales') : '')  :
    ucfirst(trans_choice('metas.see_the_best_deals', ($total_ads == 0) ? 1 : $total_ads, ['number'=>$total_ads])) . (!empty($sell_type) ? ' ' . trans('metas.for') . ' ' . $sell_type . ' ' . trans('metas.sales') : '') . (!empty($ad_type) ? ' ' . trans('metas.for') . ' ' . $ad_type : '') . (!empty($ad_category) ? ' ' . trans('metas.in') . ' ' . $ad_category : '');
    $metas_description .= ' ' . trans('metas.at_unbeatable_price') . ' ' . mb_strtolower(trans('navigation.for_sale')) . ' ' . trans('metas.among_the_marine_professionals', ['country'=>$country]) . '.';

    /*$metas_keywords = '';
    $metas_keywords .= !empty($ad_type) ? trans('navigation.for_sale') . ' ' . $ad_type : '';
    $metas_keywords .= !empty($ad_category) ? ', ' . $ad_category : '';
    $metas_keywords .= !empty($ad_manufacturer) ? ', ' . $ad_manufacturer : '';
    $metas_keywords .= !empty($ad_model) ? ', ' . $ad_model : '';
    */
    $metas = [
        'metas_title' => $metas_title,
        'metas_description' => $metas_description
        //,'metas_keywords' => $metas_keywords
    ];
?>

@section('metas')
@include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('javascript')
    {{--@if($errors->any() || Session::has('errors') || Session::has('message') || Session::has('newsletter_message') || Session::has('search_notification_message'))
        <script>$(document).ready(function(){$("#msgModal").modal('show');});</script>
    @endif--}}
    <script src="{!! asset(config('assets.js.youboat_filters.common.default.url')) !!}" defer></script>
@endsection