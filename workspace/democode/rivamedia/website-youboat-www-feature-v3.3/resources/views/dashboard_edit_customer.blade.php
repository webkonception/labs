@extends('layouts.theme')

<?php
    if (Auth::check()) {
        $agree_emails = !empty($customerscaracts['agree_emails']) ? $customerscaracts['agree_emails'] : (!empty($datasRequest['agree_emails']) ? $datasRequest['agree_emails'] : null);
        $origin = !empty($customerscaracts['origin']) ? $customerscaracts['origin'] : (!empty($datasRequest['origin']) ? $datasRequest['origin'] : null);
    }
    /*$bod_reference = '';
    if ( isset($datasRequest['reference']) && !empty($datasRequest['reference'])) {
        $bod_reference =  $datasRequest['reference'];
    }*/
?>

<?php
    $metas_title = 'Dasboard - Edit your account';

    $metas_description = 'Dasboard - Edit your account';
    $metas = [
        'metas_title' => $metas_title . ' - ' . $website_name,
        'metas_description' => $metas_description
    ];
?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('javascript')
    {{--@if($errors->any() || Session::has('errors') || Session::has('message') || Session::has('newsletter_message') || Session::has('customer_message'))
        <script>$(document).ready(function(){$("#msgModal").modal('show');});</script>
    @endif--}}
    <script src="{!! asset(config('assets.js.youboat_filters.common.default.url')) !!}" defer></script>
    <script src="{!! asset(config('assets.js.youboat_filters_recovery.common.default.url')) !!}" defer></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-9">
            <div class="icon-box ibox-outline">
                <div class="ibox-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <h2 class="uppercase strong accent-color text-left inbox-title">{!! trans('navigation.edit') . ' '  . trans('dashboard.your_account_details') !!}</h2>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 text-center">
                <a href="{{ url(trans_route($currentLocale, 'routes.logout')) }}" title="{!! trans('navigation.logout') !!}" class="btn btn-sm btn-warning btn-block"><i class="fa fa-sign-out fa-fw"></i>{!! trans('navigation.logout') !!}</a>
                <br>
                <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! trans('dashboard.private_individuals_subtitle') !!}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-dashboard fa-fw"></i>{!! trans('dashboard.private_individuals_subtitle') !!}</a>
        </div>
    </div>

    <hr>
    @if(Session::has('message'))
        @include('theme.partials.modals.msg-modal', ['form_referrer'=>Session::get('message.referrer'),'title_modal'=>Session::get('message.title'),'message_modal'=>Session::get('message.text'), 'message_type'=>Session::get('message.type')])
    @endif

    {{--{!! Form::model($customerscaracts, array('url' => trans_route($currentLocale, 'routes.dashboard_edit_customer') . '/' . $customerscaracts->id, 'class' => 'form-horizontal', 'role'=>'form', 'id' => 'form_customer_edit', 'autocomplete'=>'off', 'method' => 'PATCH')) !!}--}}
    {!! Form::model($customerscaracts, array('url' => trans_route($currentLocale, 'routes.dashboard_edit_customer'), 'class' => 'form-horizontal', 'role'=>'form', 'id' => 'form_customer_edit', 'autocomplete'=>'off', 'method' => 'PATCH')) !!}
    {!! Form::hidden('user_id', Auth::user()->id) !!}
    {!! Form::hidden('origin',$origin) !!}
    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
                $firstname = old('firstname', isset($customerscaracts->firstname) ? ucwords(mb_strtolower($customerscaracts->firstname)) : '');
                $label_txt = ucfirst(trans('validation.attributes.first_name'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'firstname'
                ];
                $css_state = '';
                if (!empty($firstname)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('firstname')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('firstname', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('firstname', $firstname, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $name = old('name', isset($customerscaracts->name) ? mb_strtoupper($customerscaracts->name) : '');
                $label_txt = ucfirst(trans('validation.attributes.name'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'required'=>'required',
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'name'
                ];
                $css_state = '';
                if (!empty($name)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('name')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('name', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('name', $name, $attributes) !!}
                </div>
            </div>
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
                $address = old('address', isset($customerscaracts->address) ? ucwords(mb_strtolower($customerscaracts->address)) : '');
                $label_txt = ucfirst(trans('validation.attributes.address'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'address'
                ];
                $css_state = '';
                if (!empty($address)) {
                    $css_state = 'has-success';
            }
            if ($errors->has('address')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('address', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('address', $address, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $address_more = old('address_more', isset($customerscaracts->address_more) ? ucwords(mb_strtolower($customerscaracts->address_more)) : '');
                $label_txt = ucfirst(trans('validation.attributes.address_more'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'address_more'
                ];
                $css_state = '';
                if (!empty($address_more)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('address_more')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('address_more', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('address_more', $address_more, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $zip = old('zip', isset($customerscaracts->zip) ? $customerscaracts->zip : '');
                $label_txt = ucfirst(trans('validation.attributes.zip'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'zip'
                ];
                $css_state = '';
                if (!empty($zip)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('zip')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('zip', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('zip', $zip, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $city = old('city', isset($customerscaracts->city) ? mb_strtoupper($customerscaracts->city) : '');
                $label_txt = ucfirst(trans('validation.attributes.city'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'city'
                ];
                $css_state = '';
                if (!empty($city)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('city')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('city', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('city', $city, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $province = old('province', isset($customerscaracts->province) ? ucwords(mb_strtolower($customerscaracts->province)) : '');
                $label_txt = ucfirst(trans('validation.attributes.province'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'province'
                ];
                $css_state = '';
                if (!empty($province)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('province')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('province', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('province', $province, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $region = old('region', isset($customerscaracts->region) ? ucwords(mb_strtolower($customerscaracts->region)) : '');
                $label_txt = ucfirst(trans('validation.attributes.district'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'region'
                ];
                $css_state = '';
                if (!empty($region)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('region')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('region', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('region', $region, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $subregion = old('subregion', isset($customerscaracts->subregion) ? ucwords(mb_strtolower($customerscaracts->subregion)) : '');
                $label_txt = ucfirst(trans('validation.attributes.county'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'subregion'
                ];
                $css_state = '';
                if (!empty($subregion)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('subregion')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('subregion', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('subregion', $subregion, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $country_id = old('country_id', isset($customerscaracts->country_id) ? $customerscaracts->country_id : '');
                $label_txt = ucfirst(trans('validation.attributes.country'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'country_id'
                ];
                $css_state = '';
                if (!empty($country_id)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('country_id')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('country_id', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::select('country_id', $countries, $country_id, $attributes) !!}
                </div>
            </div>
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
                $phone_1 = old('phone_1', isset($customerscaracts->phone_1) ? $customerscaracts->phone_1 : '');
                $phone_1 = preg_replace('/\s+/', '', $phone_1);
                $label_txt = ucfirst(trans('validation.attributes.phone'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'required'=>'required',
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'phone_1'
                ];
                $css_state = '';
                if (!empty($phone_1)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('phone_1')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('phone_1', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::tel('phone_1', $phone_1, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $phone_mobile = old('phone_mobile', isset($customerscaracts->phone_mobile) ? $customerscaracts->phone_mobile : '');
                $phone_mobile = preg_replace('/\s+/', '', $phone_mobile);
                $label_txt = ucfirst(trans('validation.attributes.mobile'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'phone_mobile'
                ];
                $css_state = '';
                if (!empty($phone_mobile)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('phone_mobile')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('phone_mobile', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::tel('phone_mobile', $phone_mobile, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $fax = old('fax', isset($customerscaracts->fax) ? $customerscaracts->fax : '');
                $fax = preg_replace('/\s+/', '', $fax);
                $label_txt = ucfirst(trans('validation.attributes.fax'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'fax'
                ];
                $css_state = '';
                if (!empty($fax)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('fax')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('fax', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::tel('fax', $fax, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $emails = old('emails', isset($customerscaracts->emails) ? $customerscaracts->emails : Auth::user()->email);
                $emails = mb_strtolower($emails);
                $label_txt = ucfirst(trans('validation.attributes.email')) . 's';
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'emails'
                ];
                $css_state = '';
                if (!empty($emails)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('emails')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('emails', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::email('emails', $emails, $attributes) !!}
                    <em>{!! trans('dashboard.separated_by_semicolon') !!}</em>
                </div>
            </div>
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
                $twitter = old('twitter', isset($customerscaracts->twitter) ? $customerscaracts->twitter : '');
                $twitter = mb_strtolower($twitter);
                $label_txt = 'Twitter';
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'twitter'
                ];
                $css_state = '';
                if (!empty($twitter)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('twitter')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('twitter', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('twitter', $twitter, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $facebook = old('facebook', isset($customerscaracts->facebook) ? $customerscaracts->facebook : '');
                $facebook = mb_strtolower($facebook);
                $label_txt = 'Facebook';
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'facebook'
                ];
                $css_state = '';
                if (!empty($facebook)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('facebook')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('facebook', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('facebook', $facebook, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
                $label_txt = ucfirst(trans('contact_informations.label_optin_agree_emails'));
                $css_state = '';
                if (!empty($agree_emails) && '1' == $agree_emails) {
                    $css_state = 'has-success';
                    $attributes = [
                            'id'=>'agree_emails',
                        //'checked'=>'checked'
                    ];
                    $checkbox_attributes = [
                            'id'=>'checkbox_agree_emails',
                            'checked'=>'checked'
                    ];
                } else {
                    $attributes = [
                            'id'=>'agree_emails'
                    ];
                    $checkbox_attributes = [
                            'id'=>'checkbox_agree_emails'
                    ];
                }
                if ($errors->has('agree_emails')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                <div class="col-xs-12">
                    <div class="checkbox {!! $css_state !!}">
                        <label for="checkbox_agree_emails">
                            {!! Form::checkbox('checkbox_agree_emails', !empty($agree_emails) ? true : false, !empty($agree_emails) ? $agree_emails : old('agree_emails', false), $checkbox_attributes) !!}
                            {!! Form::hidden('agree_emails', !empty($agree_emails) ? $agree_emails : old('agree_emails', false), $attributes) !!}
                            {!! $label_txt !!}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr>
    <div class="form-group">
        <div class="col-sm-12 text-center">
            {!! Form::button('<i class="fa fa-refresh fa-fw"></i>Update', ['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-exception']) !!}
            <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! trans('navigation.back') !!}" class="btn btn-default pull-right"><i class="fa fa-mail-reply fa-fw"></i>{!! trans('navigation.back') !!}</a>
        </div>
    </div>

    {!! Form::close() !!}

@endsection