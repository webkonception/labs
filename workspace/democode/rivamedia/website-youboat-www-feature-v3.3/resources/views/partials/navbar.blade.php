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
                //$imgLogo = Html::image('assets/img/picto-boat.jpg', config('youboat.' . $country_code . '.website_name'), ['class' => 'logo logo visible-lg-inline', 'width' => 50]);
                //$imgLogo = app('html')->image('assets/img/picto-boat.jpg', config('youboat.' . $country_code . '.website_name'), ['class' => 'logo logo visible-lg-inline', 'width' => '50']);
                //$imgLogo = image('assets/img/picto-boat.jpg', config('youboat.' . $country_code . '.website_name'), ['class' => 'logo', 'width' => '50']);
            ?>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <?php
                $navbarLinks = [];
                $navbarLinks[] = ['route' => 'home', 'fa-icon' => 'fa-home', 'title' => 'homepage', 'param' => [], 'options' => []];
                $navbarLinks[] = ['route' => 'contact', 'fa-icon' => 'fa-envelope-o', 'title' => 'contact', 'param' => [], 'options' => []];
                $navbarLinks[] = ['route' => 'about', 'fa-icon' => 'fa-info', 'title' => 'about', 'param' => [], 'options' => []];
                $navbarLinks[] = ['route' => 'welcome', 'fa-icon' => 'fa-user', 'title' => 'welcome', 'param' => [], 'options' => []];
            ?>
            <ul class="nav navbar-nav">
                @foreach ($navbarLinks as $link)
                <?php
                    $title  = htmlspecialchars_decode(title_case(trans('navigation.'.  $link['route'])) . '<i class="fa ' . $link['fa-icon'] .' fa-fw"></i>');
                    $url    = url(trans_route($currentLocale, 'routes.' . $link['route']));
                ?>
                    <li {!! ($link['route'] == $currentRoute) ? 'class="active"' : '' !!}>
                        <a href="{{ $url }}" title="{!! title_case(trans('navigation.'.  $link['route'])) !!}">
                            {!! $title !!}
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Right Side Of Navbar -->
            @include('partials.langswitch')
        </div>
    </div>
</nav>
