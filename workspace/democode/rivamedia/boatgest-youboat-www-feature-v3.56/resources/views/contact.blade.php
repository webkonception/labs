@extends('layouts.portal')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2>Contact<i class="fa fa-envelope-o fa-fw"></i></h2></div>

                    <div class="panel-body">
                        @if(Session::has('contact_message'))
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert {!! Session::has('contact_message.type') ? 'alert-' . Session::get('contact_message.type') : 'alert-info' !!}">
                                        <p><strong class="{!! Session::has('contact_message.type') ? 'text-' . Session::get('contact_message.type') : '' !!}">{!! Session::get('contact_message.text') !!}</strong></p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ('success' !== Session::get('contact_message.type'))
                            {{--@if ($errors->any())
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-danger">
                                        <ul>
                                            {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif--}}
                            <div class="row">
                                <div class="col-sm-12">
                                    {!! Form::open(array('route'=>'contact_store', 'class'=>'form', 'role'=>'form')) !!}
                                    {!! csrf_field() !!}
                                    {!! Form::hidden('country_code', 'uk') !!}

                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        {!! Form::label('name', 'Name *', ['class'=>'control-label']) !!}
                                        {!! Form::text('name', old('name'), array('class'=>'form-control', 'placeholder'=>'Name', 'required'=>'required')) !!}
                                        @if ($errors->has('name'))
                                            <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('name') }}</strong></span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        {!! Form::label('email', 'Email *', ['class'=>'control-label']) !!}
                                        {!! Form::email('email', old('email'), array('class'=>'form-control', 'placeholder'=>'Email', 'required'=>'required')) !!}
                                        @if ($errors->has('email'))
                                            <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('email') }}</strong></span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                        {!! Form::label('phone', 'Phone', ['class'=>'control-label']) !!}
                                        {!! Form::email('phone', old('phone'), array('class'=>'form-control', 'placeholder'=>'Phone')) !!}
                                        @if ($errors->has('phone'))
                                            <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('phone') }}</strong></span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                                        {!! Form::label('message', 'Message *', ['class'=>'control-label']) !!}
                                        {!! Form::textarea('message', old('message'), array('class'=>'form-control', 'placeholder'=>'Message', 'required'=>'required')) !!}
                                        @if ($errors->has('message'))
                                            <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('message') }}</strong></span>
                                        @endif
                                    </div>

                                    @if (!app()->isLocal())
                                        <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }} controls">
                                            <div class="row">
                                                <div class="{{ $errors->has('g-recaptcha-response') ? 'col-sm-12 col-md-5' : 'col-sm-12' }}">
                                                    {!! Recaptcha::render(['lang'=>'en', 'theme'=>'light', 'callback'=>'recaptchaCallback']) !!}
                                                </div>
                                                @if ($errors->has('g-recaptcha-response'))
                                                    <div class="col-sm-12 col-md-7">
                                                        <div class="help-block alert alert-danger">
                                                            <ul class="error-list">
                                                                @foreach ($errors->get('g-recaptcha-response') as $message)
                                                                    <li><strong class="text-danger">{!! $message !!}</strong></li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group text-center">
                                        <em class="pull-right">* required</em>
                                        {!! Form::button('<i class="fa fa-btn fa-envelope-o fa-fw"></i>Contact Us!', ['type'=>'submit', 'class'=>'btn btn-lg btn-primary btn-exception']) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        @endif

                        @if (Session::has('contact_message'))
                            {!! Session::forget('contact_message') !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
