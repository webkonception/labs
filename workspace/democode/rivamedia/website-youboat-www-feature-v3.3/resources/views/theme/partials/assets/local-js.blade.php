<?php
    $scripts_js = '';
?>
{{--<script type="text/javascript" src="{!! asset(config('assets.js.jquery.local.2_2_1.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.jquery.local.2_2_1.url') . '|';
?>
{{--<script type="text/javascript" src="{!! asset(config('assets.js.bootstrap.local.3_3_6.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.bootstrap.local.3_3_6.url') . '|';
?>
@if (isset($scripts_js) && !empty($scripts_js))
    <script type="text/javascript" src="{!! asset('assets/assets.php?type=local_jquery_bs-js&urls=' . mb_substr($scripts_js, 0, -1))  !!}"></script>
@endif

<?php
    $scripts_js = '';
?>
@if (config('youboat.'. $country_code .'.vendor.select2'))
{{--<script type="text/javascript" src="{!! asset(config('assets.js.select2.local.4_0_2.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.select2.local.4_0_2.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.pretty_photo') && isset($view_name) && !preg_match('/sell/', $view_name))
    {{--<script type="text/javascript" src="{!! asset(config('assets.js.pretty_photo.local.3_1_6.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.pretty_photo.local.3_1_6.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.owl_carousel') && isset($view_name) && !preg_match('/sell/', $view_name))
    {{--<script type="text/javascript" src="{!! asset(config('assets.js.owl_carousel.local.1_3_3.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.owl_carousel.local.1_3_3.url') . '|';
?>
@endif
@if (config('youboat.'. $country_code .'.theme.vendor.flex_slider') && isset($view_name) && !preg_match('/sell/', $view_name))
    {{--<script type="text/javascript" src="{!! asset(config('assets.js.flex_slider.local.2_6_1.url')) !!}" defer></script>--}}
    <?php
    $scripts_js .= config('assets.js.flex_slider.local.2_6_1.url') . '|';
    ?>
@endif

@if (isset($scripts_js) && !empty($scripts_js))
    <script type="text/javascript" src="{!! asset('assets/assets.php?compress=false&type=local_plugins_cf-js&urls=' . mb_substr($scripts_js, 0, -1)) !!}" defer></script>
@endif