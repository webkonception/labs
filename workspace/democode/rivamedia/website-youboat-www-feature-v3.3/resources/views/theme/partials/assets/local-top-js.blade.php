<?php
    $scripts_js = '';
?>
@if (config('youboat.'. $country_code .'.theme.vendor.modernizr'))
{{--<script type="text/javascript" src="{!! asset(config('assets.js.modernizr.local.2_8_3.url')) !!}" defer></script>--}}
<?php
    $scripts_js .= config('assets.js.modernizr.local.2_8_3.url') . '|';
?>
@endif

@if (isset($scripts_js) && !empty($scripts_js))
    <script type="text/javascript" src="{!! asset('assets/assets.php?type=local_top-js&urls=' . mb_substr($scripts_js, 0, -1)) !!}" defer></script>
@endif