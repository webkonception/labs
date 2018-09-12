<!DOCTYPE html >
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ ENV('APP_NAME') }}</title>

    <meta name="robots" content="noindex, nofollow">
    <meta name="google-site-verification" content="ZUa1-uwrom7zzOT7iyxngZVnrEiGJe5P3E5WrnA8fxQ">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    @if (App::isLocal())
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/font-awesome/4.5.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/3.3.6/css/bootstrap.min.css') }}">
    @else
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/styles.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/boatgest-admin/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/boatgest-admin/css/boatgest-admin-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/boatgest-admin/css/boatgest-admin-theme-default.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/circular-navigation/circular-navigation.css') }}">
</head>

<body id="app-layout">
