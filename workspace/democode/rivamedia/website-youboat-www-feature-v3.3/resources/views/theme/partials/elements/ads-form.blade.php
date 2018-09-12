<?php
    Cache::flush();
?>
@extends('layouts.theme')

<?php
    if (Auth::check()) {
        $name = !empty($privatescaracts['name']) ? !empty($privatescaracts['firstname']) ? ucwords(mb_strtolower($privatescaracts['firstname'])) . ' ' . mb_strtoupper($privatescaracts['name']) : mb_strtoupper($privatescaracts['name']) : '';
    }
    $ads_reference = '';
    if ( isset($datasRequest['reference']) && !empty($datasRequest['reference'])) {
        $ads_reference =  $datasRequest['reference'];
    }
?>

<?php

    if (isset($form_action) && 'edit' === $form_action) {
        $metas_title = 'Edit your boats ads | Update by indicating the boats ad criteria';
        $title = trans('ads_caracts.title_edit');
    } else {
        $metas_title = 'Submit your boats ads | Submit your boats ads criteria';
        $title = trans('ads_caracts.title_submit');
    }
    $metas_description = 'Submitting a boats ad? Submit your boats ads  by indicating your boat criteria';
    $metas = [
            'metas_title' => $metas_title . ' - ' . $website_name,
            'metas_description' => $metas_description
    ];

    $datasRequest =  $adscaracts->toArray();

    if (!empty($datasRequest) && count($datasRequest)>0) {
        foreach($datasRequest as $key => $value) {
            $$key = isset($value) ? $value : null;
        }
    }

