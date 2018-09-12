<div class="navbar">

    <div class="container sp-cont">
        <div class="row">
            <?php
                /*echo '<pre>';
            var_dump("config('app.locale')");
            var_dump(config('app.locale'));
            var_dump("config('app.fallback_locale')");
            var_dump(config('app.fallback_locale'));
            var_dump("config('app.fallback_subdomain')");
            var_dump(config('app.fallback_subdomain'));
            var_dump("session()->get('subdomain')");
            var_dump(session()->get('subdomain'));
            var_dump("session()->get('country_code')");
            var_dump(session()->get('country_code'));
            var_dump('$country_code');
            var_dump($country_code);
            var_dump("session()->get('locale')");
            var_dump(session()->get('locale'));
            var_dump("app('laravellocalization')->getCurrentLocale()");
            var_dump(app('laravellocalization')->getCurrentLocale());
            var_dump("app('laravellocalization')->hideDefaultLocaleInURL()");
            var_dump(app('laravellocalization')->hideDefaultLocaleInURL());
            var_dump("app('laravellocalization')->getDefaultLocale()");
            var_dump(app('laravellocalization')->getDefaultLocale());


            echo '</pre>';
                */
            ?>

        @if (config('youboat.'. $country_code .'.theme.tpl.currency_nav') || config('youboat.'. $country_code .'.theme.tpl.language_nav'))
            <ul class="additional-triggers hidden-xs hidden-sm">
                @if (config('youboat.'. $country_code .'.theme.tpl.currency_nav'))
                    @include('theme.partials.header.currency')
                @endif

                @if (config('youboat.'. $country_code .'.theme.tpl.language_nav'))
                    @include('theme.partials.header.language-picker')
                @endif
            </ul>
            @endif

            {{--@include('theme.partials.elements.search.search-function')--}}
            <a href="#" class="col-xs-2 col-sm-1 visible-sm visible-xs" id="menu-toggle"><i class="fa fa-bars"></i></a>
            {{--@include('theme.partials.header.main-navigation')--}}
            @include('theme.partials.header.navigation', ['navigation_type'=>'main-navigation'])

            @if($view_name == 'homepage')
            <div class="hidden-xs hidden-sm btn-dashboard pull-right">
                @if(Auth::check())
                <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! !empty($customer_denomination) ? '(' . $customer_denomination . ') ' : '' !!}{!! trans('navigation.customer_area') !!}" class="lead btn btn-xs btn-info">
                    <span class="hidden-xs"><strong>{!! trans('navigation.customer_area') !!}</strong></span>
                    <i class="hidden-xs fa fa-dashboard"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-dashboard"></i>
                </a>
                @else
                <a href="{{ url(trans_route($currentLocale, 'routes.login')) }}" title="{!! trans('navigation.customer_area') !!}" class="lead btn btn-default accent-color">
                    <span class="hidden-xs"><strong>{!! trans('navigation.customer_area') !!}</strong></span>
                    <i class="hidden-xs fa fa-dashboard"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-dashboard"></i>
                </a>
                @endif
            </div>
            <div class="hidden-xs hidden-sm btn-dashboard pull-left">
                <a href="{{ url(trans_route($currentLocale, 'routes.boat_on_demand')) }}" title="{!! trans('navigation.boat_on_demand') !!}" class="lead btn btn-danger">
                    <span class="hidden-xs"><strong>{!! trans('navigation.boat_on_demand') !!}</strong></span>
                    <i class="hidden-xs fa fa-search-plus"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-search-plus"></i>
                </a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="{{ url(trans_route($currentLocale, 'routes.sell')) }}" title="{!! trans('navigation.sell') !!}" class="lead btn btn-primary">
                    <span class="hidden-xs"><strong>{!! trans('navigation.sell') !!}</strong></span>
                    <i class="hidden-xs fa fa-tag"></i>
                    <i class="visible-xs hidden-sm fa fa-2x fa-tag"></i>
                </a>
            </div>
            @endif

            {{--@include('theme.partials.elements.search.search-form')--}}
            @if ($agent->isMobile())
            <div class="col-xs-10 col-sm-11 search_form visible-xs visible-sm">
                @include('theme.partials.elements.search.search-form-light')
            </div>
            @endif

        </div>
    </div>

</div>