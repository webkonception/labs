@extends('layouts.' . (preg_match('/landing/', $currentRoute) ? 'landing' : 'theme'))
<?php
    $metas_title = trans('metas.contact_us_title');
    $metas_description = trans('metas.contact_us_desc');
    $metas = [
        'metas_title' => $metas_title . ' - ' . $website_name,
        'metas_description' => $metas_description
        //,'metas_keywords' => $metas_keywords
    ];
?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection
{{--
@section('javascript')
    @if($errors->any() || Session::has('errors') || Session::has('message') || Session::has('newsletter_message') || Session::has('contact_message'))
        <script>$(document).ready(function(){$("#msgModal").modal('show');});</script>
    @endif
@endsection--}}

@section('content')
        <div class="row">
            @if(!$agent->isMobile())
            <div class="col-sm-7 col-md-6">
                @if(Session::has('contact_message'))
                    @include('theme.partials.modals.msg-modal', ['form_referrer'=>'form_contact', 'title_modal'=>trans('navigation.contact'),'message_modal'=>Session::get('contact_message.text'), 'message_type'=>Session::get('contact_message.type')])
                    {{--{!! Session::forget('contact_message') !!}--}}
                @elseif(Session::has('errors'))
                    <?php
                    $message_modal = '<ul>';
                    $message_modal .= implode('', $errors->all('<li>:message</li>'));
                    $message_modal .= '</ul>';
                    ?>
                    @include('theme.partials.modals.msg-modal', ['form_referrer'=>'form_contact', 'title_modal'=>trans('navigation.contact'),'message_modal'=>$message_modal, 'message_type'=>'error'])
                @endif

                @include('theme.partials.elements.contact-form')
            </div>
            <div class="col-sm-5 col-md-6">
                <div class="col-sm-12 well well-white">
                    <h3>{!! trans('contact.title_phone', ['website_name' => $website_name]) !!}</h3>
                    <p class="text-center lead">{!! trans('contact.desc_phone', ['number' => $website_phone]) !!}</p>
                </div>

                <div class="col-sm-12 well well-white">
                    <h3>{!! trans('contact.title_facebook') !!}</h3>
                    <a href="{!! config('youboat.' . $country_code . '.facebook') !!}" data-ga="{!! $view_name . '~' . 'facebook' . '|' . 'Ref. ' . URL::full() !!}" class="GA_event blank">
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-square-o fa-stack-2x"></i>
                          <i class="fa fa-facebook fa-stack-1x"></i>
                        </span>
                        {!! rtrim(preg_replace('/(https|http):\/\//', '', config('youboat.' . $country_code . '.facebook')), '\/') !!}
                    </a>
                    @include('theme.partials.elements.block.fb-follow-block', ['layout'=>'button_count', 'size'=>'large'])
                </div>
                <div class="col-sm-12 well well-white">
                    <h3>{!! trans('contact.title_tweet') !!}</h3>
                    <a href="{!! config('youboat.' . $country_code . '.twitter') !!}" data-ga="{!! $view_name . '~' . 'twitter' . '|' . 'Ref. ' . URL::full() !!}" class="GA_event blank">
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-square-o fa-stack-2x"></i>
                          <i class="fa fa-twitter fa-stack-1x"></i>
                        </span>
                        {!! rtrim(preg_replace('/(https|http):\/\//', '', config('youboat.' . $country_code . '.twitter')), '\/') !!}
                    </a>
                </div>
                @if($ad_banners)
                <div class="col-sm-12 hidden-xs text-center">
                    @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
                </div>
                @endif
                @if (!empty(config('youboat.'. $country_code .'.facebook')) && config('youboat.'. $country_code .'.facebook_widget'))
                <div class="col-sm-12 well well-white hidden-xs">
                    @include('theme.partials.elements.block.facebook-block', [])
                </div>
                @endif
                @if (config('youboat.'. $country_code .'.theme.helper_plugins.tweetie')  && config('youboat.'. $country_code .'.twitter_widget'))
                <div class="col-sm-12 well well-white hidden-xs">
                    @include('theme.partials.elements.block.twitter-block', ['tweets_count'=>isset($tweets_count) ? $tweets_count : 2])
                </div>
                @endif
            @endif
            </div>

            @if($agent->isMobile())
            <div class="col-sm-12 col-md-6">
                @if(Session::has('contact_message'))
                    @include('theme.partials.modals.msg-modal', ['form_referrer'=>'form_contact', 'title_modal'=>trans('navigation.contact'),'message_modal'=>Session::get('contact_message.text'), 'message_type'=>Session::get('contact_message.type')])
                    {{--{!! Session::forget('contact_message') !!}--}}
                @elseif(Session::has('errors'))
                    <?php
                    $message_modal = '<ul>';
                    $message_modal .= implode('', $errors->all('<li>:message</li>'));
                    $message_modal .= '</ul>';
                    ?>
                    @include('theme.partials.modals.msg-modal', ['form_referrer'=>'form_contact', 'title_modal'=>trans('navigation.contact'),'message_modal'=>$message_modal, 'message_type'=>'error'])
                @endif

                <div class="col-sm-12 well well-white">
                    <h3>{!! trans('contact.title_phone', ['website_name' => $website_name]) !!}</h3>
                    <p class="text-center lead">{!! trans('contact.desc_phone', ['number' => $website_phone]) !!}</p>
                </div>
                    
                <div class="spacer-10"></div>

                @include('theme.partials.elements.contact-form')
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="col-sm-12 well well-white">
                    <h3>{!! trans('contact.title_facebook') !!}</h3>
                    <a href="{!! config('youboat.' . $country_code . '.facebook') !!}" data-ga="{!! $view_name . '~' . 'facebook' . '|' . 'Ref. ' . URL::full() !!}" class="GA_event blank">
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-square-o fa-stack-2x"></i>
                          <i class="fa fa-facebook fa-stack-1x"></i>
                        </span>
                        {!! rtrim(preg_replace('/(https|http):\/\//', '', config('youboat.' . $country_code . '.facebook')), '\/') !!}
                    </a>
                    @include('theme.partials.elements.block.fb-follow-block', ['layout'=>'button_count', 'size'=>'large'])
                </div>
                <div class="col-sm-12 well well-white">
                    <h3>{!! trans('contact.title_tweet') !!}</h3>
                    <a href="{!! config('youboat.' . $country_code . '.twitter') !!}" data-ga="{!! $view_name . '~' . 'twitter' . '|' . 'Ref. ' . URL::full() !!}" class="GA_event blank">
                    <span class="fa-stack fa-lg">
                      <i class="fa fa-square-o fa-stack-2x"></i>
                      <i class="fa fa-twitter fa-stack-1x"></i>
                    </span>
                        {!! rtrim(preg_replace('/(https|http):\/\//', '', config('youboat.' . $country_code . '.twitter')), '\/') !!}
                    </a>
                </div>
                @if($ad_banners)
                    <div class="col-sm-12 hidden-xs text-center">
                        @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
                    </div>
                @endif
            </div>
            @endif

            <div class="col-sm-12">
                <a href="{{ url(trans_route($currentLocale, '/')) }}" title="{!! trans('navigation.back_to_home') !!}" class="btn btn-default pull-right">
                    <i class="fa fa-mail-reply fa-fw"></i>
                    {!! trans('navigation.back') !!}
                </a>
            </div>
        </div>
@endsection

{{--@if (Session::has('contact_message'))--}}
    {{--{!! Session::forget('contact_message') !!}--}}
{{--@endif--}}