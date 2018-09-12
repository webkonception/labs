<?php
    $currentLocale = isset($currentLocale) ? $currentLocale : LaravelLocalization::getCurrentLocale();
?>
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" class="no-js">

<head>
    <title>{!! $website_name !!} | {!! config('youboat.' . $country_code . '.default_metas_title') !!}</title>
    <meta name="description" content="{!! config('youboat.' . $country_code . '.default_metas_description') !!}">
    <meta name="keywords" content="{!! config('youboat.' . $country_code . '.default_metas_keywords') !!}">
    <meta name="author" content="{!! config('youboat.' . $country_code . '.website_name') !!}">
    <meta name="google-site-verification" content="{!! env('GOOGLE_SITE_VERIFICATION') !!}">

    <meta name="robots" content="noindex, nofollow">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta http-equiv="Content-type" content="text/html; charset=utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Lato:100,300,400,700&subset=all">--}}

    @if (App::isLocal())
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/vendor/font-awesome/4.6.3/css/font-awesome.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/vendor/bootstrap/3.3.6/css/bootstrap.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/vendor/vegas/2.2.0/vegas.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/vendor/animate.css/3.5.1/css/animate.min.css') !!}">
    @else
        <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/vegas/2.2.0/vegas.min.css">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">
    @endif

    <link rel="stylesheet" type="text/css" href="{!! asset('assets/vendor/youboat/css/youboat.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/vendor/youboat/landing/css/'. $country_code .'/landing.min.css') !!}">
</head>
