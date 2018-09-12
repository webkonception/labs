@extends('layouts.theme')
<?php
    $email              = isset($email) ? $email : (Session::has('email') ? Session::get('email') : '');
    $metas_title        = trans('navigation.dashboard') . ' | ' . trans('navigation.login');

    $metas_description  = 'Login to Dashboard';
    //$metas_keywords = '';
    $metas              = [
            'metas_title' => $metas_title . ' - ' . $website_name,
            'metas_description' => $metas_description
        //,'metas_keywords' => $metas_keywords
    ];
    $breadcrumb         = '<ol class="breadcrumb">';
    $breadcrumb         .= '<li><a href="' . url('/') . '" title="' . trans('navigation.home') . '">' . trans('navigation.home') . '</a></li>';
    $breadcrumb         .= '<li class="active">' . $metas_title . '</li>';
    $breadcrumb         .= '</ol>';

    $boatgest_link = link_to('https://www.boatgest.com', 'BoatGest.com', ['class'=>'link uppercase strong accent-color blank']);

    $_countryCode = (null !== config('youboat.' . $country_code . '.country_code')) ? config('youboat.' . $country_code . '.country_code') : 'GB';
    $locale = Search::getCountryLocaleCode($_countryCode);
    setlocale(LC_MONETARY, $locale);

    $name = '';
    if (Auth::check()) {
        $name = !empty($user_infos['ci_last_name']) ? !empty($user_infos['ci_firstname']) ? ucwords(mb_strtolower($user_infos['ci_firstname'])) . ' ' . mb_strtoupper($user_infos['ci_last_name']) : mb_strtoupper($user_infos['ci_last_name']) : '';
    }
?>
@section('title_page')
    {!! mb_strtoupper(trans('navigation.dashboard')) !!}
    <span>{!! trans('navigation.boat_on_demand') !!}</span>
@endsection

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('breadcrumb')
    {!! $breadcrumb !!}
@endsection

{{--@section('javascript')
    @if($errors->any() || Session::has('errors') || Session::has('message') || Session::has('newsletter_message') || Session::has('dashboard_message'))
        <script>$(document).ready(function(){$("#msgModal").modal('show');});</script>
    @endif
@endsection--}}

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>
                    <strong class="uppercase">{!! trans('dashboard.private_individuals_title') . '</strong>' !!}
                    {!! '<span class="lead">' . trans('dashboard.private_individuals_subtitle') . '</span>' !!}
                </h2>
            </div>
        </div>

        @if(Session::has('message'))
            @include('theme.partials.modals.msg-modal', ['form_referrer'=>Session::get('message.referrer'),'title_modal'=>Session::get('message.title'),'message_modal'=>Session::get('message.text'), 'message_type'=>Session::get('message.type')])
        @elseif(!empty($message))
            @include('theme.partials.modals.msg-modal', ['form_referrer'=>$message['referrer'],'title_modal'=>$message['title'],'message_modal'=>$message['text'], 'message_type'=>$message['type']])
        @endif

        @if (!Auth::check())
        <div class="row">
            <div class="col-sm-6">
                @include('theme.partials.elements.dashboard-login-form', [
                    'target'=>'sales',
                    'email'=>$email,
                    'text_title'=>trans('dashboard.sale_ad_title'), 'text_intro'=>trans('dashboard.connect_sale_intro'),
                    'btn_link_text'=>trans('dashboard.connect_sale_link')])
            </div>
            <div class="col-sm-6">
                @include('theme.partials.elements.dashboard-login-form', [
                    'target'=>'bod',
                    'email'=>$email,
                    'text_title'=>trans('dashboard.bod_title'), 'text_intro'=>trans('dashboard.connect_bod_intro'),
                    'btn_link_text'=>trans('dashboard.connect_bod_link')])
            </div>
            <div class="col-sm-12">
                <p class="lead">
                    <strong>{!! trans('dashboard.professional_boating_customer') !!}</strong>
                    <br>
                    {!! trans('dashboard.connect_boatgest_link', ['boatgest_link'=>$boatgest_link]) !!}
                </p>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-sm-8">
                <div class="well well-white clearfix">
                    <p class="lead strong">
                        {!! trans('navigation.welcome') !!}&nbsp;<strong class="accent-color">{!! $name !!}</strong>
                    </p>
                    {{--{{ Auth::user()->type }}--}}
                    {{--{{ Auth::user()->role }}--}}
                    <hr>
                    <p class="lead">
                        {!! trans('boat_on_demand.looking_for') !!}
                        {!! link_to(trans_route($currentLocale,'routes.boat_on_demand'), trans('navigation.search') . ' ' . trans('navigation.on') . ' ' . trans('navigation.boat_on_demand'), ['title'=> trans('navigation.search') . ' ' . trans('navigation.on') . ' ' . trans('navigation.boat_on_demand'), 'class'=>'btn btn-danger']) !!}
                    </p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="well well-white text-right clearfix">
                    <p>
                        <a href="{{ url(trans_route($currentLocale, 'routes.logout')) }}" title="{!! trans('navigation.logout') !!}" class="btn btn-warning btn-block"><i class="fa fa-sign-out fa-fw"></i>{!! trans('navigation.logout') !!}</a>
                        <br>
                        <a href="{{ url(trans_route($currentLocale, 'routes.dashboard_edit_customer')) }}/{!! $user_infos['customer_id'] !!}" title="{!! trans('navigation.edit') . ' '  . trans('dashboard.your_account_details') !!}" class="btn btn-primary btn-block"><i class="fa fa-edit fa-fw"></i>{!! trans('navigation.edit') . ' '  . trans('dashboard.your_account_details') !!}</a>
                        <br>
                        <a href="{{ url(trans_route($currentLocale, 'routes.password_email')) }}/{!! $user_infos['ci_email'] !!}" title="{!! trans('navigation.auth.passwords.reset') !!}" class="btn btn-danger btn-block"><i class="fa fa-lock fa-fw"></i>{!! trans('navigation.auth.passwords.reset') !!}</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="well well-white clearfix">
                    <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! trans('navigation.dashboard') !!}" class="btn btn-primary btn-block"><i class="fa fa-dashboard fa-fw"></i>{!! trans('navigation.dashboard') !!}</a>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection