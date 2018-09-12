<div class="site-header-wrapper">
    {{--@if (isset($view_name)
    //&& 'homepage' != $view_name
    && $ad_banners)
    <div class="row">
        <div class="col-sm-12 hidden-xs text-center">
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90'])
        </div>
    </div>
    @endif--}}
    @include('theme.partials.header.site-' . config('youboat.'. $country_code .'.theme.tpl.header_version')[0] ?: 'header-v1')
</div>