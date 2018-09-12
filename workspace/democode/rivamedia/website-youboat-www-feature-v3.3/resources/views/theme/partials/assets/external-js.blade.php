<script type="text/javascript" src="{!! asset(config('assets.js.jquery.external.2_2_1.url')) !!}" defer></script>
{{--<script type="text/javascript" src="{!! asset(config('assets.js.fontawesome.external.4_6_3.url')) !!}" async></script>--}}
<script type="text/javascript" src="{!! asset(config('assets.js.bootstrap.external.3_3_6.url')) !!}" defer></script>

{{-- UI Plugins --}}
@if (config('youboat.'. $country_code .'.theme.ui_plugins.fitvids'))
<script type="text/javascript" src="{!! asset(config('assets.js.fitvids.external.1_0_0.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.hoverintent') && config('youboat.'. $country_code .'.theme.ui_plugins.superfish'))
<script type="text/javascript" src="{!! asset(config('assets.js.hoverintent.external.2013_03_11.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.ui_plugins.superfish'))
<script type="text/javascript" src="{!! asset(config('assets.js.superfish.external.1_7_4.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.ui_plugins.scroll_to'))
<script type="text/javascript" src="{!! asset(config('assets.js.scroll_to.external.1_4_3.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.ui_plugins.bootstrap_select'))
<script type="text/javascript" src="{!! asset(config('assets.js.bootstrap_select.external.1_6_0.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.ui_plugins.bootstrap_timepicker'))
<script type="text/javascript" src="{!! asset(config('assets.js.bootstrap_timepicker.external.0_5_2.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.ui_plugins.bootstrap_datepicker'))
<script type="text/javascript" src="{!! asset(config('assets.js.bootstrap_datepicker.external.1_6_1.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.vendor.bootstrap_datetimepicker'))
<script type="text/javascript" src="{!! asset(config('assets.js.moment_with_locales.external.2_13_0.url')) !!}" defer></script>
<script type="text/javascript" src="{!! asset(config('assets.js.bootstrap_datetimepicker.external.4_17_37.url')) !!}" defer></script>
@endif

{{-- Helper Plugins --}}
@if (config('youboat.'. $country_code .'.theme.helper_plugins.sticky'))
    <script type="text/javascript" src="{!! asset(config('assets.js.sticky.external.1_0_0.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.isotope'))
<script type="text/javascript" src="{!! asset(config('assets.js.imagesloaded.external.2_1_1.url')) !!}" defer></script>
<script type="text/javascript" src="{!! asset(config('assets.js.isotope.external.1_5_25.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.touch_swipe'))
    <script type="text/javascript" src="{!! asset(config('assets.js.touch_swipe.external.1_3_3.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.throttle_debounce'))
    <script type="text/javascript" src="{!! asset(config('assets.js.throttle_debounce.external.1_1.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.localscroll'))
    <script type="text/javascript" src="{!! asset(config('assets.js.localscroll.external.1_2_8.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.easing'))
<script type="text/javascript" src="{!! asset(config('assets.js.easing.external.1_3.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.scroll_to'))
    <script type="text/javascript" src="{!! asset(config('assets.js.scroll_to.external.1_4_3.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.cookie'))
    <script type="text/javascript" src="{!! asset(config('assets.js.cookie.external.2_1_1.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.swipe'))
    <script type="text/javascript" src="{!! asset(config('assets.js.swipe.external.2_0_0.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.appear'))
    <script type="text/javascript" src="{!! asset(config('assets.js.appear.external.0_3_3.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.parallax'))
    <script type="text/javascript" src="{!! asset(config('assets.js.parallax.external.1_1_3.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.retina'))
    <script type="text/javascript" src="{!! asset(config('assets.js.retina.external.1_1_0.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.transit'))
    <script type="text/javascript" src="{!! asset(config('assets.js.transit.external.0_9_12.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.helper_plugins.mousewheel'))
    <script type="text/javascript" src="{!! asset(config('assets.js.mousewheel.external.3_0_6.url')) !!}" defer></script>
@endif

@if (config('youboat.'. $country_code .'.theme.vendor.pretty_photo') && isset($view_name) && !preg_match('/sell/', $view_name))
<script type="text/javascript" src="{!! asset(config('assets.js.pretty_photo.external.3_1_6.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.owl_carousel') && isset($view_name) && !preg_match('/sell/', $view_name))
<script type="text/javascript" src="{!! asset(config('assets.js.owl_carousel.external.1_3_3.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.waypoints'))
<script type="text/javascript" src="{!! asset(config('assets.js.waypoints.external.4_0_0.url')) !!}" defer></script>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.flex_slider') && isset($view_name) && !preg_match('/sell/', $view_name))
<script type="text/javascript" src="{!! asset(config('assets.js.flex_slider.external.2_6_1.url')) !!}" defer></script>
@endif

@if (config('youboat.'. $country_code .'.vendor.select2'))
    <script type="text/javascript" src="{!! asset(config('assets.js.select2.external.4_0_2.url')) !!}" defer></script>
@endif