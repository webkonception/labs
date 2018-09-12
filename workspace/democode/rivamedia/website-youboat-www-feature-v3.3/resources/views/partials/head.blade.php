<!DOCTYPE html >
<html lang="{{ App::getLocale() }}">

<head>
    <title>{{ !empty($pageTitle) ? $pageTitle . ' | ' . trans('metas.title') . ' | ' . trans('metas.description') : trans('metas.title') . ' | ' . trans('metas.description') }}</title>
    <meta name="description" content="{{ trans('metas.description') }}">
    <meta name="keywords" content="{{ trans('metas.keywords') }}">
    <meta name="Language" content="{{ App::getLocale() }}">
    <meta name="google-site-verification" content="{!! env('GOOGLE_SITE_VERIFICATION') !!}">

    <meta name="robots" content="noindex, nofollow">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.favicons')

    {{--<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Lato:100,300,400,700&subset=all">--}}

    @if (App::isLocal())
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/vendor/font-awesome/4.6.3/css/font-awesome.min.css' !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/vendor/bootstrap/3.3.6/css/bootstrap.min.css') !!}">
    @else
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
@endif

<link rel="stylesheet" type="text/css" href="{!! asset('assets/css/styles.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('assets/vendor/youboat/css/youboat.css') !!}">
</head>
