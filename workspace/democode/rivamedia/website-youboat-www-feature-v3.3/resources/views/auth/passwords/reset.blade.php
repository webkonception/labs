@extends('layouts.theme')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                <div class="clearfix well well-white signup-form">

                    <h2>{{ trans('navigation.auth.passwords.reset') }}<i class="fa fa-lock fa-fw"></i></h2>

                    @if(Session::has('message'))
                        @include('theme.partials.modals.msg-modal', ['form_referrer'=>Session::get('message.referrer'),'title_modal'=>Session::get('message.title'),'message_modal'=>Session::get('message.text'), 'message_type'=>Session::get('message.type')])
                    @elseif(!empty($message))
                        @include('theme.partials.modals.msg-modal', ['form_referrer'=>$message['referrer'],'title_modal'=>$message['title'],'message_modal'=>$message['text'], 'message_type'=>$message['type']])
                    @endif

                    {{--{!! Form::open(array('url'=>'password/reset', 'class'=>'form-horizontal regular-signup', 'autocomplete'=>'off')) !!}--}}
                    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.password_reset'), 'class'=>'form regular-signup', 'autocomplete'=>'off', 'role'=>'form', 'id'=>'form_password_reset')) !!}
                        {!! csrf_field() !!}
                        {!! Form::hidden('token', $token) !!}

                        <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', trans('validation.attributes.email'), ['class'=>'col-sm-12 control-label']) !!}
                            <div class="col-sm-12">
                                {!! Form::email('email', old('email', isset($email) ? $email : null), ['class'=>'form-control', 'placeholder'=>trans('validation.attributes.email') . '*', 'required'=>'required']) !!}
                                @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password', trans('validation.attributes.password'), ['class'=>'col-sm-12 control-label']) !!}
                            <div class="col-sm-12">
                                {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> trans('validation.attributes.password') . '*', 'required'=>'required']) !!}
                                @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            {!! Form::label('password_confirmation', trans('validation.attributes.password_confirmation'), ['class'=>'col-sm-12 control-label']) !!}
                            <div class="col-sm-12">
                                {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=> trans('validation.attributes.password_confirmation') . '*', 'required'=>'required']) !!}
                                @if ($errors->has('password_confirmation'))
                                <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="cols-sm-12 text-center">
                                {!! Form::button('<i class="fa fa-btn fa-refresh fa-fw"></i>' . trans('navigation.auth.passwords.email'), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary btn-exception']) !!}
                                <br>
                                <br>
                                {!! link_trans_url(trans_route($currentLocale, 'routes.password_email'), 'navigation.auth.passwords.try_again', ['email' => isset($email) ? $email : ''], ['class' => 'btn btn-warning']) !!}
                                {{--<a href="{!! url(trans_route($currentLocale, 'routes.password_email')) !!}/{!! isset($email) ? $email : '' !!}" title="{!! trans('navigation.auth.passwords.try_again') !!}" class="btn btn-warning">{!! trans('navigation.auth.passwords.try_again') !!}</a>--}}
                            </div>
                        </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

