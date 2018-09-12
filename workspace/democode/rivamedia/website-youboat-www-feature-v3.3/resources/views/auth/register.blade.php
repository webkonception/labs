@extends('layouts.theme')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">

                <div class="clearfix well well-white signup-form">

                    <h2>{{ trans('navigation.auth.register') }}<i class="fa fa-user-plus fa-fw"></i></h2>
                    @if ('success' !== Session::get('register_message.type'))

                    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.register'), 'class'=>'form regular-signup', 'role'=>'form')) !!}
                        {!! csrf_field() !!}
                        {!! Form::hidden('country_code', $country_code) !!}

                        <div class="row form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            {!! Form::label('username', trans('validation.attributes.username'), ['class'=>'col-sm-12 control-label']) !!}
                            <div class="col-sm-12">
                                {!! Form::text('username', old('username'), ['required', 'class'=>'form-control', 'placeholder'=>trans('validation.attributes.username') . '*', 'required'=>'required']) !!}
                                @if ($errors->has('username'))
                                    <span class="help-block"><strong>{{ $errors->first('username') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', trans('validation.attributes.email'), ['class'=>'col-sm-12 control-label']) !!}
                            <div class="col-sm-12">
                                {!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=> trans('validation.attributes.email') . '*', 'required'=>'required', 'autocomplete'=>'off']) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password', trans('validation.attributes.password'), ['class'=>'col-sm-12 control-label']) !!}
                            <div class="col-sm-12">
                                {!! Form::password('password', ['class'=>'form-control password-input', 'placeholder'=> trans('validation.attributes.password') . '*', 'required'=>'required']) !!}
                                @if ($errors->has('password'))
                                    <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <a href="javascript:void(0);" class="password-generate pass-actions"><i class="fa fa-refresh"></i></a>
                                <div class="progress"><div class="progress-bar password-output" style="width: 0%"></div></div>
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            {!! Form::label('password_confirmation', trans('validation.attributes.password_confirmation'), ['class'=>'col-sm-12 control-label']) !!}
                            <div class="col-sm-12">
                                {!! Form::password('password_confirmation', ['class'=>'form-control password-input', 'placeholder'=> trans('validation.attributes.password_confirmation') . '*', 'required'=>'required']) !!}
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-sm-12 text-center">
                                {!! Form::button('<i class="fa fa-btn fa-user-plus fa-fw"></i>' . trans('navigation.create_account'), ['type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary btn-exception']) !!}
                            </div>
                        </div>

                        {{--<!-- Social Signup -->
                        <div class="social-signup">
                            <span class="or-break">or</span>
                            <button type="button" class="btn btn-block btn-facebook btn-social"><i class="fa fa-facebook"></i> Signup with Facebook</button>
                            <button type="button" class="btn btn-block btn-twitter btn-social"><i class="fa fa-twitter"></i> Signup with Twitter</button>
                        </div>--}}

                    {!! Form::close() !!}

                    @endif

                    @if(Session::has('register_message'))
                        <div class="alert {!! Session::has('register_message.type') ? 'alert-' . Session::get('register_message.type') : 'alert-info' !!}">
                            <p><strong class="{!! Session::has('register_message.type') ? 'text-' . Session::get('register_message.type') : '' !!}">{!! Session::get('register_message.text') !!}</strong></p>
                        </div>
                        {{--{!! Session::forget('register_message') !!}--}}
                    @endif

                </div>

            </div>

        </div>
    </div>
@endsection
