<div class="utility-bar hidden-xs">
@if ('homepage' === $view_name || 'home' === $view_name)
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-8">
                <div class="toggle-make">
                    <a href="#"><i class="fa fa-navicon"></i></a>
                    <span>{!! trans('navigation.browse_by_type') !!}</span>
                </div>
            </div>
            <div class="col-md-8 col-sm-6 col-xs-4">
                <ul class="utility-icons social-icons social-icons-colored">
                    <li class="facebook"><a href="{!! config('youboat.' . $country_code . '.facebook') !!}" class="blank"><i class="fa fa-facebook"></i></a></li>
                    <li class="twitter"><a href="{!! config('youboat.' . $country_code . '.twitter') !!}" class="blank"><i class="fa fa-twitter"></i></a></li>
                    {{--<li class="googleplus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                    <li class="linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>--}}
                </ul>
            </div>
        </div>
    </div>
<?php
    $urlPrefix = 'for-sale';
    $urlPrefix = url(app('laravellocalization')->localizeURL($urlPrefix));
    //$array = Navigation::getAdsTypesList('active', true);
    $array = Navigation::getAdsTypesList();
    //$adstypeslist = Navigation::getAdsTypesList('active', true);
    //$array = json_decode(json_encode($adstypeslist), true);
?>
    <div class="by-type-options">
        <div class="container">
            <div class="row">
                <ul class="">
                @foreach ($array as $adstype)
                    <?php
                        list($rewrite_url, $count) = explode('#',$adstype );

                        $url    = $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url));
                        $title  = trans('adstypes.' . $rewrite_url);
                    ?>
                    @if($count > 1)
                    <li class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                        <a href="{{ url($url) }}" title="{!! strip_tags($title) !!}" class="btn btn-block btn-primary">
                            {!! title_case($title) !!}</span>
                        </a>
                    </li>
                    @endif
                @endforeach
                </ul>
            </div>
        </div>
    </div>
@else
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-5">
                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @else
                <ol class="breadcrumb">
                    <li><a href="{{ url('/') }}" title="{!! trans('navigation.home') !!}">{!! trans('navigation.home') !!}</a></li>
                    <li class="active">{!! trans('navigation.' . $view_name) !!}</li>
                </ol>
                @endif
            </div>
            <div class="col-xs-12 col-sm-4 col-md-7 text-right btn-dashboard">
                <a href="{{ url(trans_route($currentLocale, 'routes.sell')) }}" title="{!! trans('navigation.sell') !!}" class="lead btn btn-sm btn-primary">
                    <span class="hidden-sm"><strong>{!! trans('navigation.sell') !!}</strong></span>
                    <i class="hidden-xs fa fa-tag"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-tag"></i>
                </a>
                &nbsp;|&nbsp;
                <a href="{{ url(trans_route($currentLocale, 'routes.boat_on_demand')) }}" title="{!! trans('navigation.boat_on_demand') !!}" class="lead btn btn-sm btn-danger">
                    <span class="hidden-sm"><strong>{!! trans('navigation.boat_on_demand') !!}</strong></span>
                    <i class="hidden-xs fa fa-search-plus"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-search-plus"></i>
                </a>
                &nbsp;|&nbsp;
                @if($view_name == 'dashboard')
                <a href="https://www.boatgest.com" title="{!! trans('dashboard.professional_boating_customer') !!} : BoatGest.com" class="lead btn btn-sm btn-info blank">
                    <span class="hidden-sm"><strong>BoatGest.com</strong></span>
                    <i class="hidden-xs fa fa-ship"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-boat"></i>
                </a>
                &nbsp;|&nbsp;
                @endif
                @if(Auth::check())
                @if($view_name != 'dashboard')
                <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! !empty($customer_denomination) ? '(' . $customer_denomination . ') ' : '' !!}{!! trans('navigation.customer_area') !!}" class="lead btn btn-sm btn-info">
                    <span class="hidden-sm"><strong>{!! trans('navigation.customer_area') !!}</strong></span>
                    <i class="hidden-xs fa fa-dashboard"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-dashboard"></i>
                </a>
                &nbsp;|&nbsp;
                @endif
                <a href="{{ url(trans_route($currentLocale, 'routes.logout')) }}" title="{!! trans('navigation.logout') !!}" class="btn btn-sm btn-warning">
                    <i class="hidden-xs fa fa-sign-out fa-fw"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-sign-out fa-fw"></i>
                    <span class="hidden-sm">{!! trans('navigation.logout') !!}</span>
                </a>
                @else
                <a href="{{ url(trans_route($currentLocale, 'routes.login')) }}" title="{!! trans('navigation.customer_area') !!}" class="lead btn btn-sm btn-default accent-color">
                    <span class="hidden-xs"><strong>{!! trans('navigation.customer_area') !!}</strong></span>
                    <i class="hidden-xs fa fa-dashboard"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-dashboard"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
@endif
</div>
