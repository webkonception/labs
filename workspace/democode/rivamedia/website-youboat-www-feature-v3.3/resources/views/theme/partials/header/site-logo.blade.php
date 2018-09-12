@if (!$agent->isMobile())
<div class="col-xs-4 col-sm-3 logo">
    <a href="{!! LaravelLocalization::localizeURL('/') !!}"><img src="{!! asset('assets/theme/' . $country_code . '/img/logo.png') !!}" alt="{!! $website_name !!} / {!! config('youboat.' . $country_code . '.tagline') !!}"></a>
</div>
@else
<div class="logo">
    <a href="{!! LaravelLocalization::localizeURL('/') !!}"><img src="{!! asset('assets/theme/' . $country_code . '/img/logo.png') !!}" alt="{!! $website_name !!} / {!! config('youboat.' . $country_code . '.tagline') !!}"></a>
</div>
@endif