@extends('layouts.portal')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-key fa-3x modal-icon pull-left"></i>
                        <h2 class="modal-title">{!! ucfirst(trans('boatgest.request_for_access_to')) !!}</h2>
                    </div>
                    <div class="panel-body">
                        <p class="font-bold">{!! ucfirst(trans('boatgest.please_fill_inputs')) !!}</p>
                        <p class="well">{!! trans('boatgest.password_form_text') !!}</p>
                        {!! Form::open(array('url'=>'password/email', 'class'=>'form-horizontal', 'role'=>'form', 'autocomplete'=>'off')) !!}
                            {!! csrf_field() !!}
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                {!! Form::label('name', ucfirst(trans('boatgest.your_company_name')) . '*', ['class'=>'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('name', old('name'), array('class'=>'form-control', 'placeholder'=>ucfirst(trans('boatgest.your_company_name')) . '*', 'required'=>'required')) !!}
                                    @if ($errors->has('name'))
                                    <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('name') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                {!! Form::label('email', ucfirst(trans('validation.attributes.email')) . '*', ['class'=>'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', old('email', isset($email) ? $email : null), array('class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.email')) . '*', 'required'=>'required')) !!}
                                    @if ($errors->has('email'))
                                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                    @endif
                                </div>
                            </div>

                            {{--<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                {!! Form::label('username', 'Username', ['class'=>'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('username', (Request::get('username') ? Request::get('username') : old('username', null)), ['class'=>'form-control', 'placeholder'=>'Username']) !!}
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

                            <div class="form-group">
                                <div class="cols-sm-12 col-md-8 col-md-offset-4 text-center">
                                    {!! Form::button('<i class="fa fa-btn fa-envelope fa-fw"></i>' . trans('navigation.auth.passwords.send_reset_link'), ['type'=>'submit', 'class'=>'btn btn-lg btn-block btn-primary btn-exception']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                        {!! htmlspecialchars_decode(link_to(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.dashboard'), '<i class="fa fa-mail-reply fa-fw"></i>' . trans('navigation.back') , ['class' => 'btn btn-danger pull-right'])) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

