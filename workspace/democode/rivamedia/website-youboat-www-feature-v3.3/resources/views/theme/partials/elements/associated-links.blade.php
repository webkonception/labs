<?php
    $ad_pontoon_mooring_title = '';
    $ad_results_pontoon_mooring_url = '';
    if (isset($ad_type) && !preg_match('/engine/i', $ad_type)) {
        $ad_pontoon_mooring_title = trans('search.find_pontoon_mooring');
        $ad_results_pontoon_mooring_url = trans_route($currentLocale, 'routes.for_sale') . '/' . trans('routes.pontoon_mooring');
    };
?>
<ul class="chevrons col-sm-6">
    {!! isset($ad_results_manufacturer_url) && !empty($ad_results_manufacturer_url) ? '<li><a href="' . $ad_results_manufacturer_url . '" title="' . trans('search.manufacturer_ads') . ' ' . $ad_manufacturer . '">' . trans('search.manufacturer_ads') . ' ' . $ad_manufacturer . '</a></li>': '' !!}

    {!! isset($ad_results_model_url) && !empty($ad_results_model_url) ? '<li><a href="' . $ad_results_model_url . '" title="' . trans('search.model_ads') . ' ' . $ad_model . '">' . trans('search.model_ads') . ' ' . $ad_model . '</a></li>' : '' !!}

    {!! isset($ad_model) && !empty($ad_model) ? '<li>' . link_trans_route('boat_on_demand', trans('navigation.boat_on_demand') . ' ' . trans('navigation.for') . ' ' . $ad_model, ['class'=>"accent-color-danger strong"]) .'</li>' : '<li>' . link_trans_route('boat_on_demand', trans('navigation.boat_on_demand'), ['class'=>"accent-color-danger strong"]) .'</li>' !!}
</ul>
<ul class="chevrons col-sm-6">
    {!! isset($ad_pontoon_mooring_title) && !empty($ad_pontoon_mooring_title) ? '<li><a href="' . $ad_results_pontoon_mooring_url . '" title="' . $ad_pontoon_mooring_title . '">' . $ad_pontoon_mooring_title . '</a></li>' : '' !!}

    <li>{!! link_trans_route('for_sale', 'search.perform_new_search') !!}</li>

    {!! isset($ad_type) && !empty($ad_type) ? '<li><a href="' . $ad_results_type_url . '" title="' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_type . '">' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_type . '</a></li>' : '' !!}

    {!! isset($ad_category) && !empty($ad_category) ? '<li><a href="' . $ad_results_category_url . '" title="' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_category . '">' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_category . '</a></li>' : '' !!}

    {!! isset($ad_subcategory) && !empty($ad_subcategory) ? '<li><a href="' . $ad_results_subcategory_url . '" title="' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_subcategory . '">' . trans('search.other_ads') . ' ' . trans('navigation.for') . ' ' . $ad_subcategory . '</a></li>' : '' !!}
</ul>
{{--<ul class="chevrons col-sm-4">--}}
    {{--{!! isset($ad_manufacturer) && !empty($ad_manufacturer) && isset($ad_model) && !empty($ad_model) ? '<li>' . link_trans_route('sell', trans('navigation.sell') . ' ' . $ad_manufacturer . ' ' . $ad_model) . '</li>' : '' !!}--}}
    {{--{!! isset($ad_manufacturer) && !empty($ad_manufacturer) && !isset($ad_model) ? '<li>' . link_trans_route('sell', trans('navigation.sell') . ' ' . $ad_manufacturer) . '</li>' : '' !!}--}}
    {{--{!! !isset($ad_manufacturer) && isset($ad_model) && !empty($ad_model) ? '<li>' . link_trans_route('sell', trans('navigation.sell') . ' ' . $ad_model) . '</li>' : '' !!}--}}
    {{--<li><a href="{{ url(trans_route($currentLocale, 'routes.sell')) }}" title="{!! trans('navigation.sell') . ' ' . $ad_manufacturer . ' ' . $ad_model !!}">{!! trans('navigation.sell') . ' ' . $ad_manufacturer . ' ' . $ad_model !!}</a></li>--}}
{{--</ul>--}}
