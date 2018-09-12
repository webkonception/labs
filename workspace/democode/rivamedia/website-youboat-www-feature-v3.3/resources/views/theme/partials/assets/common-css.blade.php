<?php
    $protocole = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
    $scripts_css = '';
    $country_code = mb_strtolower($country_code);
    $theme_color = config('app.theme_color')[$country_code];
?>
{{--<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:400,700,300|Roboto+Condensed:400,700|Playfair+Display:400,400italic,700">--}}
<?php
    //$scripts_css .= $protocole . ':' . '//fonts.googleapis.com/css?family=Roboto:400,700,300|Roboto+Condensed:400,700|Playfair+Display:400,400italic,700' . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.theme.common.theme.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.theme.common.theme.url') . '|';
?>
@if (isset($scripts_css) && !empty($scripts_css))
<?php
    $scripts_css = str_replace('%country_code%', $country_code, $scripts_css);
    $scripts_css = str_replace('%theme_color%', $theme_color, $scripts_css);
?>
<link rel="stylesheet" type="text/css" href="{!! asset('assets/assets.php?type=common_01-css&urls=' . mb_substr($scripts_css, 0, -1)) !!}" media="screen">
@endif
<?php
    $scripts_css = '';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.material_switch.common.default.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.material_switch.common.default.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.cookie_legacy.common.default.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.cookie_legacy.common.default.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.youboat.common.default.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.youboat.common.default.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.outlined_iconset.common.default.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.outlined_iconset.common.default.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.theme.common.styles.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.theme.common.styles.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.theme_color.common.default.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.theme_color.common.default.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.blueimp_gallery.common.default.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.blueimp_gallery.common.default.url') . '|';
?>
{{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.theme.common.override.url')) !!}">--}}
<?php
    $scripts_css .= config('assets.css.theme.common.override.url') . '|';
?>
<!--[if lte IE 9]><link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.theme.common.ie.url')) !!}" media="screen" /><![endif]-->
<?php
    //$scripts_css .= config('assets.css.theme.common.ie.url') . '|';
?>
@if (config('youboat.'. $country_code .'.theme.vendor.revolution_slider'))
    {{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.revolution_slider_extralayers.common.default.url')) !!} media="screen">--}}
    <?php
    $scripts_css .= config('assets.css.revolution_slider_extralayers.common.default.url') . '|';
    ?>
    {{--<link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.revolution_slider_settings.common.default.url')) !!} media="screen">--}}
    <?php
    $scripts_css .= config('assets.css.revolution_slider_settings.common.default.url') . '|';
    ?>
    <link rel="stylesheet" type="text/css" href="{!! asset(config('assets.css.revolution_slider_fonts.common.default.url')) !!}">
    <?php
    //$scripts_css .= config('assets.css.revolution_slider_fonts.common.default.url') . '|';
    ?>
@endif

@if (isset($scripts_css) && !empty($scripts_css))
<?php
$scripts_css = str_replace('%country_code%', $country_code, $scripts_css);
$scripts_css = str_replace('%theme_color%', $theme_color, $scripts_css);
?>
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/assets.php?type=common_02-css&urls=' . mb_substr($scripts_css, 0, -1)) !!}" media="screen">
@endif

<?php
    $currentRoute = Route::currentRouteName();
?>
@if(isset($currentRoute) && ('dashboard_edit_ads' == $currentRoute))
    @if (App::isLocal())
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/jquery-fileupload/css/jquery.fileupload.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/jquery-fileupload/css/jquery.fileupload-ui.min.css') }}">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/jquery-fileupload/css/jquery.fileupload-noscript.min.css') }}"></noscript>
    <noscript><link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/jquery-fileupload/css/jquery.fileupload-ui-noscript.css') }}"></noscript>
    @else
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.17.0/css/jquery.fileupload.min.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.17.0/css/jquery.fileupload-ui.min.css">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.17.0/css/jquery.fileupload-noscript.min.css"></noscript>
    <noscript><link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.17.0/css/jquery.fileupload-ui-noscript.min.css"></noscript>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    @endif
@endif