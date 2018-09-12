<?php
    $hasCountryContract = false;
    if(null !== Session::get('country_contracts.id')) {
        $hasCountryContract = true;
    }
    if (App::isLocal()) {
        define('RUN_TYPE', 'debug');
    } else {
        define('RUN_TYPE', '');
    }
    define('REGLEMENT_TITLE', 'Pay Offers');
    define('REGLEMENT_CONFIRM_TITLE', 'Payment confirmation');
    define('REGLEMENT_CONFIRM_TEXT', '<strong class="lead big">Thank you for registering.</strong> <br>You will recieve a confirmation email shortly on your <em class="strong">%s</em> mailbox.<br>For more information please <a href="/contact-us" title="Contact us" target="_blank">contact</a> the administration team.');

    //$plan = isset($_GET['plan']) && !empty($_GET['plan']) ? $_GET['plan'] : '';
    $PageViewName = 'pay';
    $tagPage = 'window.location.pathname + window.location.search + window.location.hash';
    $titlePage = 'Youboat ' . mb_strtoupper($country_code) . ' | ';
    $plan_description = '';

    $charge = 1;
    //$email = !empty($ci_email) ? $ci_email : '';
    $charge_description = config('youboat.'. $country_code .'.stripe.charge_description');
    $amount = config('youboat.'. $country_code .'.stripe.charge_amount');
    $pay_currency = config('youboat.'. $country_code .'.stripe.pay_currency');
    $pay_locale = config('youboat.'. $country_code .'.stripe.pay_locale');


    require $_SERVER['DOCUMENT_ROOT'] . '/pay/actions/pay_stripe.php';

    if($errors->any()) {
        $errorsMessages = $errors->getMessages();
        $lastInputHasError = array_keys($errorsMessages);
        $inputsHasError = implode(',', array_map(function($item) { return "#" . $item; }, $lastInputHasError));
        $lastInputHasError = end($lastInputHasError);

        $ready_to_pay = false;
        $formPosted = 'posted';
        $message_type = 'error';
        $message_modal = '<ul class="clearfix">';
        if(count($errors) > 1) {
            $cols = 'col-sm-6';
        } else {
            $cols = 'col-sm-12';
        }
        $message_modal .= preg_replace('/ id/', '', implode('', $errors->all('<li class="'. $cols .'">:message</li>')));
        $message_modal .= '</ul>';
        if($errors->first('email')) {
            $change_email_link = link_to('#change_email', trans('sell.change_the_email_address'), ['class'=>"btn btn-sm btn-block btn-danger", 'data-dismiss'=>"modal", 'aria-hidden'=>"true"]);
            $message_modal .= '<p>';
            $message_modal .= $change_email_link;
            $message_modal .= '</p>';
            $modal_javascript = "<script>" . "\n";
            $modal_javascript .= "\t\t" . "inputsHasError='#ci_email';" . "\n";
            $modal_javascript .= "\t\t" . "lastInputHasError='#ci_email';" . "\n";
            $modal_javascript .= "$(document).ready(function () {" . "\n";
            $modal_javascript .= "\t" . "var CiEmail = $('#form_sell #ci_email');" . "\n";
            $modal_javascript .= "\t" . "var CiEmailVal = CiEmail.val();" . "\n";
            $modal_javascript .= "\t" . "var CurrentStep = CiEmail.closest('.step');" . "\n";
            $modal_javascript .= "\t" . "var ADmsgModalError = $('#msgModalError');" . "\n";
            $modal_javascript .= "\t" . "$('a[href=\"#change_email\"]', ADmsgModalError).on('click', function() {" . "\n";
            $modal_javascript .= "\t\t" . "CiEmail.attr('data-placeholder', CiEmailVal).attr('placeholder', CiEmailVal).val('').parents('.input-group').closest('.form-group').removeClass('has-success').addClass('has-error');" . "\n";
            //$modal_javascript .= "\t\t" . "setStyleSteps(CurrentStep);" . "\n";
            //$modal_javascript .= "\t\t" . "$('#form_sell .step_to_check[href=\"#' + CurrentStep.attr('id') + '\"]').trigger('click');" . "\n";
            //$modal_javascript .= "\t\t" . "CiEmail.focus();" . "\n";
            $modal_javascript .= "\t\t" . "
            CiEmail.on('blur', function(){
                if($(this).val()) {
                    CiEmail.attr('placeholder','');
                } else {
                    CiEmail.attr('placeholder',CiEmail.attr('data-placeholder'));
                }
            });" . "\n";
            //$modal_javascript .= "\t\t" . "$('body,html').animate({scrollTop:CiEmail.offset().top}, 750, 'easeOutExpo');" . "\n";
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
            $message_action .= trans('sell.email_already_created', ['website_name'=>$website_name, 'login_link'=>$login_link]);
            $message_action .= '</div>';

            $message_action .= '<span class="or">' . trans('navigation.or') .'</span>';

            $message_action .= '<div class="well well-info text-center">';
            $message_action .= trans('sell.email_lost_password', ['forgotten_password_link'=>$forgotten_password_link]);
            $message_action .= '</div>';
            $message_type = 'error';
        }
    }

    if(isset($datasRequest) && !empty($datasRequest)) {
        $formPosted = 'posted';
    } else {
        $formPosted = '';
    }
    if(isset($ready_to_pay) && $ready_to_pay) {
        $formPosted = $ready_to_pay;
    }
    if(!isset($ready_to_pay)) {
        $ready_to_pay = old('ready_to_pay', false);
    }

    $collapse_in = !empty($formPosted) ? '' : 'in';
    $collapse_in = isset($ready_to_pay) && $ready_to_pay ? '' : $collapse_in;
    $collapse_in_pay = isset($ready_to_pay) && $ready_to_pay ? 'in' : '';

    $submitBtn = '';
    $submitNavBtn = '';

    //check
    //$submitBtn = Form::button(ucfirst(trans('navigation.check')) . '<i class="fa fa-arrow-right fa-fw"></i>', ['type' => 'submit', 'id' => 'btn_check','class' => 'big btn btn-lg btn-success btn-exception']);
    $submitBtn = '<div id="btn_check">' . Form::button(ucfirst(trans('navigation.check')) . '<i class="fa fa-arrow-right fa-fw"></i>', ['type' => 'submit', 'id' => 'btn_check','class' => 'big btn btn-lg btn-success btn-exception']) . '</div>';
    $submitNavBtn = Form::button(ucfirst(trans('navigation.check')) . '<i class="fa fa-arrow-right fa-fw"></i>', ['type' => 'button', 'id' => 'nav_btn_check','class' => 'btn btn-block btn-success btn-exception']);

    if(isset($ready_to_pay) && 'ready_to_pay' == $ready_to_pay) {
        $submitNavBtnPay = '';

        //submit
        $submitNavBtn .= Form::button(ucfirst(trans('navigation.submit')) . '<i class="fa fa-check-circle fa-fw"></i>', ['type' => 'button', 'id' => 'nav_btn_submit','class' => 'btn btn-block btn-success btn-exception']);

        //check
        //$submitBtn = Form::button(ucfirst(trans('navigation.check')) . '<i class="fa fa-arrow-right fa-fw"></i>', ['type' => 'submit', 'id' => 'btn_check', 'class' => 'hidden big btn btn-lg btn-success btn-exception']);
        $submitBtn = '<div id="btn_check" class="hidden">' . Form::button(ucfirst(trans('navigation.check')) . '<i class="fa fa-arrow-right fa-fw"></i>', ['type' => 'submit', 'id' => '', 'class' => ' big btn btn-lg btn-success btn-exception']) . '</div>';

        //payment
        //$submitBtn .= Form::button(ucfirst(trans('navigation.payment')) . '<i class="fa fa-credit-card fa-fw"></i>', ['type' => 'submit', 'id' => 'btn_submit', 'class' => 'big btn btn-lg btn-success btn-exception']);
        if($hasCountryContract) {
            $submitBtn .= '<div id="btn_submit" class="col-xs-12 col-sm-4">' . Form::button(ucfirst(trans('navigation.payment')) . '<i class="fa fa-credit-card fa-fw"></i>', ['type' => 'submit', 'id' => '', 'class' => 'big btn btn-lg btn-success btn-exception']) . '</div>';
        } else {
            $submitBtn .= '<div id="btn_submit" class="col-xs-12 col-sm-4">' . Form::button(ucfirst(trans('navigation.submit')) . '<i class="fa fa-check-circle fa-fw"></i>', ['type' => 'submit', 'id' => '', 'class' => 'big btn btn-lg btn-success btn-exception']) . '</div>';
        }
    }
    $submitBtnCC = '';
    if(isset($ready_to_pay) && 'success' == $ready_to_pay) {

        if($hasCountryContract) {
            //check
            //$submitBtn = Form::button(ucfirst(trans('navigation.check')) . '<i class="fa fa-arrow-right fa-fw"></i>', ['type' => 'submit', 'id' => 'btn_check', 'class' => 'hidden big btn btn-lg btn-success btn-exception']);
            $submitBtn = '<div id="btn_check" class="hidden">' . Form::button(ucfirst(trans('navigation.check')) . '<i class="fa fa-arrow-right fa-fw"></i>', ['type' => 'submit', 'id' => '', 'class' => ' big btn btn-lg btn-success btn-exception']) . '</div>';

            //submit
            //$submitBtn .= Form::button(ucfirst(trans('navigation.submit')) . '<i class="fa fa-check-circle fa-fw"></i>', ['type' => 'submit', 'id' => 'btn_submit', 'class' => 'big btn btn-lg btn-success btn-exception']);
            $submitBtn .= '<div id="btn_submit">' . Form::button(ucfirst(trans('navigation.submit')) . '<i class="fa fa-check-circle fa-fw"></i>', ['type' => 'submit', 'id' => '', 'class' => 'big btn btn-lg btn-success btn-exception']) . '</div>';

            //$submitBtnCC = Form::button(ucfirst(trans('navigation.submit')) . '<i class="fa fa-check-circle fa-fw"></i>', ['type' => 'submit', 'id' => 'btn_submit_cc', 'class' => 'big btn btn-lg btn-success btn-exception']);
            $submitBtnCC = '<div id="btn_submit_cc">' . Form::button(ucfirst(trans('navigation.submit')) . '<i class="fa fa-check-circle fa-fw"></i>', ['type' => 'submit', 'id' => '', 'class' => 'big btn btn-lg btn-success btn-exception']) . '</div>';

        } else {
            //check
            //$submitBtn = Form::button(ucfirst(trans('navigation.check')) . '<i class="fa fa-arrow-right fa-fw"></i>', ['type' => 'submit', 'id' => 'btn_check', 'class' => 'hidden big btn btn-lg btn-success btn-exception']);
            $submitBtn = '<div id="btn_check" class="hidden">' . Form::button(ucfirst(trans('navigation.check')) . '<i class="fa fa-arrow-right fa-fw"></i>', ['type' => 'submit', 'id' => '', 'class' => ' big btn btn-lg btn-success btn-exception']) . '</div>';

            //payment
            //$submitBtn .= Form::button(ucfirst(trans('navigation.payment')) . '<i class="fa fa-credit-card fa-fw"></i>', ['type' => 'submit', 'id' => 'btn_submit', 'class' => 'big btn btn-lg btn-success btn-exception']);
            $submitBtn .= '<div id="btn_submit">' . Form::button(ucfirst(trans('navigation.payment')) . '<i class="fa fa-credit-card fa-fw"></i>', ['type' => 'submit', 'id' => '', 'class' => 'big btn btn-lg btn-success btn-exception']) . '</div>';
        }

        //submit
        $submitNavBtn = Form::button(ucfirst(trans('navigation.submit')) . '<i class="fa fa-check-circle fa-fw"></i>', ['type' => 'button', 'id' => 'nav_btn_submit','class' => 'btn btn-block btn-success btn-exception']);

        //payment
        $submitNavBtnPay = Form::button(ucfirst(trans('navigation.payment')) . '<i class="info fa fa-2x fa-credit-card fa-fw"></i>', ['type' => 'button', 'id' => 'nav_btn_pay','class' => 'btn btn-block btn-info btn-exception']);
        $submitNavBtn .= $submitNavBtnPay;
    }
