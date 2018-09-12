@if(!$agent->isMobile() )
<div class="hero-area hidden-xs hidden-sm">
    @if (!config('youboat.'. $country_code .'.theme.vendor.revolution_slider') && config('youboat.'. $country_code .'.theme.tpl.hero_area.search_over_slider'))
        @include('theme.partials.elements.search.search-over-slider')
    @endif

    @if (!$agent->isMobile() && config('youboat.'. $country_code .'.theme.vendor.flex_slider') && config('youboat.'. $country_code .'.theme.tpl.hero_area.flex_slider'))
        @include('theme.partials.elements.hero-area.flexslider')
    @endif

    @if (config('youboat.'. $country_code .'.theme.vendor.revolution_slider') && config('youboat.'. $country_code .'.theme.tpl.hero_area.revolution_slider'))
        @include('theme.partials.elements.hero-area.revolution-slider')
    @endif

    @if (config('youboat.'. $country_code .'.theme.vendor.owl_carousel') && config('youboat.'. $country_code .'.theme.tpl.hero_area.owl_carousel'))
        @include('theme.partials.elements.hero-area.owl-carousel')
    @endif
</div>
@else
    @include('theme.partials.elements.search.search-over-slider')
@endif