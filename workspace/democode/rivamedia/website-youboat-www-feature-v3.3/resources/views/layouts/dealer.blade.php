<?php
$header_version = config('youboat.'. $country_code .'.theme.tpl.header_version')[0] ?: '';
$title_page = !empty($pageTitle) ? $pageTitle : config('youboat.' . $country_code . '.default_metas_title');
?>

@include('theme.partials.head')

<body class="{{ $view_name }} {{ $header_version }}">

@if (app()->isLocal())
<!-- {!! str_random(8) !!}-->
@endif

    @include('theme.partials.analyticstracking', ['website_name'=>$website_name, 'custom_dimensions'=>!empty($custom_dimensions) ? $custom_dimensions : '', 'title_page'=> strip_tags($title_page), 'view_name'=>$view_name, 'ua'=>config('youboat.' . $country_code . '.ua')])

    <!--[if lt IE 7]>
    <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
    <![endif]-->
    <div class="overlay"></div>

    @if (config('youboat.'. $country_code .'.theme.tpl.cookie_legacy'))
    @include('partials.cookie_legacy')
@endif

    <div class="body{!! $agent->isMobile() ? ' mobile' : '' !!}{!! $agent->isTablet() ? ' tablet' : '' !!}">

        @include('theme.partials.header')

    @if ('homepage' === $view_name || 'home' === $view_name)
        @include('theme.partials.elements.hero-area')
    @else
        <div class="page-header">
            <div class="container">
                <h1 class="page-title">@yield('title_page', trans('navigation.' . $view_name))</h1>
            </div>
        </div>
    @endif

    <div class="main" role="main">

        <div id="content" class="content full">

            <div class="container">
                @yield('content')
                @if(
                    'homepage' != $view_name &&
                    'home' != $view_name &&
                    'boat_on_demand' != $view_name &&
                    'for_sale' != $view_name &&
                    'contact' != $view_name
                    &&
                    $ad_banners && $agent->isMobile()
                )
                    <div class="advertising">
                        <div class="row">
                            <div class="col-sm-12 hidden-xs text-center">
                                <hr>
                                @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90'])
                            </div>
                            <div class="col-sm-12 visible-xs text-center">
                                <hr>
                                @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        @include('theme.partials.footer')

    </div>

</div>

@include('theme.partials.javascripts')

@if(Session::has('message'))
    @include('theme.partials.modals.msg-modal', ['form_referrer'=>Session::get('message.referrer'),'title_modal'=>Session::get('message.title'),'message_modal'=>Session::get('message.text'), 'message_type'=>Session::get('message.type')])
@endif

@if(!empty($errors) && $errors->any() || Session::has('errors'))
    <script>
        $(document).ready(function(){$("#msgModalError").modal('show');});
    </script>
@endif
@if(
    //(!empty($errors) && $errors->any())
    //||
    //Session::has('errors')
    //||
    Session::has('message')
    || Session::has('search_notification_message')
    || Session::has('newsletter_message')
    || Session::has('contact_message')
    || Session::has('bod_message')
    || Session::has('dashboard_message')
    || Session::has('customer_message')
    || Session::has('register_message')
    || Session::has('status')
    || Session::has('enquiry_message')
)
    <script>$(document).ready(function(){$("#msgModal{!! !empty(Session::get('message.referrer')) ? '_' . Session::get('message.referrer') : '' !!}").modal('show');});</script>
@endif

@yield('javascript')
</body>
</html>
@if (Session::has('message'))
    {!! Session::forget('message') !!}
@endif

@if (Session::has('search_notification_message'))
    {!! Session::forget('search_notification_message') !!}
@endif

@if (Session::has('newsletter_message'))
    {!! Session::forget('newsletter_message') !!}
@endif

@if (Session::has('contact_message'))
    {!! Session::forget('contact_message') !!}
@endif

@if (Session::has('bod_message'))
    {!! Session::forget('bod_message') !!}
@endif

@if (Session::has('dashboard_message'))
    {!! Session::forget('dashboard_message') !!}
@endif

@if (Session::has('customer_message'))
    {!! Session::forget('customer_message') !!}
@endif

@if (Session::has('enquiry_message'))
    {!! Session::forget('enquiry_message') !!}
@endif

@if (Session::has('register_message'))
    {!! Session::forget('enquiry_message') !!}
@endif

@if (Session::has('status'))
    {!! Session::forget('status') !!}
@endif
