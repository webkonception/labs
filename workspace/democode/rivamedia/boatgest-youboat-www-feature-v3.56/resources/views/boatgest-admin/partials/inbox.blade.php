<?php
    $patterns = [];
    $replacements = [];

    $patterns[0] = '/index/';
    $replacements[0] = 'create';


    //$patterns[0] = '/index/';
    //$replacements[0] = 'edit';
    //$editRoute = preg_replace($patterns, $replacements, $currentRoute);

    $adsRoute = config('quickadmin.route') . '.adscaracts.index';
    $bodRoute = config('quickadmin.route') . '.bodcaracts.index';
    $adsTypesRoute = config('quickadmin.route') . '.adstypes.index';
    $countriesRoute = config('quickadmin.route') . '.countries.index';
    $usersRoute = 'users.index';
    $rolesRoute = config('quickadmin.route') . '.roles.index';
    $usersActionsRoute = 'actions';
    $dealersRoute = config('quickadmin.route') . '.dealerscaracts.index';
    $commercialsRoute = config('quickadmin.route') . '.commercialscaracts.index';
    $customersRoute = config('quickadmin.route') . '.customerscaracts.index';

    $floatingLinks = [];
    if (null !== $currentRoute && ( !preg_match('/dashboard/i', $currentRoute) || !preg_match('/statistics/i', $currentRoute))) {
        $createRoute = preg_replace($patterns, $replacements, $currentRoute);
        $floatingLinks[] = ['route' => $createRoute, 'fa-icon' => 'fa-plus', 'param' => [], 'color' => 'success', 'title' => 'Add new'];
    }

    $floatingLinks[] = ['route' => $adsRoute, 'fa-icon' => 'fa-tags', 'param' => [], 'color' => 'negative', 'title' => 'Ads\'s'];
    $floatingLinks[] = ['route' => $bodRoute, 'fa-icon' => 'fa-search-plus', 'param' => [], 'color' => 'negative', 'title' => 'BOD'];

    //if (Auth::user()->role_id == config('quickadmin.defaultRole')) {
    if($isAdmin || 'commercial' == Auth::user()->type) {
        if($isAdmin) {
            $floatingLinks[] = ['route' => $adsTypesRoute, 'fa-icon' => 'fa-tag', 'param' => [], 'color' => 'negative', 'title' => 'Ads\'s Types'];
        }
        if($isAdmin) {
            $floatingLinks[] = ['route' => $countriesRoute, 'fa-icon' => 'fa-map-o', 'param' => [], 'color' => 'negative', 'title' => 'Countries'];
        }
        $floatingLinks[] = ['route' => $dealersRoute, 'fa-icon' => 'fa-anchor', 'param' => [], 'color' => 'negative', 'title' => 'Dealers'];
        $floatingLinks[] = ['route' => $commercialsRoute, 'fa-icon' => 'fa-dollar', 'param' => [], 'color' => 'negative', 'title' => 'Commercials'];
        $floatingLinks[] = ['route' => $customersRoute, 'fa-icon' => 'fa-life-ring', 'param' => [], 'color' => 'negative', 'title' => 'Customers'];
        if($isAdmin) {
            $floatingLinks[] = ['route' => $usersRoute, 'fa-icon' => 'fa-user-plus', 'param' => [], 'color' => 'primary', 'title' => 'Users'];
        }
        /*if (!preg_match("/roles/i", $currentRoute)) {
            $floatingLinks[] = ['route' => $rolesRoute, 'fa-icon' => 'fa-user-secret', 'param' => [], 'color' => 'info', 'title' => 'Roles'];
        }
        if ($usersActionsRoute !== $currentRoute) {
            $floatingLinks[] = ['route' => $usersActionsRoute, 'fa-icon' => 'fa-history', 'param' => [], 'color' => 'warning', 'title' => 'User actions'];
        }*/
    }
?>

@if (count($floatingLinks) > 0)
<div id="inbox">
    <div class="fab btn-group show-on-hover dropup">
        <div data-toggle="tooltip" data-placement="left" title="Tools">
            <button type="button" class="btn btn-danger btn-io dropdown-toggle" data-toggle="dropdown">
                <span class="fa-stack fa-2x">
                    <i class="fa fa-circle fa-stack-2x fab-backdrop"></i>
                    <i class="fa fa-plus fa-stack-1x fa-inverse fab-primary"></i>
                    <i class="fa fa-briefcase fa-stack-1x fa-inverse fab-secondary"></i>
                </span>
            </button>
        </div>
        <ul class="dropdown-menu dropdown-menu-right" role="menu">
        @foreach ($floatingLinks as $link)
            <li>{!!
                htmlspecialchars_decode(
                    link_to_route(
                        $link['route'],
                        '<i class="fa ' . $link['fa-icon'] .' fa-fw"></i>',
                        $link['param'],
                        [
                            'class' => 'btn btn-' . $link['color'],
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'left',
                            'title' => $link['title']
                        ]
                    )
                )
            !!}</li>
        @endforeach
        </ul>
    </div>
</div>
@endif