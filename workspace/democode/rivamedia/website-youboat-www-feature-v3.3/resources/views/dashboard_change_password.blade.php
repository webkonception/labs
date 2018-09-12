@extends('layouts.theme')

<?php
    $metas_title = 'Dasboard - Change your password';

    $metas_description = 'Dasboard - Change your password';
    $metas = [
        'metas_title' => $metas_title . ' - ' . $website_name,
        'metas_description' => $metas_description
    ];
?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-9">
            <div class="icon-box ibox-outline">
                <div class="ibox-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <h2 class="uppercase strong accent-color text-left inbox-title">{!! trans('navigation.auth.passwords.change') !!}</h2>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 text-center">
                <a href="{{ url(trans_route($currentLocale, 'routes.logout')) }}" title="{!! trans('navigation.logout') !!}" class="btn btn-sm btn-warning btn-block"><i class="fa fa-sign-out fa-fw"></i>{!! trans('navigation.logout') !!}</a>
                <br>
                <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! trans('dashboard.private_individuals_subtitle') !!}" class="btn btn-sm btn-danger btn-block"><i class="fa fa-dashboard fa-fw"></i>{!! trans('dashboard.private_individuals_subtitle') !!}</a>
        </div>
    </div>

    <hr>
    @if(Session::has('message'))
        @include('theme.partials.modals.msg-modal', ['form_referrer'=>Session::get('message.referrer'),'title_modal'=>Session::get('message.title'),'message_modal'=>Session::get('message.text'), 'message_type'=>Session::get('message.type')])
    @endif

    <section class="row well well-white">
    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.dashboard_change_password'), 'class'=>'form', 'autocomplete'=>'off', 'role'=>'form')) !!}
    {!! csrf_field() !!}
        <div class="col-sm-8 col-sm-offset-2">
            <?php
            $email = old('email', isset($email) ? $email : null);
            $label_txt = ucfirst(trans('validation.attributes.email'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'readonly' => 'readonly',
                    'class' => 'form-control',
                    'id' => 'email'
            ];
            $css_state = '';
            if (!empty($email)) {
                $css_state = 'text-success';
            }
            ?>
            <div class="row form-group">
                {!! Form::label('email', $label_txt, ['class'=>'col-xs-12 col-sm-4 control-label '. $css_state]) !!}
                <div class="col-xs-12 col-sm-8">
                    <strong class="{!! $css_state !!}">{!! $email !!}</strong>
                    {!! Form::hidden('email', $email, $attributes) !!}
                </div>
            </div>

            <?php
                $label_txt = ucfirst(trans('validation.attributes.password'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'required'=>'required',
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'password'
                ];
                $css_state = '';
                if ($errors->has('password')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="row form-group {!! $css_state !!}">
                {!! Form::label('password', $label_txt, ['class'=>'col-xs-12 col-sm-4 control-label']) !!}
                <div class="col-xs-12 col-sm-8">
                    {!! Form::password('password', $attributes) !!}
                    @if ($errors->has('password'))
                    <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>
            </div>

            <?php
                $label_txt = ucfirst(trans('validation.attributes.password_confirmation'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'required'=>'required',
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'password_confirmation'
                ];
                $css_state = '';
                if ($errors->has('password_confirmation')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="row form-group {!! $css_state !!}">
                {!! Form::label('password_confirmation', $label_txt, ['class'=>'col-xs-12 col-sm-4 control-label']) !!}
                <div class="col-xs-12 col-sm-8">
                    {!! Form::password('password_confirmation', $attributes) !!}
                    @if ($errors->has('password_confirmation'))
                    <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-sm-offset-4 col-sm-8 text-center">
                    {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . trans('navigation.submit'), ['type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary btn-exception']) !!}
                    <br>
                </div>
            </div>

        </div>

    {!! Form::close() !!}
    </section>
    <div class="row">
        <div class="col-xs-12 text-right">
            <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! trans('navigation.back') !!}" class="btn btn-default"><i class="fa fa-mail-reply fa-fw"></i>{!! trans('navigation.back') !!}</a>
        </div>
    </div>
@endsection
