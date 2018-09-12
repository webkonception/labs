<?php
    $currentControllerAction = str_replace(array(config('quickadmin.homeRoute') . '.', '.'), array('', '_'), $currentRoute);
    if (preg_match('/_/', $currentControllerAction)) {
        $currentAction = preg_split('/_/', $currentControllerAction)[1] ? preg_split('/_/', $currentControllerAction)[1] : '';
        $currentController = preg_split('/_/', $currentControllerAction)[0] ? preg_split('/_/', $currentControllerAction)[0] : '';
    }
    $currentLocale = isset($currentLocale) ? $currentLocale : LaravelLocalization::getCurrentLocale();
?>
<!DOCTYPE html >
<html lang="{{ $currentLocale }}">

<head>
    <meta charset="utf-8">
    <title>{{ ENV('APP_NAME') }}</title>

    <meta name="robots" content="noindex, nofollow">
    <meta name="google-site-verification" content="co-0nUX7hOdGo_l1XTWziQYNTufz_IycUG2QMQY7rEU">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include(config('quickadmin.route') . '.partials.favicons')

    @include(config('quickadmin.route') . '.partials.css')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    {!! ConsoleTVs\Charts\Facades\Charts::assets() !!}
</head>

<body class="page-header-fixed {{ isset($currentController) ? $currentController : '' }} {{ isset($currentControllerAction) ? $currentControllerAction : '' }}">