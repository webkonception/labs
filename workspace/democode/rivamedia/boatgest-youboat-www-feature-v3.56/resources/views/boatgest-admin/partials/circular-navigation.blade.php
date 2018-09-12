<?php
    $adsRoute = config('quickadmin.route') . '.ads.index';
    $adsTypesRoute = config('quickadmin.route') . '.adstypes.index';
    $countriesRoute = config('quickadmin.route') . '.countries.index';
    $usersRoute = 'users.index';
    $rolesRoute = config('quickadmin.route') . '.roles.index';
    $usersActionsRoute = 'actions';
    $dealersRoute = config('quickadmin.route') . '.dealerscaracts.index';
    $commercialsRoute = config('quickadmin.route') . '.commercialscaracts.index';
    $customersRoute = config('quickadmin.route') . '.customerscaracts.index';

    $circularLinksAction = [];
    $circularLinks = [];

    //$circularLinksAction[] = ['action' => 'HomeController@index', 'fa-icon' => 'fa-home', 'title' => 'Home', 'param' => [], 'options' => []];
    $circularLinksAction[] = ['action' => 'DashboardController@index', 'fa-icon' => 'fa-home', 'title' => 'Dashboard', 'param' => [], 'options' => []];
    if (Auth::user()->role_id == config('quickadmin.defaultRole')) {
        //$circularLinks[] = ['route' => config('quickadmin.route') . '.dashboard', 'fa-icon' => 'fa-dashboard', 'title' => 'Login', 'param' => [], 'options' => []];
    } else {
        $circularLinksAction[] = ['action' => 'Auth\AuthController@getLogin', 'fa-icon' => 'fa-sign-in', 'title' => 'Login', 'param' => [], 'options' => []];
    }
    $circularLinksAction[] = ['action' => 'Auth\AuthController@getLogin', 'fa-icon' => 'fa-sign-in', 'title' => 'Login', 'param' => [], 'options' => []];
    $circularLinksAction[] = ['action' => 'PagesController@contact', 'fa-icon' => 'fa-envelope-o', 'title' => 'Contact', 'param' => [], 'options' => []];
    $circularLinksAction[] = ['action' => 'PagesController@about', 'fa-icon' => 'fa-info', 'title' => 'About', 'param' => [], 'options' => []];

    $circularLinks[] = ['route' => $adsRoute, 'fa-icon' => 'fa-tags', 'param' => [], 'color' => 'negative', 'title' => 'Ads', 'param' => [], 'options' => []];

    if (Auth::user()->role_id == config('quickadmin.defaultRole')) {
        $circularLinks[] = ['route' => $adsTypesRoute, 'fa-icon' => 'fa-tag', 'title' => 'Ads\'s Types', 'param' => [], 'options' => []];
        $circularLinks[] = ['route' => $countriesRoute, 'fa-icon' => 'fa-map-o', 'title' => 'Countries', 'param' => [], 'options' => []];
        $circularLinks[] = ['route' => $dealersRoute, 'fa-icon' => 'fa-anchor', 'title' => 'Dealers', 'param' => [], 'options' => []];
        $circularLinks[] = ['route' => $commercialsRoute, 'fa-icon' => 'fa-dollar', 'title' => 'Commercials', 'param' => [], 'options' => []];
        $circularLinks[] = ['route' => $customersRoute, 'fa-icon' => 'fa-life-ring', 'title' => 'Customers', 'param' => [], 'options' => []];
        $circularLinks[] = ['route' => $usersRoute, 'fa-icon' => 'fa-user-plus', 'title' => 'Users', 'param' => [], 'options' => []];
        /*if (!preg_match("/roles/i", $currentRoute)) {
            $circularLinks[] = ['route' => $rolesRoute, 'fa-icon' => 'fa-user-secret', 'param' => [], 'title' => 'Roles', 'param' => [], 'options' => []];
        }
        if ($usersActionsRoute !== $currentRoute) {
            $circularLinks[] = ['route' => $usersActionsRoute, 'fa-icon' => 'fa-history', 'param' => [], 'title' => 'User actions', 'param' => [], 'options' => []];
        }*/
    }
?>
<div class="component">
    <!-- Start Nav Structure -->
    <button class="cn-button" id="cn-button" data-default="Menu" data-active="Close">Menu</button>
    <div class="cn-wrapper" id="cn-wrapper">
        <ul>
        @foreach ($circularLinksAction as $link)
            <li class="{{ strtolower($link['title']) }}">{!! htmlspecialchars_decode( link_to_action(
                $link['action'],
                '<strong class="hidden-xs">' . $link['title'] . '</strong>' . '<span class="fa ' . $link['fa-icon'] .'"></span>',
                $link['param'],
                $link['options']
            ))!!}</li>
        @endforeach
        @foreach ($circularLinks as $link)
            <li class="{{ strtolower($link['title']) }}">{!! htmlspecialchars_decode( link_to_route(
                $link['route'],
                '<strong class="hidden-xs">' . $link['title'] . '</strong>' . '<span class="fa ' . $link['fa-icon'] .'"></span>',
                $link['param'],
                $link['options']
            ))!!}</li>
        @endforeach
        </ul>
    </div>
    <div id="cn-overlay" class="cn-overlay"></div>
    <!-- End Nav Structure -->
</div>
@section('javascript')
    <script src="{{  url('assets/vendor') }}/circular-navigation/circular-navigation.js"></script>
@endsection