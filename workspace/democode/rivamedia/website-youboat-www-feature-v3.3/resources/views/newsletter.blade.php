@extends('layouts.' . (preg_match('/landing/', $currentRoute) ? 'landing' : 'theme'))
<?php
$metas_title = trans('metas.newsletter_title');
    $metas_description = trans('metas.newsletter_desc');
    $metas = [
            'metas_title' => $metas_title . ' - ' . $website_name,
            'metas_description' => $metas_description
        //,'metas_keywords' => $metas_keywords
    ];
?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

{{--@section('javascript')
    @if($errors->any() || Session::has('errors') || Session::has('message') || Session::has('newsletter_message'))
        <script>$(document).ready(function(){$("#msgModal").modal('show');});</script>
    @endif
@endsection--}}

@section('content')
    <div class="row">

        <div class="col-sm-8 col-sm-offset-2">

            @if(Session::has('newsletter_message'))
                @include('theme.partials.modals.msg-modal', ['form_referrer'=>'form_newsletter', 'title_modal'=>trans('navigation.newsletter'),'message_modal'=>Session::get('newsletter_message.text'), 'message_type'=>Session::get('newsletter_message.type')])
                {{--{!! Session::forget('newsletter_message') !!}--}}
            @elseif(Session::has('errors'))
                <?php
                $message_modal = '<ul>';
                $message_modal .= implode('', $errors->all('<li>:message</li>'));
                $message_modal .= '</ul>';
                ?>
                @include('theme.partials.modals.msg-modal', ['form_referrer'=>'form_newsletter', 'title_modal'=>trans('navigation.newsletter'),'message_modal'=>$message_modal, 'message_type'=>'error'])
            @endif

            <div class="well row">
                <div class="col-sm-12 well well-white">
                    <h3>{!! trans('newsletter.title', ['website_name' => $website_name]) !!}</h3>
                    <p>{!! trans('newsletter.desc', ['website_name' => $website_name]) !!}</p>
                    <p>{!! trans('newsletter.more') !!}</p>
                </div>
                <div class="spacer-20"></div>
                <div class="col-sm-12">
                    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.newsletter'), 'class'=>'form', 'id'=>'form_newsletter', 'role'=>'form')) !!}
                    {!! csrf_field() !!}
                    {!! Form::hidden('country_code', $country_code) !!}

                        <div class="form-group col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                            {!! Form::label('name', ucfirst(trans('validation.attributes.name')), ['class'=>'control-label']) !!}
                            {!! Form::text('name', isset($name) ? $name : old('name'), array('class'=>'form-control', 'placeholder'=>trans('validation.attributes.name'))) !!}
                            @if ($errors->has('name'))
                                <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group col-sm-6 {{ $errors->has('email') ? 'has-error' : '' }}">
                            {!! Form::label('email', ucfirst(trans('validation.attributes.email')), ['class'=>'control-label']) !!}
                            {!! Form::email('email', isset($email) ? $email : old('email'), array('class'=>'form-control', 'placeholder'=>trans('validation.attributes.email') . '*', 'required'=>'required')) !!}
                            @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                        </div>
                        {{--@if (!app()->isLocal() && config('youboat.' . $country_code . '.recaptcha'))
                        <div class="form-group col-sm-8 {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }} controls">
                            <div class="row">
                                <div class="col-xs-12">
                                    {!! Recaptcha::render(['lang'=> config('app.locale'), 'theme'=>'light', 'callback'=>'recaptchaCallback']) !!}
                                </div>
                                @if ($errors->has('g-recaptcha-response'))
                                    <div class="col-xs-12">
                                        <div class="help-block">
                                            <ul class="error-list">
                                                @foreach ($errors->get('g-recaptcha-response') as $message)
                                                    <li><strong>{!! $message !!}</strong></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif--}}

                        {{--<div class="form-group text-center {!! (!app()->isLocal() && config('youboat.' . $country_code . '.recaptcha')) ? 'col-xs-12 col-sm-12 col-md-12 col-lg-4' : 'col-xs-12 col-sm-12 col-md-6' !!} pull-right">--}}
                        <div class="form-group col-xs-12 col-sm-12 col-md-6 pull-right">
                            {!! Form::button(trans('elements.connect-with-us.subcribe'), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary btn-block btn-exception']) !!}
                            <p class="meta-data">{!! trans('elements.connect-with-us.dont_worry') !!}</p>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
        <div class="col-sm-12">
            <a href="{{ url(trans_route($currentLocale, '/')) }}" title="{!! trans('navigation.back_to_home') !!}" class="btn btn-default pull-right">
                <i class="fa fa-mail-reply fa-fw"></i>
                {!! trans('navigation.back') !!}
            </a>
        </div>
    </div>
@endsection

{{--
@if (Session::has('newsletter_message'))
    {!! Session::forget('newsletter_message') !!}
@endif--}}
