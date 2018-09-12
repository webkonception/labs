@extends('layouts.portal')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2>Register<i class="fa fa-user-plus fa-fw"></i></h2></div>
                    <div class="panel-body">
                        @if(Session::has('register_message'))
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert {!! Session::has('register_message.type') ? 'alert-' . Session::get('register_message.type') : 'alert-info' !!}">
                                        <p><strong class="{!! Session::has('register_message.type') ? 'text-' . Session::get('register_message.type') : '' !!}">{!! Session::get('register_message.text') !!}</strong></p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ('success' !== Session::get('register_message.type'))
                        <div class="row">
                            <div class="col-sm-12">
                                {!! Form::open(array('route'=>'register', 'class'=>'form-horizontal', 'role'=>'form')) !!}
                                    {!! csrf_field() !!}

                                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                        {!! Form::label('username', 'Username', ['class'=>'col-md-4 control-label']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('username', old('username'), ['required', 'class'=>'form-control', 'placeholder'=>'Username', 'required'=>'required']) !!}
                                            @if ($errors->has('username'))
                                            <span class="help-block"><strong>{{ $errors->first('username') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        {!! Form::label('email', 'E-Mail', ['class'=>'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=> 'Email', 'required'=>'required', 'autocomplete'=>'off']) !!}
                                            @if ($errors->has('email'))
                                            <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        {!! Form::label('password', 'Password', ['class'=>'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> 'Password', 'required'=>'required']) !!}
                                            @if ($errors->has('password'))
                                            <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        {!! Form::label('password_confirmation', 'Confirm password', ['class'=>'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=> 'Password', 'required'=>'required']) !!}
                                            @if ($errors->has('password_confirmation'))
                                            <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="cols-sm-12 col-md-8 col-md-offset-4 text-center">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! Form::button('<i class="fa fa-btn fa-user-plus fa-fw"></i>Register', ['type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        @endif

                        @if (Session::has('register_message'))
                            {!! Session::forget('register_message') !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
