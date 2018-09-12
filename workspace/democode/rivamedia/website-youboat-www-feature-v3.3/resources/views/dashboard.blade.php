<?php
//unCacheView($view_name);
//Cache::flush();
//Artisan::call('cache:clear');
        function formatListingTable($listing = [], $transKey = 'boat_on_demand', $prefix = '', $currentLocale) {

            $tK = !(Lang::has($transKey .'.adstype')) ? 'filters' : $transKey;
            $trans_adstype = trans($tK .'.adstype');

            $tK = !(Lang::has($transKey .'.manufacturers_shipyards')) ? 'filters' : $transKey;
            $trans_manufacturers_shipyards = trans($tK .'.manufacturers_shipyards');

            $tK = !(Lang::has($transKey .'.model')) ? 'filters' : $transKey;
            $trans_model = trans($tK .'.model');

            $tK = !(Lang::has($transKey .'.budget')) ? 'filters' : $transKey;
            $trans_budget = trans($tK .'.budget');

            $Table = '';
            if(isset($listing) && !empty($listing)) {
                $Table = '<div class="table-responsive">';
                $Table .= '<table class="table table-condensed table-bordered table-striped table-hover">';
                $Table .= '<thead>';
                $Table .= '<tr class="active">';
                $Table .= '<th class="text-center strong accent-color">' . $trans_adstype . '</th>';
                $Table .= '<th class="text-center strong accent-color">' . $trans_manufacturers_shipyards . '</th>';
                $Table .= '<th class="text-center strong accent-color">' . $trans_model . '</th>';
                $Table .= '<th class="text-center strong accent-color">' . $trans_budget . '</th>';
                $Table .= '<th class="text-center strong accent-color">' . trans('dashboard.status') . '</th>';
                $Table .= '<th class="text-center strong accent-color">' . trans('dashboard.deposit_date') . '</th>';
                $Table .= '<th class="text-center strong accent-color-danger">' . trans('dashboard.actions') . '</th>';
                $Table .= '</tr>';
                $Table .= '</thead>';
                $Table .= '<tbody>';
                foreach($listing as $entry) {
                    $Table .= '<tr>';
                    foreach($entry as $key => $value) {

                        $status_css ='';
                        switch($entry['status']) {
                            default :
                                $status_css ='';
                                break;
                            case 'active' :
                            case 'valid' :
                                $status_css ='success text-success';
                                break;
                            case 'inactive' :
                                $status_css ='warning text-primary';
                                break;
                            case 'not_valid' :
                                $status_css ='danger text-danger';
                                break;
                            case 'in_moderation' :
                                $status_css ='info text-info';
                                break;
                            case 'unpublished' :
                                $status_css ='warning text-warning';
                                break;
                        }
                        if('id' != $key && 'ad_country_code' != $key && 'countries_id' != $key && 'ad_title' != $key)  {
                            switch($key) {
                                case 'adstypes_id':
                                    $value = !empty($value) ? Search::getAdstypeById($value)['name'] : '/';
                                    break;
                                case 'manufacturers_id':
                                    $value = !empty($value) ? Search::getManufacturerById($value)['name'] : '/';
                                    break;
                                case 'models_id':
                                    $value = !empty($value) ? Search::getModelById($value)['name'] : '/';
                                    break;
                                case 'ad_price':
                                    //$value = !empty($value) ? trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $value))) : '/';
                                    //$value = !empty($value) ? formatPrice($value) : '/';
                                    $value = !empty($value) ? (is_numeric($value) && !empty($entry['ad_country_code']) ? formatPriceCurrency($value, $entry['ad_country_code']) : trim(preg_replace('!\s+!', ' ', $value)) ) : $value;
                                    $status_css .=' text-right';
                                    break;
                                case 'budget':
                                    //$value = !empty($value) ? trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $value))) : '/';
                                    //$value = !empty($value) ? formatPrice($value) : '/';
                                    $value = !empty($value) ? (is_numeric($value) && !empty($entry['countries_id']) ? formatPriceCurrency($value, $entry['countries_id']) : trim(preg_replace('!\s+!', ' ', $value)) ) : $value;
                                    $status_css .=' text-right';
                                    break;
                                case 'status':
                                    $value = !empty($value) ? trans('dashboard.'. $value) : '/';
                                    $status_css .=' text-center';
                                    break;
                                case 'updated_at':
                                    $status_css .=' text-center';
                                    break;
                            }
                            $Table .= '<td class="' . $status_css . '">';
                            $Table .= $value;
                            $Table .= '</td>';
                        }
                    }
                    $Table .= '<td class="text-center">';

                    $edit_link = $unpublish_link = $reactivate_link = '';
                    $route = 'routes.dashboard_edit_' . $prefix;
                    $title = trans('navigation.edit');
                    $class = 'btn btn-sm btn-block btn-primary btn-exception';
                    $edit_link = Form::open(array('url'=>trans_route($currentLocale, $route), 'id'=>'form_dashboard_edit_' . $prefix, 'method'=>'POST'));
                    $edit_link .= csrf_field();
                    $edit_link .= Form::hidden('id', $entry['id']);
                    $edit_link .= Form::button($title, ['type' => 'submit', 'class' => $class, 'title' => $title]);
                    $edit_link .= Form::close();

                    if('valid' == $entry['status'] || 'active' == $entry['status'] || 'inactive' == $entry['status'] || 'not_valid' == $entry['status'] || 'in_moderation' == $entry['status']) {
                        $route = 'routes.dashboard_unpublish_' . $prefix;
                        $title = trans('navigation.unpublish');
                        $class = 'btn btn-sm btn-block btn-danger btn-exception';
                        $unpublish_link = '<br>';
                        $unpublish_link .= Form::open(array('url'=>trans_route($currentLocale, $route), 'id'=>'form_dashboard_unpublish_' . $prefix, 'method'=>'PATCH'));
                        $unpublish_link .= csrf_field();
                        $unpublish_link .= Form::hidden('id', $entry['id']);
                        $unpublish_link .= Form::button($title, ['type' => 'submit', 'class' => $class, 'title' => $title]);
                        $unpublish_link .= Form::close();
                    }

                    if('not_valid' == $entry['status'] || 'inactive' == $entry['status'] || 'unpublished' == $entry['status']
                        /*|| 'in_moderation' == $entry['status']*/
                    ) {
                        $route = 'routes.dashboard_reactivate_' . $prefix;
                        $title = trans('navigation.reactivate');
                        $class = 'btn btn-sm btn-block btn-sm btn-success btn-exception';
                        $reactivate_link = '<br>';
                        //$reactivate_link .= link_to(trans_route($currentLocale,$route) . '/' . $entry['id'], $title, ['title' => $title, 'class' => $class]);
                        $reactivate_link .= Form::open(array('url'=>trans_route($currentLocale, $route), 'id'=>'form_dashboard_reactivate_' . $prefix, 'method'=>'PATCH'));
                        $reactivate_link .= csrf_field();
                        $reactivate_link .= Form::hidden('id', $entry['id']);
                        $reactivate_link .= Form::button($title, ['type' => 'submit', 'class' => $class, 'title' => $title]);
                        $reactivate_link .= Form::close();
                    }

                    $Table .= $edit_link;
                    $Table .= $unpublish_link;
                    $Table .= $reactivate_link;

                    $Table .= '</td>';
                }
                $Table .= '</tbody>';
                $Table .= '</table>';
                $Table .= '</div>';
            }
            return $Table;
        }
