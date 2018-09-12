<!-- Authentication Links -->
@if (Auth::guest())
    @if ('v1' === $login_version || 'v2' === $login_version)
    <div class="user-login-panel">
        {{--<a href="#" class="user-login-btn" data-toggle="modal" data-target="#loginModal"><i class="icon-profile"></i></a>--}}
        <!-- Authentication Links -->
        {!! htmlspecialchars_decode( link_to(
                trans_route($currentLocale, 'routes.login'),
                '<span class="hidden">' . trans('navigation.auth.login') . '</span>' . '<i class="icon-profile"></i>',
                ['class'=>"user-login-btn", /*'data-toggle'=>"modal", 'data-target'=>"#loginModal",*/ 'title'=>trans('navigation.auth.login')],
                []
        ))!!}
        <!-- End Authentication Links -->
    </div>
    @else
    <div class="user-login-panel">
        <!-- Authentication Links -->
        {!! htmlspecialchars_decode( link_to(
                trans_route($currentLocale, 'routes.login'),
                trans('navigation.auth.login'),
                ['class'=>"btn btn-info btn-sm", /*'data-toggle'=>"modal", 'data-target'=>"#loginModal",*/ 'title'=>trans('navigation.auth.login')],
                []
        ))!!}
                <!-- End Authentication Links -->
        {!! htmlspecialchars_decode( link_to(
                trans_route($currentLocale, 'routes.register'),
                trans('navigation.auth.register'),
                ['class'=>"btn btn-primary btn-sm", 'title'=>trans('navigation.auth.register')],
                []
        ))!!}
    </div>
    @endif
@else
    <div class="user-login-panel logged-in-user">
        <a href="#" class="user-login-btn" id="userdropdown" data-toggle="dropdown">
            <span class="user-informa">
                <span class="meta-data">{!! trans('navigation.welcome') !!}</span>
                <span class="user-name">{{ Auth::user()->username }}</span>
            </span>
            <span class="user-dd-dropper"><i class="fa fa-angle-down"></i></span>
        </a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="userdropdown">
            @if(Auth::check())
            <li><a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! !empty($customer_denomination) ? '(' . $customer_denomination . ') ' : '' !!}{!! trans('navigation.customer_area') !!}" class="lead btn btn-xs btn-info">
                <span class="hidden-xs"><strong>{!! trans('navigation.customer_area') !!}</strong></span>
                <i class="hidden-xs fa fa-dashboard"></i>
                <i class="visible-xs hidden-sm fa fa-2x fa-dashboard"></i>
            </a></li>
            @else
            <li><a href="{{ url(trans_route($currentLocale, 'routes.login')) }}" title="{!! trans('navigation.customer_area') !!}" class="lead btn btn-default accent-color">
                <span class="hidden-xs"><strong>{!! trans('navigation.customer_area') !!}</strong></span>
                <i class="hidden-xs fa fa-dashboard"></i>
                <i class="visible-xs hidden-sm fa fa-2x fa-dashboard"></i>
            </a></li>
            @endif
            {{--<li><a href="user-dashboard-saved-searches.html">Saved Searches</a></li>
            <li><a href="user-dashboard-saved-cars.html">Saved Boats</a></li>
            <li><a href="user-dashboard-manage-ads.html">Manage Ads</a></li>
            <li><a href="user-dashboard-profile.html">My Profile</a></li>
            <li><a href="user-dashboard-settings.html">Settings</a></li>--}}
            <li><a href="{{ url(trans_route($currentLocale, 'routes.logout')) }}" title="{!! trans('navigation.logout') !!}"><i class="fa fa-sign-out fa-fw"></i>{!! trans('navigation.logout') !!}</a></li>
        </ul>
    </div>
@endif