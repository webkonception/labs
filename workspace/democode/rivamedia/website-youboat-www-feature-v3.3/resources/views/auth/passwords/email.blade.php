@extends('layouts.theme')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">

                <div class="clearfix well well-white signup-form">

                    <h2>{{ trans('navigation.auth.passwords.email') }}<i class="fa  fa-fw"></i></h2>

                    @if(Session::has('status'))
                        <div class="alert alert-success">
                            <p><strong class="text-success">{!! Session::get('status') !!}</strong></p>
                        </div>
                        {{--{!! Session::forget('status') !!}--}}
                    @else
                    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.password_email'), 'class'=>'form regular-signup', 'autocomplete'=>'off', 'role'=>'form', 'id'=>'form_password_email')) !!}
                    {{--{!! Form::open(array('url'=>trans_route($currentLocale, 'routes.dashboard_reset_password'), 'class'=>'form regular-signup', 'autocomplete'=>'off', 'role'=>'form')) !!}--}}
                        {!! csrf_field() !!}

                        <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', trans('validation.attributes.email'), ['class'=>'col-sm-12 control-label']) !!}
                            <div class="col-sm-12">
                                {!! Form::email('email', old('email', isset($email) ? $email : null), ['class'=>'form-control', 'placeholder'=>trans('validation.attributes.email') . '*', 'required'=>'required']) !!}
                                @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        {{--<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            {!! Form::label('username', trans('validation.attributes.username'), ['class'=>'col-sm-12 control-label visible-xs']) !!}
                            <div class="col-sm-12">
                                {!! Form::text('username', (Request::get('username') ? Request::get('username') : old('username', null)), ['class'=>'form-control', 'placeholder'=>trans('validation.attributes.username') . '*', 'required'=>'required']) !!}
                                @if ($errors->has('username'))
                                <span class="help-block"><strong>{{ $errors->first('username') }}</strong></span>
                                @endif
                            </div>
                        </div>--}}

                        {{--<div class="form-group{{ $errors->has('g-recaptcha') ? ' has-error' : '' }}">
                            <div class="col-sm-8 col-sm-offset-4">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_PUBLIC_KEY') }}"></div>
                            </div>
                        </div>--}}

                        <div class="row form-group">
                            <div class="cols-sm-12 text-center">
                                {!! Form::button('<i class="fa fa-btn fa-envelope fa-fw"></i>' . trans('navigation.auth.passwords.send_reset_link'), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary btn-exception']) !!}
                            </div>
                        </div>

                    {!! Form::close() !!}
                    @endif
                    {!! htmlspecialchars_decode(link_to(LaravelLocalization::transRoute('routes.dashboard'), '<i class="fa fa-mail-reply fa-fw"></i>' . trans('navigation.back') , ['class' => 'btn btn-danger pull-right'])) !!}
                </div>

            </div>
        </div>
    </div>
@endsection

