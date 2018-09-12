<?php
    $scripts_css = '';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.fontawesome.local.4_6_3.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.fontawesome.local.4_6_3.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.bootstrap.local.3_3_6.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.bootstrap.local.3_3_6.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.bootstrap_theme.local.default.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.bootstrap_theme.local.default.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.animate_css.local.3_5_1.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.animate_css.local.3_5_1.url') . '|';
?>
@if (config('youboat.'. $country_code .'.theme.vendor.select2'))
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.select2.local.4_0_2.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.select2.local.4_0_2.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.pretty_photo'))
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.pretty_photo.local.3_1_6.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.pretty_photo.local.3_1_6.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.bootstrap_select'))
    {{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.bootstrap_select.local.1_10_0.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.bootstrap_select.local.1_10_0.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.owl_carousel'))
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.owl_carousel.local.1_24.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.owl_carousel.local.1_24.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.owl_carousel_theme.local.1_24.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.owl_carousel_theme.local.1_24.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.owl_carousel_transitions.local.1_3_3.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.owl_carousel_transitions.local.1_3_3.url') . '|';
?>
@endif

@if (isset($scripts_css) && !empty($scripts_css))
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/assets.php?type=local-css&urls=' . mb_substr($scripts_css, 0, -1)) !!}" media="screen">
@endif