<?php
$currentController = 'password';
$currentAction = 'change';
?>
@extends(config('quickadmin.route') . '.layouts.master')


@section('content')

    @if ($errors->any())
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <ul>
                        {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <section class="row well well-white">
    {!! Form::open(array('url'=>config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.change_password'), 'class'=>'form', 'autocomplete'=>'off', 'role'=>'form')) !!}
        <div class="col-sm-12 col-md-offset-3 col-md-6">
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
                {!! Form::label('email', $label_txt, ['class'=>'col-sm-4 col-md-5 control-label '. $css_state]) !!}
                <div class="col-sm-8 col-md-7">
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
                {!! Form::label('password', $label_txt, ['class'=>'col-sm-4 col-md-5 control-label']) !!}
                <div class="col-sm-8 col-md-7">
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
                {!! Form::label('password_confirmation', $label_txt, ['class'=>'col-sm-4 col-md-5 control-label']) !!}
                <div class="col-sm-8 col-md-7">
                    {!! Form::password('password_confirmation', $attributes) !!}
                    @if ($errors->has('password_confirmation'))
                    <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="row form-group">
                <div class="col-sm-offset-6 col-md-offset-5 col-sm-6 col-md-7 text-right">
                    {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . trans('navigation.update'), ['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-exception']) !!}
                </div>
            </div>

        </div>

    {!! Form::close() !!}
    </section>
    <div class="row">
        <div class="col-xs-12 text-right">
            <a href="{{ url(config('quickadmin.homeRoute')) }}" title="{!! trans('navigation.cancel') !!}" class="btn btn-default pull-righ"><i class="fa fa-ban fa-fw"></i>{!! ucfirst(trans('navigation.cancel')) !!}</a>
        </div>
    </div>
@endsection