@extends('layouts.portal')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-key fa-3x modal-icon pull-left"></i>
                        <h2 class="modal-title">{!! ucfirst(trans('navigation.auth.passwords.email')) !!}</h2>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url'=>'password/reset', 'class'=>'form-horizontal', 'role'=>'form', 'autocomplete'=>'off')) !!}
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                {!! Form::label('email', ucfirst(trans('validation.attributes.email')) . '*', ['class'=>'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', old('email', isset($email) ? $email : null), array('class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.email')) . '*', 'required'=>'required')) !!}
                                    @if ($errors->has('email'))
                                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                {!! Form::label('password', ucfirst(trans('validation.attributes.password')) . '*', ['class'=>'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.password')), 'required'=>'required']) !!}
                                    @if ($errors->has('password'))
                                    <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                {!! Form::label('password_confirmation', ucfirst(trans('validation.attributes.password_confirmation')) . '*', ['class'=>'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.password_confirmation')), 'required'=>'required']) !!}
                                    @if ($errors->has('password_confirmation'))
                                    <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="cols-sm-12 col-md-8 col-md-offset-4 text-center">
                                    {!! Form::button('<i class="fa fa-btn fa-refresh fa-fw"></i>' . trans('navigation.auth.passwords.email'), ['type'=>'submit', 'class'=>'btn btn-lg btn-block btn-primary btn-exception']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                        {!! htmlspecialchars_decode(link_to(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.login'), '<i class="fa fa-mail-reply fa-fw"></i>' . trans('navigation.back_to') . ' ' . trans('navigation.login'), ['class' => 'btn btn-danger pull-right'])) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

