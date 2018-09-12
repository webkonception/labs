@if(session()->has('data') && session('data')['logout'])
    {{session_unset()}}
    {{setcookie('ybcom_session', "", -1, "/")}}
@endif
<?php
    if ($ads_list) {
        $ads_list = json_decode(json_encode($ads_list), true);
        $total_ads = $ads_list['ads_list']['total'];
        $total_used_boats = $ads_list['ads_list']['data']['total_used_boats'];
        $total_new_boats = $ads_list['ads_list']['data']['total_new_boats'];
        $ads_list = $ads_list['ads_list']['data'];
        //$data = array_map('array_filter_recursive', $ads_list);
        //$ads_list = array_filter($data);
    }
?>
@extends('layouts.theme')

@section('content')
    @if (isset($ads_list) && is_array($ads_list) && $ads_list > 0)
    @include('theme.partials.elements.recent-ads', ['data_columns'=>4, 'ads_list'=>$ads_list])
    @endif

    @if(!$agent->isMobile())
    <div class="spacer-20"></div>
    <div class="row welcome">
        <div class="{!! $ad_banners ? 'col-sm-6 col-md-8' : 'col-sm-12 col-md-12' !!}">
            <div class="panel">
                <div class="panel-body bg-warning text-center">
                    <div class="col-md-12">
                        <h3 class="title strong uppercase">{!! trans('boat_on_demand.looking_for') !!}</h3>
                    </div>
                    <div class="col-md-12">
                        <p class="lead">{!!  trans('boat_on_demand.title') !!} <br><span class="strong bg-success uppercase text-success">&nbsp;&nbsp;{!! trans('boat_on_demand.service_completely_free') !!}&nbsp;&nbsp;</span></p>
                    </div>
                    <div class="col-md-12">
                        {!! link_trans_route('boat_on_demand', 'navigation.boat_on_demand', ['class'=>'btn btn-danger btn-lg big']) !!}
                    </div>
                </div>
            </div>
        </div>
        @if($ad_banners)
        <div class="col-sm-6 col-md-4 text-center">
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
        </div>
        @endif
    </div>
    @endif

    @if($agent->isMobile() && $ad_banners)
    <div class="row">
        <div class="col-sm-12 hidden-xs text-center">
            <div class="spacer-20"></div>
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90'])
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <hr>
            @include('theme.partials.elements.latest-news', ['data_columns'=>3, 'items'=> !empty($latest_news) ? $latest_news : []])
            <hr>
        </div>
    </div>

    @if($ad_banners)
        @if(!$agent->isMobile())
        <div class="col-sm-12 hidden-xs text-center">
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90'])
            <div class="spacer-20"></div>
        </div>
        <div class="col-sm-12 visible-xs text-center">
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
        </div>
        @elseif($agent->isMobile())
        <div class="col-sm-12 visible-xs text-center">
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
        </div>
        @endif
    @endif

    <hr>

    @if(!$agent->isMobile())
        @include('theme.partials.elements.welcome')
    @endif

    @if($agent->isMobile())
    <div class="row welcome">
        <div class="{!! $ad_banners ? 'col-sm-7' : 'col-sm-12' !!}">
            <div class="panel">
                <div class="panel-body bg-warning text-center">
                    <div class="col-md-12">
                        <h3 class="title strong uppercase">{!! trans('boat_on_demand.looking_for') !!}</h3>
                    </div>
                    <div class="col-md-12">
                        <p class="lead">{!!  trans('boat_on_demand.title') !!} <br><span class="strong bg-success uppercase text-success">&nbsp;&nbsp;{!! trans('boat_on_demand.service_completely_free') !!}&nbsp;&nbsp;</span></p>
                    </div>
                    <div class="col-md-12">
                        {!! link_trans_route('boat_on_demand', 'navigation.boat_on_demand', ['class'=>'btn btn-danger btn-md big']) !!}
                    </div>
                </div>
            </div>
        </div>
        @if($ad_banners)
        <div class="col-sm-5 hidden-xs text-center">
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
        </div>
        @endif
    @endif
    <div class="spacer-20"></div>

    {{--@include('theme.partials.elements.submit-ad')--}}
    {{--<div class="spacer-40"></div>--}}
    @include('theme.partials.elements.search-by-manufacturer')

    @include('theme.partials.elements.latest-testimonials', ['data_columns'=>3])
    {{--@include('theme.partials.elements.connect-with-us', ['block_format'=>'two-col'])--}}
@endsection

@section('javascript')
    <script src="{!! asset(config('assets.js.youboat_filters.common.default.url')) !!}" defer></script>
@endsection