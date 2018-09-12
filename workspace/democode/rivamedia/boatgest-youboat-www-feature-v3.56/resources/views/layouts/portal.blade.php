<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="google-site-verification" content="ZUa1-uwrom7zzOT7iyxngZVnrEiGJe5P3E5WrnA8fxQ">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoatGest {!! mb_strtoupper($country_code) !!} | {!! ucfirst(trans('boatgest.title_page')) !!}</title>
    <link href="/portal/css/bootstrap.min.css" rel="stylesheet">
    <link href="/portal/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/portal/css/animate.css" rel="stylesheet">
    <link href="/portal/css/style.css" rel="stylesheet">
    <link href="/portal/css/mine.css" rel="stylesheet">
    <style>
        .fullscreen-bg {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
            z-index: -100;
        }
        .fullscreen-bg__video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

        }
        .opacity20 {
            background:rgba(243,244,244,0.8) !important;width:100%;padding:0
        }
        .mytitleLogin {font-size:120px;}
        .loginColumns h3 {line-height:20px}
        .headLogin, .headLogin h1 {color:#FFF !important;margin-top:0 !important}

        @media (max-width: 742px) {
            .headLogin, .headLogin h1 {color:#0A4873 !important;background-color:#F3F4F4}
            .headLogin {
                margin-top: -20px !important;
            }
            .fullscreen-bg {
                background: url('/portal/img/pixel.png') center center / cover repeat-all;
            }
            .opacity20 {background-color:#F3F4F4 !important}

            .fullscreen-bg__video {
                display: none;
            }
            .mytitleLogin {font-size:70px;margin-left:-10px}
            h2 {
                font-size: 19px;
            }
            .ibox-content {
                background-color:rgba(255,255,255,0.8);
            }
        }
        .other_countries {
            -webkit-border-radius: 0;
            -moz-border-radius: 0;
            -o-border-radius: 0;
            border-radius: 0;

        }
        .other_countries .french_customer {
            display: inline-block;
            padding-bottom: .5em;
            font-weight:normal;
        }
        .other_countries .btn-primary {
            background-color: #006ab3;
            border-color: #006ab3;
        }
    </style>
</head>

<body class="">

<!-- header -->
<div class="fullscreen-bg">
    <video muted loop autoplay width="100%" height="100%" poster="/portal/img/pixel.png" class="fullscreen-bg__video">
        <source src="/portal/video/boat.webm" type="video/webm" />
        <source src="/portal/video/boat.mp4" type="video/mp4" />
        <source src="/portal/video/boat.ogv" type="video/ogg" />
    </video>
</div>
<div class="container-full other_countries lead alert alert-info">
    <div class="row ">
        <div class="col-sm-offset-2 col-sm-8 text-center">
            <strong class="french_customer">{!! trans('boatgest.french_customer') !!}</strong>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="https://fr.boatgest.com" title="{!! trans('boatgest.french_access_link') !!}" class="btn btn-lg btn-primary">{!! trans('boatgest.french_access_link') !!} <img src="/assets/img/flags/FR.png" /></a>
        </div>
    </div>
</div>
<div class="loginColumns animated fadeInDown headLogin">
    <h1 class="logo-name text-center mytitleLogin">BoatGest <img src="/assets/img/flags/{!! mb_strtoupper($country_code) !!}.png" /></h1>
    <h3 class="text-center">{!! ucfirst(trans('boatgest.header_chapo')) !!}</h3>
</div>
<!-- /header -->

@yield('content')

@yield('javascript')
</body>
</html>