?>
@extends('layouts.theme')
<?php
    $email              = isset($email) ? $email : (Session::has('email') ? Session::get('email') : '');

    //$metas_title        = trans('navigation.dashboard') . ' | ' . trans('navigation.boat_on_demand');
    $metas_title        = trans('navigation.dashboard');

    //$metas_description  = 'Dashboard for Boat On Demand';
    $metas_description  = 'Dashboard';
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
    $customer_email = '';
    $listingBOD = '';
    $listingSELL = '';

    if (Auth::check()) {

        if(!empty($customerscaracts)) {
            $name = !empty($customerscaracts['name']) ? !empty($customerscaracts['firstname']) ? ucwords(mb_strtolower($customerscaracts['firstname'])) . ' ' . mb_strtoupper($customerscaracts['name']) : mb_strtoupper($customerscaracts['name']) : '';
            $customer_email = !empty($customerscaracts['email']) ? $customerscaracts['email'] : '';
        }

        /*if(!empty($enquiries)) {
            $listingEnquiries = '';
            foreach($enquiries as $enquiry) {
                var_dump($enquiry);
            }
        }*/

        if(!empty($bod_listing)) {
            $listingBOD = formatListingTable($bod_listing, 'boat_on_demand', 'bod', $currentLocale);
        }

        if(!empty($ads_listing)) {
            $listingSELL = formatListingTable($ads_listing, 'ads_caracts', 'ads', $currentLocale);
        }
    }

    if(Auth::user()->type == 'customer') {
        $dashboard_title = trans('dashboard.customer_title');
    } else {
        $dashboard_title = trans('dashboard.private_individuals_title');
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


@section('content')
    <div class="row">
        <div class="col-sm-12">

            <h2 class="uppercase strong accent-color"><strong class="col-xs-12 col-sm-12 col-md-5">{!! $dashboard_title . '</strong> <span class="col-sm-12 col-md-7 lead">' . trans('dashboard.private_individuals_subtitle') . '</span>' !!}</h2>

            @if(Session::has('dashboard_message'))
                @include('theme.partials.modals.msg-modal',
                ['form_referrer'=>Session::get('dashboard_message.referrer'),
                'title_modal'=>Session::get('dashboard_message.title'),
                'message_modal'=>Session::get('dashboard_message.text'),
                'message_type'=>Session::get('dashboard_message.type')])
            @elseif(!empty($dashboard_message))
                @include('theme.partials.modals.msg-modal', ['form_referrer'=>$dashboard_message['referrer'],'title_modal'=>$dashboard_message['title'],'message_modal'=>$dashboard_message['text'], 'message_type'=>$dashboard_message['type']])
            @endif

            @if (!Auth::guest())
            <div class="row">
                <div class="col-sm-7 col-md-8">
                    <div class="well well-white clearfix">
                        <p class="lead">
                            <strong>{!! trans('navigation.welcome') !!}&nbsp;<span class="accent-color">{!! $name !!}</span></strong>
                            <em class="small">({!! Auth::user()->type !!})</em>
                        </p>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12 col-md-offset-3 col-md-6">
                                {!! link_to(trans_route($currentLocale,'routes.boat_on_demand'), trans('navigation.search') . ' ' . trans('navigation.on') . ' ' . trans('navigation.boat_on_demand') . '<i class="fa fa-search-plus fa-fw"></i>', ['title'=> trans('navigation.search') . ' ' . trans('navigation.on') . ' ' . trans('navigation.boat_on_demand'), 'class'=>'btn btn-danger btn-block']) !!}
                            @if(Auth::user()->type != 'admin' && Auth::user()->type != 'commercial' && Auth::user()->type != 'dealer')
                                {!! link_to(trans_route($currentLocale,'routes.sell'), trans('navigation.sell') . '<i class="fa fa-inverse fa-tag fa-fw"></i>', ['title'=> trans('navigation.sell'), 'class'=>'btn btn-success btn-block']) !!}
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 col-md-4">
                    <div class="well well-white text-right clearfix">
                        <a href="{{ url(trans_route($currentLocale, 'routes.logout')) }}" title="{!! trans('navigation.logout') !!}" class="btn btn-warning btn-block"><i class="fa fa-sign-out fa-fw"></i>{!! trans('navigation.logout') !!}</a>
                        <br>
                        <a href="{{ url(trans_route($currentLocale, 'routes.dashboard_edit_account')) }}" title="{!! trans('navigation.edit') . ' '  . trans('dashboard.your_account_details') !!}" class="btn btn-primary btn-block"><i class="fa fa-edit fa-fw"></i>{!! trans('navigation.edit') . ' '  . trans('dashboard.your_account_details') !!}</a>
                        {{--<br>--}}
                        {{--{!! htmlspecialchars_decode(link_to(LaravelLocalization::transRoute('routes.dashboard') . '/'. LaravelLocalization::transRoute('routes.logout_and_reset_password') . '/' . $customer_email, '<i class="fa fa-lock fa-fw"></i>' . trans('navigation.auth.passwords.change') , ['class' => 'btn btn-danger btn-block'])) !!}--}}
                        <br>
                        <a href="{{ url(trans_route($currentLocale, 'routes.dashboard_change_password')) }}/{!! $customer_email !!}" title="{!! trans('navigation.auth.passwords.change') !!}" class="btn btn-danger btn-block"><i class="fa fa-lock fa-fw"></i>{!! trans('navigation.auth.passwords.change') !!}</a>
                    </div>
                </div>
            </div>
            @if(!empty($listingSELL))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="well well-white clearfix">
                            <h3 class="accent-color-danger">{!! trans('dashboard.listing') !!} / {!! trans('navigation.your_boats_ads') !!}</h3>
                            {!! $listingSELL !!}
                        </div>
                    </div>
                </div>
            @endif
            @if(!empty($listingBOD))
            <div class="row">
                <div class="col-sm-12">
                    <div class="well well-white clearfix">
                        <h3 class="accent-color-danger">{!! trans('dashboard.listing') !!} / {!! trans('navigation.boat_on_demand') !!}</h3>
                        {!! $listingBOD !!}
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
@endsection