?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-9">
            <div class="icon-box ibox-outline">
                <div class="ibox-icon">
                    <i class="fa fa-tag fa-flip-horizontal"></i>
                </div>
                <h2 class="uppercase strong accent-color text-center inbox-title">{!! $title !!}</h2>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-3 text-center">
            <span class="lead accent-color-danger">{!! !empty($name) ? $name : trans('boat_on_demand.already_registered') !!}</span><br>
            <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! trans('dashboard.private_individuals_subtitle') !!}" class="btn btn-sm btn-primary"><i class="fa fa-dashboard fa-fw"></i>{!! trans('dashboard.private_individuals_subtitle') !!}</a>
        </div>
        @if (isset($form_action) && 'edit' === $form_action)
        <div class="col-xs-6 col-sm-6 col-md-3 text-right">
            <br>{!! htmlspecialchars_decode(link_to(url()->previous(), '<i class="fa fa-mail-reply fa-fw"></i>Back', ['class' => 'btn btn-sm btn-default'])) !!}
        </div>
        @endif
    </div>

    <hr>
    @if(Session::has('message'))
        @include('theme.partials.modals.msg-modal', ['form_referrer'=> (isset($form_action) && 'edit' === $form_action) ? 'form_ads_edit' : 'form_ads', 'title_modal'=>Session::get('message.title'),'message_modal'=>Session::get('message.text'), 'message_type'=>Session::get('message.type')])
    @elseif($errors->any())
        <?php
            $message_type = 'error';
            $message_modal = '<ul class="clearfix">';
            $message_modal .= implode('', $errors->all('<li>:message</li>'));
            $message_modal .= '</ul>';
            if($errors->first('email')) {
                $change_email_link = link_to('#change_email', trans('boat_on_demand.change_the_email_address'), ['class'=>"btn btn-sm btn-block btn-danger", 'data-dismiss'=>"modal", 'aria-hidden'=>"true"]);
                $message_modal .= '<p>';
                $message_modal .= $change_email_link;
                $message_modal .= '</p>';
                $modal_javascript = "<script>" . "\n";
                $modal_javascript .= "$(document).ready(function () {" . "\n";
                $modal_javascript .= "\t" . "var ADSmsgModalError = $('.boat_on_demand #msgModalError');" . "\n";
                $modal_javascript .= "\t" . "$('a[href=\"#change_email\"]', ADSmsgModalError).on('click', function() {" . "\n";
                $modal_javascript .= "\t\t" . "$('body,html').animate({scrollTop:$('.boat_on_demand #ci_email').offset().top}, 750, 'easeOutExpo');" . "\n";
                $modal_javascript .= "\t" . "});" . "\n";
                $modal_javascript .= "});" . "\n";
                $modal_javascript .= "</script>" . "\n";
            }
            $message_action = '';
            if($errors->first('email')) {
                $login_link = '<p>';
                $login_link .= link_trans_url(trans_route($currentLocale, 'routes.login'), 'navigation.login', [], ['class' => 'btn btn-block btn-success blank']);
                $login_link .= '</p>';

                $email = !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : old('ci_email');
                $forgotten_password_link = '<p>';
                $forgotten_password_link = link_trans_url(trans_route($currentLocale, 'routes.password_email'), 'passwords.textlink_reset_password', ['email' => $email], ['class' => 'btn btn-sm btn-block btn-info blank']);
                $forgotten_password_link .= '</p>';

                $message_action .= '<span class="or">' . trans('navigation.or') .'</span>';

                $message_action .= '<div class="well well-white text-info clearfix">';
                $message_action .= trans('boat_on_demand.email_already_created', ['website_name'=>$website_name, 'login_link'=>$login_link]);
                $message_action .= '</div>';

                $message_action .= '<span class="or">' . trans('navigation.or') .'</span>';

                $message_action .= '<div class="well well-info text-center">';
                $message_action .= trans('boat_on_demand.email_lost_password', ['forgotten_password_link'=>$forgotten_password_link]);
                $message_action .= '</div>';
                $message_type = 'error';
            }

        ?>
        @include('theme.partials.modals.msg-modal', ['form_referrer'=> (isset($form_action) && 'edit' === $form_action) ? 'form_ads_edit' : 'form_ads', 'title_modal'=>trans('navigation.boat_on_demand'),'message_modal'=>$message_modal, 'message_action'=>$message_action, 'message_type'=>$message_type])
    @endif

    @if (isset($form_action) && 'edit' === $form_action)
    {!! Form::model($adscaracts, array('novalidate'=>'novalidate', 'files' => true, 'url' => trans_route($currentLocale, 'routes.dashboard_edit_ads'), 'class' => 'form-horizontal', 'role'=>'form', 'id' => 'form_ads_edit', 'autocomplete'=>'off', 'method' => 'PATCH')) !!}
        {!! Form::hidden('id', $adscaracts->id) !!}
    @else
    {!! Form::open(array('novalidate'=>'novalidate', 'files' => true, 'url'=>trans_route($currentLocale, 'routes.sell'), 'class'=>'form-horizontal', 'role'=>'form', 'id'=>'form_ads', 'autocomplete'=>'off')) !!}
    @endif
        {!! csrf_field() !!}
        {!! Form::hidden('country_code', $country_code) !!}
        {!! Form::hidden('reference', $ads_reference) !!}
        {!! Form::hidden('currency', config('youboat.'. $country_code .'.currency')) !!}

        {!! Form::hidden('ad_country_code', isset($ad_country_code) ? $ad_country_code : (!empty($datasRequest['ad_country_code']) ? $datasRequest['ad_country_code'] : 'uk')) !!}
        {!! Form::hidden('currency', config('youboat.'. $country_code .'.currency')) !!}

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading_step_01">
                    <h4 class="panel-title">
                        <a class="step_to_check" href="#step_01" role="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="true" aria-controls="step_01">
                            <strong class="number">1</strong> {!! trans('sell.describe_your_ad') !!}
                        </a>
                    </h4>
                </div>
                <div id="step_01" class="step panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_step_01">
                    <div class="panel-body">
                        @include('theme.partials.elements.sell.step-01', ['form_action'=>'edit'])
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading_step_02">
                    <h4 class="panel-title">
                        <a class="step_to_check" href="#step_02" class="collapsed" data-toggle="collapse" role="button" data-parent="#accordion" aria-expanded="false" aria-controls="step_02">
                            <strong class="number">2</strong> {!! trans('sell.your_details') !!}
                        </a>
                    </h4>
                </div>
                <div id="step_02" class="step panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_step_02">
                    <div class="panel-body">
                        @include('theme.partials.elements.sell.step-02', ['form_action'=>'edit'])
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group text-center">
                    {!! Form::button('<i class="fa fa-btn fa-2x fa-mouse-pointer fa-fw"></i>' . trans('navigation.submit'), ['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-exception']) !!}
                </div>
            </div>
        </div>
    {!! Form::close() !!}
    @if($ad_banners)
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
    @endif

@endsection


@section('javascript')
    @if(isset($modal_javascript) && !empty($modal_javascript))
        {!! $modal_javascript !!}
    @endif

    @if (App::isLocal())
        <script src="{!! asset('assets/vendor/ckeditor/4.6.2/standard/ckeditor.js') !!}"></script>
        <script src="{!! asset('assets/vendor/jquery-ui/1.12.1/jquery-ui.min.js') !!}"></script>
    @else
        <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
        <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    @endif
    <script>
        var placeholder_txt = '{!! trans('navigation.form_enter_placeholder') !!}';
        var delete_txt = '{!! trans('navigation.delete') !!}';
        var reload_txt = '{!! trans('navigation.reload') !!}';
        var mandatory_txt = '{!! trans('sell.fill_mandatory_fields') !!}';
                @if(isset($inputsHasError) && !empty($inputsHasError))
        var inputsHasError = '{!! $inputsHasError !!}';
                @endif
                @if(isset($lastInputHasError) && !empty($lastInputHasError))
        var lastInputHasError = '#{!! $lastInputHasError !!}';
        @endif
    </script>
    @if(isset($modal_javascript) && !empty($modal_javascript))
        {!! $modal_javascript !!}
    @endif

    <script src="{!! asset('assets/vendor/youboat/js/caracts.js') !!}"></script>
    <script src="{!! asset(config('assets.js.youboat_filters.common.default.url')) !!}" defer></script>
@endsection


