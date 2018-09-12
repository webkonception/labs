@extends('layouts.portal')

@section('content')
<div class="opacity20">
    <div class="loginColumns animated fadeInDown">
        <div class="row mt20 mt0-xs" >
            <div class="col-sm-6 col-xs-12">
                <div class="ibox-content">
                    <h2>
                        {!! ucfirst(trans('boatgest.customer_of_boatgest')) !!}
                        <br>
                        <strong>{!! ucfirst(trans('boatgest.sign_in')) !!} !</strong>
                    </h2>
                    {!! Form::open(array('route'=>'login', 'class'=>'m-t', 'autocomplete'=>'off')) !!}
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            {!! Form::label('username', ucfirst(trans('validation.attributes.username')) . '*', ['class'=>'control-label']) !!}
                            {!! Form::text('username', (Request::get('username') ? Request::get('username') : old('username', null)), ['class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.username')), 'required'=>'required']) !!}
                            @if ($errors->has('username'))
                                <span class="help-block"><strong>{{ $errors->first('username') }}</strong></span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password', ucfirst(trans('validation.attributes.password')) . '*', ['class'=>'control-label']) !!}
                            <span class="fright"><a data-toggle="modal" data-target="#forgotPass"><small>{!! ucfirst(trans('navigation.auth.passwords.forgot')) . '*' !!}</small></a></span>
                            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.password')), 'required'=>'required']) !!}
                            @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('remember', null) !!} {!! ucfirst(trans('validation.attributes.remember_me')) !!}
                                </label>
                            </div>
                        </div>
                        {!! Form::button(ucfirst(trans('navigation.login')), ['type'=>'submit', 'class'=>'btn btn-primary block full-width m-b']) !!}
                        @if(Session::has('contact_message'))
                        <div class="alert {!! Session::has('contact_message.type') ? 'alert-' . Session::get('contact_message.type') : 'alert-info' !!} alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            {!! Session::get('contact_message.text') !!}
                        </div>
                        {!! Session::forget('contact_message') !!}
                        @endif
                        @if(Session::has('status'))
                        <div class="alert alert-info alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            {!! Session::get('status') !!}
                        </div>
                        {!! Session::forget('status') !!}
                        @endif
                        <hr>
                        <a class="btn btn-sm btn-white btn-block" data-toggle="modal" data-target="#comptePro">
                            {!! ucfirst(trans('boatgest.do_not_have_account')) !!}
                            <br>
                            <strong>{!! ucfirst(trans('boatgest.request_a_pro_account')) !!}</strong>
                        </a>
                    </form>

                </div>
            </div>

            <div class="col-sm-6 col-xs-12" >
                <h2 class="mt0 mt20-xs">{!! ucfirst(trans('boatgest.you_are_professional_of_boating')) !!}</h2>
                <p>{!! trans('boatgest.explications', ['country_code'=>$country_code]) !!}</p>

                <p>
                    <small>
                        {!! trans('boatgest.contact_us') !!}
                        [<a href="{!! url(config('youboat.'. $country_code .'.website_url') . '/' . app('laravellocalization')->transRoute('routes.contact')) !!}" target="_blank" title="{!! ucfirst(trans('boatgest.contact_form')) !!}">{!! ucfirst(trans('boatgest.contact_form')) !!}</a>]
                    </small>
                </p>

                <h2>{!! ucfirst(trans('boatgest.are_you_an_individual')) !!}</h2>
                <p>{!! ucfirst(trans('boatgest.manage_your_ad_directly')) !!}</p>
                <p>- <a href="{!! url(config('youboat.'. $country_code .'.website_youboat_url') . '/' . app('laravellocalization')->transRoute('routes.dashboard')) !!}">{!! ucfirst(trans('boatgest.individual_space')) !!} YOUBOAT {!! mb_strtoupper($country_code) !!}</a>
                </p>

            </div>

        </div>
    </div>
</div>

