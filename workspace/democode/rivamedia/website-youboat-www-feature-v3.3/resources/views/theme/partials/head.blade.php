<?php
    $currentLocale = isset($currentLocale) ? $currentLocale : LaravelLocalization::getCurrentLocale();
?>
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" class="no-js">
<head>

    @include('theme.partials.metas')

    @if (preg_match('/dashboard/', $view_name) || preg_match('/boat_on_demand/', $view_name))
    <meta http-equiv="Cache-Control" content="no-cache, no-store, max-age=0, private, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    @endif

    <meta name="author" content="{!! $website_name !!}">
    <meta name="google-site-verification" content="{!! env('GOOGLE_SITE_VERIFICATION') !!}">
<?php
    if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) || preg_match('/\?/', $_SERVER['REQUEST_URI'])) {
        echo '<meta name="robots" content="noindex, follow">';
    }
    if(null !== config('youboat.' . $country_code . '.robots_index') && !empty(config('youboat.' . $country_code . '.robots_index'))) {
        echo '<meta name="robots" content="' . config('youboat.' . $country_code . '.robots_index') . '">';
    }
    /*if (isset($ads_list)) {
        $current_page = !empty($ads_list) && array_key_exists('ads_list', $ads_list) ? $ads_list['ads_list']->currentPage() : '';
        if(isset($current_page) && !empty($current_page) && $current_page > 1) {
            echo '<meta name="robots" content="noindex, follow">';
        }
    }*/
?>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">

    <meta http-equiv="Content-type" content="text/html; charset=utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.favicons')

    @if (App::isLocal())
    @include('theme.partials.assets.local-css')
    @else
    @include('theme.partials.assets.external-css')
    @endif

    @include('theme.partials.assets.common-css')

    @if (App::isLocal())
    @include('theme.partials.assets.local-top-js')
    @else
    @include('theme.partials.assets.external-top-js')
    @endif

</head>