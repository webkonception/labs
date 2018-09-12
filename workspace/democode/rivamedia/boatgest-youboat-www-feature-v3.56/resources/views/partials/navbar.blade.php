<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <?php
                //$imgLogo = Html::image('assets/img/picto-boat.jpg', env('APP_NAME'), ['class' => 'logo logo visible-lg-inline', 'width' => 50]);
                //$imgLogo = app('html')->image('assets/img/picto-boat.jpg', env('APP_NAME'), ['class' => 'logo logo visible-lg-inline', 'width' => '50']);
                //$imgLogo = image('assets/img/picto-boat.jpg', env('APP_NAME'), ['class' => 'logo', 'width' => '50']);
            ?>
            {!! htmlspecialchars_decode(link_to(
                url(config('quickadmin.homeRoute')),
                //$imgLogo . env('APP_NAME'),
                '<i class="fa fa-ship fa-2x fa-fw"></i>',
                ['class' => 'navbar-brand']
            )) !!}
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <?php
                $navbarLinks = [];
                //$navbarLinks[] = ['route' => 'HomeController@index', 'fa-icon' => 'fa-home', 'title' => 'Home', 'param' => [], 'options' => []];
                //$navbarLinks[] = ['route' => 'PagesController@contact', 'fa-icon' => 'fa-envelope-o', 'title' => 'Contact', 'param' => [], 'options' => []];
                $navbarLinks[] = ['route' => 'ContactController@create', 'fa-icon' => 'fa-envelope-o', 'title' => 'Contact', 'param' => [], 'options' => []];
                //$navbarLinks[] = ['route' => 'PagesController@about', 'fa-icon' => 'fa-info', 'title' => 'About', 'param' => [], 'options' => []];
                //$navbarLinks[] = ['route' => 'Auth\AuthController@getLogin', 'fa-icon' => 'fa-sign-in', 'title' => 'Login', 'param' => [], 'options' => []];
            ?>
            <ul class="nav navbar-nav">
                @foreach ($navbarLinks as $link)
                <li {!! preg_match('/' . $link['route'] .'/', $currentRouteAction) ? 'class="active"' : '' !!}>{!! htmlspecialchars_decode( link_to_action(
                $link['route'],
                $link['title'] . '<i class="fa ' . $link['fa-icon'] .' fa-fw"></i>',
                $link['param'],
                $link['options']
                ))!!}</li>
                @endforeach
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li {!! preg_match('/AuthController@getLogin/', $currentRouteAction) ? 'class="active"' : '' !!}>
                        {!! htmlspecialchars_decode( link_to_action(
                            'Auth\AuthController@getLogin',
                            'Login' . '<i class="fa fa-sign-in fa-fw"></i>',
                            [],
                            []
                        ))!!}
                        {{--{!! htmlspecialchars_decode( link_to(
                            'login',
                            'Login' . '<i class="fa fa-sign-in fa-fw"></i>',
                            [],
                            []
                        ))!!}--}}
                    </li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->username }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url(config('quickadmin.homeRoute')) }}"><i class="fa fa-btn fa-dashboard fa-fw"></i>Dashboard</a></li>
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out fa-fw"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
