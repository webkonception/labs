@extends('layouts.landing')

@section('content')
    <div class="container-fluid content">
        <div class="row content">
            <div class="col-md-4 col-lg-3 pull-right no-pad">
                <div class="leftline leftline-dark wow fadeInLeft">
                    <div class="leftlineinside">
                        <div class="row">
                            <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-12 col-md-offset-0">
                                <h1 class="logo"><img src="{!! asset('assets/vendor/youboat/landing/img/uk/logo.png') !!}" alt="{!! config('youboat.' . $country_code . '.website_name') !!}" width="610"></h1>
                            </div>
                            <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-12 col-md-offset-0">
                                @if(Session::has('getnotified_message'))
                                    <div class="row">
                                        <div class="col-sm-12 no-pad">
                                            <div class="alert {!!  Session::has('getnotified_message.type') ? 'alert-' . Session::get('getnotified_message.type') : 'alert-info' !!}">
                                                <p><strong class=" {!!  Session::has('getnotified_message.type') ? 'text-' . Session::get('getnotified_message.type') : '' !!}">{!! Session::get('getnotified_message.text') !!}</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ('success' !== Session::get('getnotified_message.type'))
                                    <div class="row">
                                        <div class="col-sm-12 no-pad">
                                            <h2>{!! strtoupper(trans('landing.getnotified')) !!}</h2>
                                            <p>{!! trans('landing.landing_get_notified_text', ['country_code' => strtoupper($country_code)]) !!}</p>
                                        </div>

                                        <div class="col-sm-12">
                                            {{--{!! Form::open(array('url'=>trans_route($currentLocale, 'routes.landing'), 'id'=>'formGetNotified', 'class'=>'form-horizontal')) !!}--}}
                                            {!! Form::open(array('url'=>trans_route($currentLocale, '/'), 'id'=>'formGetNotified', 'class'=>'form-horizontal')) !!}
                                                {!! csrf_field() !!}
                                                {!! Form::hidden('country_code', $country_code) !!}

                                                <div class="control-group">
                                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} floating-label-form-group controls">
                                                        {!! Form::label('name', ucfirst(trans('validation.attributes.name')) . '*', ['class'=>'sr-only control-label']) !!}
                                                        {!! Form::text('name', old('name'), ['required', 'class'=>'form-control input-lg ' . ($errors->has('name') ? ' text-danger' : ''), 'placeholder'=>ucfirst(trans('validation.attributes.name')) . '*']) !!}
                                                        @if ($errors->has('name'))
                                                        <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('name') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} floating-label-form-group controls">
                                                        {!! Form::label('email', ucfirst(trans('validation.attributes.email')) . '*', ['class'=>'sr-only control-label']) !!}
                                                        {!! Form::email('email', old('email'), ['required', 'class'=>'form-control input-lg ' . ($errors->has('email') ? ' text-danger' : ''), 'placeholder'=>ucfirst(trans('validation.attributes.email')) . '*']) !!}
                                                        @if ($errors->has('email'))
                                                        <span class="help-block alert alert-danger"><strong class="text-danger">{{ $errors->first('email') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : ' hidden' }} controls text-center ">
                                                        {!! Recaptcha::render(['lang'=> config('app.locale'), 'theme'=>'light', 'callback'=>'recaptchaCallback']) !!}
                                                        @if ($errors->has('g-recaptcha-response'))
                                                        <div class="help-block alert alert-danger">
                                                            <ul class="error-list">
                                                                @foreach ($errors->get('g-recaptcha-response') as $message)
                                                                    <li><strong class="text-danger">{!! $message !!}</strong></li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <div class="form-group text-center">
                                                        <button type="submit" class="btn btn-red btn-lg btn-block">{!! strtoupper(trans('navigation.getnotified')) !!}</button>
                                                    </div>
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                @endif
                                {{--@if (Session::has('getnotified_message'))
                                    {!! Session::forget('getnotified_message') !!}
                                @endif--}}
                                <div class="row">
                                    <div class="col-sm-12 no-pad">
                                        <h2 class="call"><i class="fa fa-phone"></i>{!! config('youboat.' . $country_code . '.phone') !!}</h2>
                                        <p class="text-right pull-right">
                                            <a href="{!! config('youboat.' . $country_code . '.facebook') !!}" class="blank facebook-color" ><i class="fa fa-facebook fa-fw fa-2x"></i></a>
                                            <a href="{!! config('youboat.' . $country_code . '.twitter') !!}" class="blank twitter-color"><i class="fa fa-twitter fa-fw fa-2x"></i></a>
                                            {{--<a href="/" class="blank linkedin-color"><i class="fa fa-linkedin fa-fw fa-2x"></i></a>--}}
                                        </p>
                                        <p>
                                            <i class="fa fa-envelope fa-fw"></i>{!! config('youboat.' . $country_code . '.email') !!}
                                            <br>
                                            <i class="fa fa-map-marker fa-fw"></i>{!! config('youboat.' . $country_code . '.address') !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-lg-9 promo no-pad">
                <div class="row intropromo">
                    <div class="col-sm-7 no-pad text-center">
                        <div id="clock"></div>
                        <h2 class="wow bounceInDown">{!! strtoupper(trans('landing.site_soon')) !!}...</h2>
                    </div>
                    <div class="col-sm-5">
                        <h4>{!! trans('landing.be_first') !!}</h4>
                        <p><i class="fa fa-clock-o fa-5x pull-right"></i>
                            {!! trans('landing.landing_text_01') !!}
                        </p>
                        <ul class="list-inline lead">
                            <li><a data-target="#aboutModal" data-toggle="modal" class="btn btn-red btn-lg wow fadeInLeft" title="{!! trans('navigation.about') !!}">{!! trans('navigation.about') !!}</a></li>
                            <li><a href="#formGetNotified" data-contact="{!! url(trans_route($currentLocale, 'routes.landingcontact')) !!}" class="btn btn-white btn-lg wow fadeInRight" title="{!! trans('navigation.contact') !!}">{!! trans('navigation.contact') !!}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="about-modal modal fade" id="aboutModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="close-modal" data-dismiss="modal">
                    <i class="fa fa-times-circle"></i>
                </div>
                <div id="about" class="container wow fadeIn ">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <span class="logo"><img src="{!! asset('assets/vendor/youboat/landing/img/uk/logo.png') !!}" alt="Logo" width="610"></span>
                        </div>
                        <div class="col-lg-12">
                            <div class="lead well bg-primary text-justify">
                                <div class="icon-box ibox-outline ibox-center ibox-light pull-left">
                                    <div class="ibox-icon">
                                        <i class="fa fa-4x fa-shield"></i>
                                    </div>
                                </div>

                                <p class="text-justify">
                                    {!! trans('landing.landing_text_02') !!}
                                    {!! trans('landing.landing_text_03') !!}
                                    <br>
                                    {!! trans('landing.landing_text_04') !!}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-4 col-sm-4 wow fadeIn animated" data-wow-delay=".1s">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="icon-box ibox-outline ibox-center ibox-light">
                                        <div class="ibox-icon">
                                            <i class="fa fa-4x fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <p class="text-justify">{!! trans('landing.landing_text_05') !!}</p>
                                </div>
                                <div class="panel-footer bg-danger text-center">
                                    <strong>{!! trans('landing.landing_text_06') !!}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 wow fadeIn animated" data-wow-delay=".2s">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="icon-box ibox-outline ibox-center ibox-light">
                                        <div class="ibox-icon">
                                            <i class="fa fa-4x fa-ship"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <p class="text-justify">{!! trans('landing.landing_text_07') !!}</p>
                                </div>
                                <div class="panel-footer bg-danger text-center">
                                    <strong>{!! trans('landing.landing_text_08') !!}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 wow fadeIn animated" data-wow-delay=".3s">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="icon-box ibox-outline ibox-center ibox-light">
                                        <div class="ibox-icon">
                                            <i class="fa fa-4x fa-ticket"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <p class="text-justify">{!! trans('landing.landing_text_09') !!}</p>
                                </div>
                                <div class="panel-footer bg-danger text-center">
                                    <strong>{!! trans('landing.landing_text_10') !!}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-dark btn-lg" data-dismiss="modal">{!! strtoupper(trans('navigation.close')) !!}</button>
            </div>
        </div>
    </div>
@endsection
