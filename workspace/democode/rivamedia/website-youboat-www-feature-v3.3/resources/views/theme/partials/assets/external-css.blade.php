<?php
    $scripts_css = '';
?>
<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.fontawesome.external.4_6_3.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.fontawesome.external.4_6_3.url') . '|';
?>
<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.bootstrap.external.3_3_6.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.bootstrap.external.3_3_6.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.bootstrap_theme.external.default.url')) !!}" media="screen">--}}
<link rel="stylesheet" type="text/css" href="{!! asset('assets/assets.php?type=bootstrap_theme-css&urls=' . config('assets.css.bootstrap_theme.external.default.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.bootstrap_theme.external.default.url') . '|';
?>
<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.animate_css.external.3_5_1.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.animate_css.external.3_5_1.url') . '|';
?>
@if (config('youboat.'. $country_code .'.theme.vendor.select2'))
<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.select2.external.4_0_2.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.select2.external.4_0_2.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.bootstrap_select'))
<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.bootstrap_select.external.1_10_0.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.bootstrap_select.external.1_10_0.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.pretty_photo'))
<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.pretty_photo.external.3_1_6.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.pretty_photo.external.3_1_6.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.owl_carousel'))
<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.owl_carousel.external.1_24.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.owl_carousel.external.1_24.url') . '|';
?>
<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.owl_carousel_theme.external.1_24.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.owl_carousel_theme.external.1_24.url') . '|';
?>
<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.owl_carousel_transitions.external.1_3_3.url')) !!}" media="screen">
<?php
    //$scripts_css .= config('assets.css.owl_carousel_transitions.external.1_3_3.url') . '|';
?>
@endif

@if (isset($scripts_css) && !empty($scripts_css))
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/assets.php?type=external-css&urls=' . mb_substr($scripts_css, 0, -1)) !!}" media="screen">
@endif