?>

@extends('layouts.theme')
<?php
$metas_title = trans('metas.sell');
$metas_description = trans('landing.landing_text_10');
$metas = [
        'metas_title' => $metas_title . ' - ' . $website_name,
        'metas_description' => $metas_description
];
?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('content')
    <div class="row ">
        <div class="col-sm-7 text-primary intro hidden-xs">
            <i class="fa fa-4x fa-tags pull-left"></i>
            <p class="text-justify lead accent-color">
                {!! trans('landing.landing_text_09') !!}
            </p>
            <p class="alert alert-info  strong">
                {!! trans('landing.landing_text_10') !!}
            </p>
        </div>
        <div class="col-sm-5">
            <div class="well well-white">
            <h2 class=" lead accent-color-danger">{!! trans('sell.sell_offer_text1', ['price' => '<strong>' . config('youboat.'. $country_code .'.currency') . $amount/100 . '</strong>']) !!}</h2>
            <h3 class="text-success big">{!! trans('sell.sell_offer_text2', ['price' => '<strong>' . config('youboat.'. $country_code .'.currency') . $amount/100 . '</strong>']) !!}</h3>
            <p>{!! trans('sell.sell_offer_text3') !!}</p>
            <em>{!! trans('sell.sell_offer_text4') !!}</em>
            </div>
        </div>
    </div>
    @if(!$agent->isMobile() || $agent->isTablet())
    <div class="row">
        <div class="col-sm-12 steps">
            <ul class='nav nav-wizard'>
                <li role="tab" class='col-xs-2 col-sm-3 active'><a class="step_to_check" role="button" data-toggle="collapse" data-parent="#accordion" href="#step_01" aria-expanded="true" aria-controls="step_01"><strong class="number">1</strong> {!! trans('sell.describe_your_ad') !!}</a>
                </li>
                <li role="tab" class="col-xs-2 col-sm-3"><a class="collapsed step_to_check" role="button" data-toggle="collapse" data-parent="#accordion" href="#step_02" aria-expanded="false" aria-controls="step_02"><strong class="number">2</strong> {!! trans('sell.enter_your_details') !!}</a>
                </li>
                <li role="tab" class="col-xs-2 col-sm-3"><a class="collapsed step_to_valid" role="button" data-toggle="collapse" data-parent="#accordion" href="#step_03" aria-expanded="false" aria-controls="step_03">
                        <strong class="number">3</strong> {!! trans('sell.summary_and_payment') !!}
                        <i class="info fa fa-2x fa-fw fa-newspaper-o" aria-hidden="true"></i>
                </a></li>
                <li role="tab" class="check-btn col-sm-3">{!! $submitNavBtn !!}</li>
            </ul>
        </div>
    </div>
    @endif
    <div class="spacer-10"></div>
    @if(Session::has('message'))
        @include('theme.partials.modals.msg-modal', ['form_referrer'=> (isset($form_action) && 'edit' === $form_action) ? 'form_ads_edit' : 'form_ads', 'title_modal'=>Session::get('message.title'),'message_modal'=>Session::get('message.text'), 'message_type'=>Session::get('message.type')])
    @elseif($errors->any())
        @include('theme.partials.modals.msg-modal', ['form_referrer'=> (isset($form_action) && 'edit' === $form_action) ? 'form_sell_edit' : 'form_sell', 'title_modal'=>trans('navigation.sell'),'message_modal'=>$message_modal, 'message_action'=>$message_action, 'message_type'=>$message_type])
    @endif

    @include('theme.partials.modals.msg-modal', ['form_referrer'=> 'form_sell', 'title_modal'=>trans('navigation.sell'),'message_modal'=>'errors', 'message_action'=>'', 'message_type'=>'success'])
    @if (isset($form_action) && 'edit' === $form_action)
    {!! Form::model($adscaracts, array('files' => true, 'url' => trans_route($currentLocale, 'routes.dashboard_edit_ads'), 'class' => ('form-horizontal adscaracts ' . (!empty($formPosted) ? $formPosted : '')), 'role'=>'form', 'id' => 'form_ads_edit', 'autocomplete'=>'off', 'method' => 'PATCH')) !!}
    {!! Form::hidden('id', $adscaracts->id) !!}
    @else
    {!! Form::open(array('files' => true, 'url'=>trans_route($currentLocale, 'routes.sell'),
    'class'=> ('form-horizontal adscaracts ' . (!empty($formPosted) ? $formPosted : '')), 'role'=>'form', 'id'=>'form_sell', 'novalidate' => 'novalidate', 'autocomplete'=>'off')) !!}
    {{--{!! Form::open(array('files' => true, 'url'=>'sell', --}}
    {{--'class'=> ('form-horizontal adscaracts ' . (!empty($formPosted) ? $formPosted : '')), 'role'=>'form', 'id'=>'form_sell', 'novalidate' => 'novalidate', 'autocomplete'=>'off')) !!}--}}
    @endif
    {!! csrf_field() !!}
    {!! Form::hidden('ad_referrer', isset($ad_referrer) ? $ad_referrer : (!empty($datasRequest['ad_referrer']) ? $datasRequest['ad_referrer'] : 'YB')) !!}
    {!! Form::hidden('ad_country_code', isset($ad_country_code) ? $ad_country_code : (!empty($datasRequest['ad_country_code']) ? $datasRequest['ad_country_code'] : 'uk')) !!}
    {!! Form::hidden('user_id', !empty($user_id) ? $user_id : old('user_id', null)) !!}
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
            <div id="step_01" class="step panel-collapse collapse {!! $collapse_in !!}" role="tabpanel" aria-labelledby="heading_step_01">
                <div class="panel-body">
                    @include('theme.partials.elements.sell.step-01', ['form_action'=>'sell'])
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading_step_02">
                <h4 class="panel-title">
                    <a class="step_to_check" href="#step_02" class="collapsed" data-toggle="collapse" role="button" data-parent="#accordion" aria-expanded="false" aria-controls="step_02">
                        <strong class="number">2</strong> {!! trans('sell.enter_your_details') !!}
                    </a>
                </h4>
            </div>
            <div id="step_02" class="step panel-collapse collapse" role="tabpanel" aria-labelledby="heading_step_02">
                <div class="panel-body">
                    @include('theme.partials.elements.sell.step-02', ['form_action'=>'sell'])
                </div>
            </div>
        </div>
        <?php
            $input_name = 'ready_to_pay';
            $input_value = !empty($ready_to_pay) ? $ready_to_pay : old($input_name);
            $attributes = [
                    //'required' => 'required',
                    'id' => $input_name
            ];
        ?>
        {!! Form::hidden('ready_to_pay', $input_value, $attributes) !!}
        @if(isset($ready_to_pay) && ($ready_to_pay == 'ready_to_pay' || $ready_to_pay == 'success'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading_step_03">
                <h4 class="panel-title">
                    <a class="step_to_valid" href="#step_03" class="collapsed" data-toggle="collapse" role="button" data-parent="#accordion" aria-expanded="false" aria-controls="step_03">
                        <strong class="number">3</strong> {!! trans('sell.summary_and_payment') !!}
                        <i class="info fa fa-2x fa-fw fa-newspaper-o" aria-hidden="true"></i>
                    </a>
                </h4>
            </div>
            <div id="step_03" class=" panel-collapse collapse {!! $collapse_in_pay !!}" role="tabpanel" aria-labelledby="heading_step_03">
                <div class="panel-body">
                    @include('theme.partials.elements.sell.step-03', ['submitBtn'=>$submitBtn])
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="form-group step-action {!! ($hasCountryContract && isset($ready_to_pay) && 'success' == $ready_to_pay)  ? 'step-submit' : '' !!}">
        {{--<div class="text-center">--}}
            @if(($hasCountryContract && isset($ready_to_pay) && 'success' == $ready_to_pay))
            {!! $submitBtnCC !!}
            @else
            {!! $submitBtn !!}
            @endif
        {{--</div>--}}
    </div>

    {!! Form::close() !!}
@endsection

@section('javascript')
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
    <script src="{!! asset(config('assets.js.youboat_filters_recovery.common.default.url')) !!}" defer></script>
    @if(isset($map_javascript) && !empty($map_javascript))
        {!! $map_javascript !!}
    @endif
    @if(isset($pay_javascript) && !empty($pay_javascript))
        {!! $pay_javascript !!}
    @endif
@endsection
