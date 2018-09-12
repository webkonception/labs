<?php
    $scripts_js = '';
?>
@if (config('youboat.'. $country_code .'.theme.tpl.cookie_legacy'))
{{--<script type="text/javascript" src="{!! asset(config('assets.js.cookie_legacy.common.default.url')) !!}" defer></script>--}}
<?php
$scripts_js .= config('assets.js.cookie_legacy.common.default.url') . '|';
?>
@endif
{{--<script type="text/javascript" src="{!! asset(config('assets.js.webfont_theme.common.default.url')) !!}" defer></script>--}}
<?php
    //$scripts_js .= config('assets.js.webfont_theme.common.default.url') . '|';
?>
{{--@if ($view_name == 'auth.register')--}}
@if (config('youboat.'. $country_code .'.theme.vendor.password_checker'))
{{--<script type="text/javascript" src="{!! asset(config('assets.js.password_checker.common.default.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.password_checker.common.default.url') . '|';
?>
{{--@endif--}}
@endif
<script type="text/javascript" src="{!! asset(config('assets.js.ui_plugins.common.default.url')) !!}" defer></script>
<?php
    //$scripts_js .= config('assets.js.ui_plugins.common.default.url') . '|';
?>
<script type="text/javascript" src="{!! asset(config('assets.js.helper_plugins.common.default.url')) !!}" defer></script>
<?php
    //$scripts_js .= config('assets.js.helper_plugins.common.default.url') . '|';
?>
{{--<script type="text/javascript" src="{!! asset(config('assets.js.blueimp_gallery.common.default.url')) !!}" defer></script>--}}
<?php
$scripts_js .= config('assets.js.blueimp_gallery.common.default.url') . '|';
?>
{{--<script type="text/javascript" src="{!! asset(config('assets.js.init_theme.common.default.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.init_theme.common.default.url') . '|';
?>

{{--<script type="text/javascript" src="{!! asset(config('assets.js.youboat.common.default.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.youboat.common.default.url') . '|';
?>
{{--<script type="text/javascript" src="{!! asset(config('assets.js.youboat_refactory.common.default.url')) !!}" defer></script>--}}
<?php
//$scripts_js .= config('assets.js.youboat_recovery.common.default.url') . '|';
?>
@if (config('youboat.'. $country_code .'.theme.vendor.google_map'))
{{--<script type="text/javascript" src="{!! asset(config('assets.js.google_map.common.default.url')) !!}" defer></script>--}}
<?php
    //@$scripts_js .= config('assets.js.google_map.common.default.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.revolution_slider'))
{{--<script type="text/javascript" src="{!! asset(config('assets.js.revolution_slider_tools.common.4_6_4.url')) !!}" defer></script>--}}
{{--<script type="text/javascript" src="{!! asset(config('assets.js.revolution_slider.common.4_6_4.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.revolution_slider_tools.common.4_6_4.url') . '|';
    $scripts_js .= config('assets.js.revolution_slider.common.4_6_4.url') . '|';
?>
@section('javascript')
<script type="text/javascript">
    jQuery(document).ready(function() {
        if(jQuery('.tp-banner').length > 0) {
            jQuery('.tp-banner').show().revolution({
                dottedOverlay: "none",
                delay: 9000,
                startwidth: 1170,
                startheight: 550,
                hideThumbs: 200,

                thumbWidth: 100,
                thumbHeight: 50,
                thumbAmount: 5,

                navigationType: "none",
                navigationArrows: "solo",
                navigationStyle: "preview2",

                touchenabled: "on",
                onHoverStop: "on",

                swipe_velocity: 0.7,
                swipe_min_touches: 1,
                swipe_max_touches: 1,
                drag_block_vertical: false,


                keyboardNavigation: "on",

                navigationHAlign: "center",
                navigationVAlign: "bottom",
                navigationHOffset: 0,
                navigationVOffset: 20,

                soloArrowLeftHalign: "left",
                soloArrowLeftValign: "center",
                soloArrowLeftHOffset: 20,
                soloArrowLeftVOffset: 0,

                soloArrowRightHalign: "right",
                soloArrowRightValign: "center",
                soloArrowRightHOffset: 20,
                soloArrowRightVOffset: 0,

                shadow: 0,
                fullWidth: "on",
                fullScreen: "off",

                spinner: "spinner0",

                stopLoop: "off",
                stopAfterLoops: -1,
                stopAtSlide: -1,

                shuffle: "off",

                autoHeight: "off",
                forceFullWidth: "off",

                hideThumbsOnMobile: "off",
                hideNavDelayOnMobile: 1500,
                hideBulletsOnMobile: "off",
                hideArrowsOnMobile: "off",
                hideThumbsUnderResolution: 0,

                hideSliderAtLimit: 0,
                hideCaptionAtLimit: 0,
                hideAllCaptionAtLilmit: 0,
                startWithSlide: 0
            });
        }
    });
</script>
@endsection
@endif

@if (isset($scripts_js) && !empty($scripts_js))
    <script type="text/javascript" src="{!! asset('assets/assets.php?type=common-js&urls=' . mb_substr($scripts_js, 0, -1)) !!}" defer></script>
@endif
<?php
    $currentRoute = Route::currentRouteName();
?>
@if(isset($currentRoute) && ('dashboard_edit_ads' == $currentRoute))
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="{!! asset('assets/vendor/jquery-ui/1.12.1/jquery-ui.min.js') !!}"></script>
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/vendor/jquery.ui.widget.min.js') !!}"></script>

    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/vendor/load-image.all.min.js') !!}"></script>

    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/vendor/canvas-to-blob.min.js') !!}"></script>

    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/jquery.iframe-transport.min.js') !!}"></script>
    <!-- The basic File Upload plugin -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/jquery.fileupload.min.js') !!}"></script>
    <!-- The File Upload processing plugin -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-process.min.js') !!}"></script>
    <!-- The File Upload image preview & resize plugin -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-image.min.js') !!}"></script>
    <!-- The File Upload audio preview plugin -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-audio.min.js') !!}"></script>
    <!-- The File Upload video preview plugin -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-video.min.js') !!}"></script>
    <!-- The File Upload validation plugin -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-validate.min.js') !!}"></script>

    <!-- The File Upload user interface plugin -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-ui.min.js') !!}"></script>

    <!-- The main application script -->
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/main.js') !!}"></script>

    <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
    <!--[if (gte IE 8)&(lt IE 10)]>
    <script src="{!! asset('assets/vendor/jquery-fileupload/js/cors/jquery.xdr-transport.min.js') !!}"></script>
    <![endif]-->
@endif