<div class="modal inmodal" id="comptePro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{!! ucfirst(trans('navigation.close')) !!}</span></button>
                <i class="fa fa-comments-o modal-icon"></i>
                <h4 class="modal-title">{!! ucfirst(trans('boatgest.subscribe_to_pro_account')) !!}</h4>
                <small class="font-bold">{!! ucfirst(trans('boatgest.fill_requested_information')) !!}</small>
            </div>
            {!! Form::open(array('route'=>'contact_login', 'class'=>'form', 'role'=>'form')) !!}
            {!! csrf_field() !!}
            <div class="modal-body">
                    {!! Form::hidden('country_code', mb_strtolower($country_code)) !!}
                    {!! Form::hidden('type_request', 'BoatGest') !!}

                    <div class="form-group {{ $errors->has('name') && !empty(old('sendask')) ? 'has-error' : '' }}">
                        {!! Form::label('name', ucfirst(trans('boatgest.your_company_name')) . '*', ['class'=>'control-label']) !!}
                        {!! Form::text('name', old('name'), array('class'=>'form-control', 'placeholder'=>ucfirst(trans('boatgest.your_company_name')) . '*', 'required'=>'required')) !!}
                        @if ($errors->has('name') && !empty(old('sendask')))
                            <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('name') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('phone') && !empty(old('sendask')) ? 'has-error' : '' }}">
                        {!! Form::label('phone', ucfirst(trans('validation.attributes.phone')) . '*', ['class'=>'control-label']) !!}
                        {!! Form::text('phone', old('phone'), array('class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.phone')) . '*', 'required'=>'required')) !!}
                        @if ($errors->has('phone') && !empty(old('sendask')))
                            <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('phone') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('email') && !empty(old('sendask')) ? 'has-error' : '' }}">
                        {!! Form::label('email', ucfirst(trans('validation.attributes.email')) . '*', ['class'=>'control-label']) !!}
                        {!! Form::email('email', old('email'), array('class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.email')) . '*', 'required'=>'required')) !!}
                        @if ($errors->has('email') && !empty(old('sendask')))
                            <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('email') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('message') && !empty(old('sendask')) ? 'has-error' : '' }}">
                        {!! Form::label('message', ucfirst(trans('validation.attributes.message')) . '*', ['class'=>'control-label']) !!}
                        {!! Form::textarea('message', old('message'), array('class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.message')) . '*', 'required'=>'required')) !!}
                        @if ($errors->has('message') && !empty(old('sendask')))
                            <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('message') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{!! ucfirst(trans('navigation.close')) !!}</button>
                    {!! Form::button(ucfirst(trans('boatgest.send_request')), ['type'=>'submit', 'class'=>'btn btn-primary btn-exception', 'name'=>'sendask', 'value'=>"sendask"]) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal inmodal" id="forgotPass" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{!! ucfirst(trans('navigation.close')) !!}</span></button>
                <i class="fa fa-key modal-icon"></i>
                <h4 class="modal-title">{!! ucfirst(trans('boatgest.request_for_access_to')) !!}</h4>
                <small class="font-bold">{!! ucfirst(trans('boatgest.please_fill_inputs')) !!}</small>
            </div>
            {!! Form::open(array('route'=>'password_email', 'class'=>'form', 'role'=>'form', 'autocomplete'=>'off')) !!}
            {!! csrf_field() !!}
            <div class="modal-body">
                {!! Form::hidden('country_code', mb_strtolower($country_code)) !!}
                <div class="modal-body">
                    <p>{!! trans('boatgest.password_form_text') !!}</p>
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        {!! Form::label('name', ucfirst(trans('boatgest.your_company_name')) . '*', ['class'=>'control-label']) !!}
                        {!! Form::text('name', old('name'), array('class'=>'form-control', 'placeholder'=>ucfirst(trans('boatgest.your_company_name')) . '*', 'required'=>'required')) !!}
                        @if ($errors->has('name'))
                            <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('name') }}</strong></span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('email', ucfirst(trans('validation.attributes.email')) . '*', ['class'=>'col-sm-4 control-label']) !!}
                        {!! Form::email('email', old('email', isset($email) ? $email : null), array('class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.email')) . '*', 'required'=>'required')) !!}
                        @if ($errors->has('email'))
                            <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{!! ucfirst(trans('navigation.close')) !!}</button>
                    {!! Form::button(ucfirst(trans('boatgest.send_request')), ['type'=>'submit', 'class'=>'btn btn-primary btn-exception', 'name'=>'sendpass', 'value'=>"sendpass"]) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="/portal/js/jquery-2.1.1.js"></script>
<script src="/portal/js/bootstrap.min.js"></script>
@if ($errors->any() && !empty(old('sendask')))
<script>
$(document).ready(function () {
    $('#comptePro').modal('show');
});
</script>
@elseif ($errors->has('email'))
<script>
    $(document).ready(function () {
        $('#forgotPass').modal('show');
    });
</script>
@endif
@endsection
