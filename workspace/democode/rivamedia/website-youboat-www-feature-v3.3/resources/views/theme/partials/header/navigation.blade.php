<?php
    $urlPrefix = 'for_sale';
    //$urlPrefix = '/' . trans('routes.' . $urlPrefix);
    $urlPrefix = url(trans_route($currentLocale, 'routes.' . $urlPrefix));
    $array = Navigation::getAdsTypesList();
    //$adstypeslist = Navigation::getAdsTypesList('active', true);
    //$array = json_decode(json_encode($adstypeslist), true);
    //array_pop($array);
?>
@if (count($array) > 0)
<?php
    $min = 1;
    $segmentCount = 5;
    $dataCount = count($array);
    $segmentLimit = round($dataCount / $segmentCount);
    if($segmentLimit == 0) {
        $segmentLimit = 1;
    }
    //$outputArray  = array_chunk($array, $segmentLimit);
    $outputArray = array_chunk($array, $segmentLimit, true);
?>

<!-- Navigation -->
@if ('main-navigation' === $navigation_type)
    <!-- Main Navigation -->
    <nav class="main-navigation dd-menu toggle-menu" role="navigation">
        <ul class="sf-menu">
@endif

@if ('top-header-navigation' === $navigation_type)
    <!-- Top Header Navigation -->
    <div class="dd-menu toggle-menu" role="navigation">
        <ul class="sf-menu">
@endif

@if ('top-navigation' === $navigation_type)
    <!-- Top Navigation -->
    <div class="topnav dd-menu toggle-menu {!! $agent->isMobile() ? 'mobile' : '' !!}" role="navigation">
        <ul class="top-navigation sf-menu">
@endif
        @if ($agent->isMobile())
        <li class="action-bar visible-xs visible-sm row">
            @if(Auth::check())
            <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" class="col-xs-5 text-center" title="{!! !empty($customer_denomination) ? '(' . $customer_denomination . ') ' : '' !!}{!! trans('navigation.customer_area') !!}">
                <span class="text-success fa fa-2x fa-dashboard fa-fw"></span>
                <strong class="text-success">{!! trans('navigation.customer_area') !!}</strong>
            </a>
            <a href="{{ url(trans_route($currentLocale, 'routes.boat_on_demand')) }}" class="col-xs-6" title="{!! trans('navigation.boat_on_demand') !!}">
                <span class="accent-color-danger text-center uppercase strong">
                    <strong">{!! trans('navigation.boat_on_demand') !!}</strong>
                    <span class="fa fa-2x fa-search-plus fa-fw"></span>
                </span>
            </a>
            <a href="{{ url(trans_route($currentLocale, 'routes.logout')) }}" class="col-xs-1 text-center" title="{!! trans('navigation.logout') !!}">
                <span class="text-warning fa fa-2x fa-sign-out fa-fw"></span>
                <span class="hidden-xs text-warning">{!! trans('navigation.logout') !!}</span>
            </a>
            @else
            <a href="{{ url(trans_route($currentLocale, 'routes.login')) }}" class="col-xs-6 text-center" title="{!! trans('navigation.customer_area') !!}">
                <span class="accent-color fa fa-2x fa-dashboard fa-fw"></span>
                <strong class="accent-color">{!! trans('navigation.customer_area') !!}</strong>
            </a>
            <a href="{{ url(trans_route($currentLocale, 'routes.boat_on_demand')) }}" class="col-xs-6" title="{!! trans('navigation.boat_on_demand') !!}">
                <span class="accent-color-danger text-center uppercase strong">
                    <strong>{!! trans('navigation.boat_on_demand') !!}</strong>
                    <span class="fa fa-2x fa-search-plus fa-fw"></span>
                </span>
            </a>
            @endif
        </li>
        @endif

        @if (isset($outputArray[0]) && is_array($outputArray[0]))
            @foreach ($outputArray[0] as $key => $adstype)
            <li class="part-0">
                <?php
                    //$categorieslist = $controller->getCategories($key, true);
                    $categorieslist = Navigation::getAdsCategoriesList($key);
                    unset($items);
                    //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)), 'title' => trans('adstypes.' . $adstype)];
                    list($rewrite_url, $count) = explode('#',$adstype );
                    $url = $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url));
                    $items[] = ['url' => $url, 'title' => trans('adstypes.' . $rewrite_url)];
                ?>
                @if ($count >= $min)
                    @include('theme.partials.header.navigation-item', ['items' => $items])
                @endif
                @if(count($categorieslist))
                <ul class="dropdown">
                    @foreach ($categorieslist as $key => $category)
                    <li>
                    <?php
                        unset($items);
                        //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)) . '/' . trans('routes.' . str_replace('-', '_', $category)), 'title' => trans('categories.' . $category)];
                        list($rewrite_url_cat, $count) = explode('#',$category );
                        $items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url)) . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url_cat)), 'title' => trans('categories.' . $rewrite_url_cat)];
                    ?>
                    @if ($count >= $min)
                        @include('theme.partials.header.navigation-item', ['items' => $items])
                    @endif
                    </li>
                    @endforeach
                    <li>
                        <a href="{{ url(trans_route($currentLocale, 'routes.boat_on_demand')) }}" title="{!! trans('navigation.boat_on_demand') !!}">
                            <strong  class="accent-color-danger">{!! trans('navigation.boat_on_demand') !!}</strong>
                        </a>
                    </li>
                </ul>
                @endif
            </li>
            @endforeach
            @endif
            @if (isset($outputArray[1]) && is_array($outputArray[1]))
            @foreach ($outputArray[1] as $key => $adstype)
            <li class="part-1">
                <?php
                    //$categorieslist = $controller->getCategories($key, true);
                    $categorieslist = Navigation::getAdsCategoriesList($key);
                    unset($items);
                    //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)), 'title' => trans('adstypes.' . $adstype)];
                    list($rewrite_url, $count) = explode('#',$adstype );
                    $url = $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url));
                    $items[] = ['url' => $url, 'title' => trans('adstypes.' . $rewrite_url)];
                ?>
                @if ($count >= $min)
                    @include('theme.partials.header.navigation-item', ['items' => $items])
                @endif
                @if(count($categorieslist))
                <ul class="dropdown">
                    @foreach ($categorieslist as $key => $category)
                    <li>
                    <?php
                        unset($items);
                        //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)) . '/' . trans('routes.' . str_replace('-', '_', $category)), 'title' => trans('categories.' . $category)];
                        list($rewrite_url_cat, $count) = explode('#',$category );
                        $items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url)) . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url_cat)), 'title' => trans('categories.' . $rewrite_url_cat)];
                    ?>
                    @if ($count >= $min)
                        @include('theme.partials.header.navigation-item', ['items' => $items])
                    @endif
                    </li>
                    @endforeach
                    <li>
                        <a href="{{ url(trans_route($currentLocale, 'routes.boat_on_demand')) }}" title="{!! trans('navigation.boat_on_demand') !!}">
                            <strong  class="accent-color-danger">{!! trans('navigation.boat_on_demand') !!}</strong>
                        </a>
                    </li>
                </ul>
                @endif
            </li>
            @endforeach
            @endif
            @if (isset($outputArray[2]) && is_array($outputArray[2]))
            @foreach ($outputArray[2] as $key => $adstype)
            <li class="part-2">
                <?php
                    //$categorieslist = $controller->getCategories($key, true);
                    $categorieslist = Navigation::getAdsCategoriesList($key);
                    unset($items);
                    //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)), 'title' => trans('adstypes.' . $adstype)];
                    list($rewrite_url, $count) = explode('#',$adstype );
                    $url = $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url));
                    $items[] = ['url' => $url, 'title' => trans('adstypes.' . $rewrite_url)];
                ?>
                @if ($count >= $min)
                    @include('theme.partials.header.navigation-item', ['items' => $items])
                @endif
                @if(count($categorieslist))
                <ul class="dropdown">
                    @foreach ($categorieslist as $key => $category)
                    <li>
                    <?php
                        unset($items);
                        //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)) . '/' . trans('routes.' . str_replace('-', '_', $category)), 'title' => trans('categories.' . $category)];
                        list($rewrite_url_cat, $count) = explode('#',$category );
                        $items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url)) . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url_cat)), 'title' => trans('categories.' . $rewrite_url_cat)];
                    ?>
                    @if ($count >= $min)
                        @include('theme.partials.header.navigation-item', ['items' => $items])
                    @endif
                    </li>
                    @endforeach
                    <li>
                        <a href="{{ url(trans_route($currentLocale, 'routes.boat_on_demand')) }}" title="{!! trans('navigation.boat_on_demand') !!}">
                            <strong  class="accent-color-danger">{!! trans('navigation.boat_on_demand') !!}</strong>
                        </a>
                    </li>
                </ul>
                @endif
            </li>
            @endforeach
            @endif
            @if (isset($outputArray[3]) && is_array($outputArray[3]))
            @foreach ($outputArray[3] as $key => $adstype)
            <li class="part-3 hidden-sm">
                <?php
                    //$categorieslist = $controller->getCategories($key, true);
                    $categorieslist = Navigation::getAdsCategoriesList($key);
                    unset($items);
                    //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)), 'title' => trans('adstypes.' . $adstype)];
                    list($rewrite_url, $count) = explode('#',$adstype );
                    $url = $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url));
                    $items[] = ['url' => $url, 'title' => trans('adstypes.' . $rewrite_url)];
                ?>
                @if ($count >= $min)
                    @include('theme.partials.header.navigation-item', ['items' => $items])
                @endif
                @if(count($categorieslist))
                <ul class="dropdown">
                    @foreach ($categorieslist as $key => $category)
                    <li>
                    <?php
                        unset($items);
                        //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)) . '/' . trans('routes.' . str_replace('-', '_', $category)), 'title' => trans('categories.' . $category)];
                        list($rewrite_url_cat, $count) = explode('#',$category );
                        $items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url)) . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url_cat)), 'title' => trans('categories.' . $rewrite_url_cat)];
                    ?>
                    @if ($count >= $min)
                        @include('theme.partials.header.navigation-item', ['items' => $items])
                    @endif
                    </li>
                    @endforeach
                    <li>
                        <a href="{{ url(trans_route($currentLocale, 'routes.boat_on_demand')) }}" title="{!! trans('navigation.boat_on_demand') !!}">
                            <strong  class="accent-color-danger">{!! trans('navigation.boat_on_demand') !!}</strong>
                        </a>
                    </li>
                </ul>
                @endif
            </li>
            @endforeach
            @endif
            @if (isset($outputArray[4]) && is_array($outputArray[4]))
            @foreach ($outputArray[4] as $key => $adstype)
            <li class="part-4 hidden-md">
                <?php
                    //$categorieslist = $controller->getCategories($key, true);
                    $categorieslist = Navigation::getAdsCategoriesList($key);
                    unset($items);
                    //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)), 'title' => trans('adstypes.' . $adstype)];
                    list($rewrite_url, $count) = explode('#',$adstype );
                    $url = $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url));
                    $items[] = ['url' => $url, 'title' => trans('adstypes.' . $rewrite_url)];
                ?>
                @if ($count >= $min)
                    @include('theme.partials.header.navigation-item', ['items' => $items])
                @endif
                @if(count($categorieslist))
                <ul class="dropdown">
                    @foreach ($categorieslist as $key => $category)
                    <li>
                    <?php
                        unset($items);
                        //$items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $adstype)) . '/' . trans('routes.' . str_replace('-', '_', $category)), 'title' => trans('categories.' . $category)];
                        list($rewrite_url_cat, $count) = explode('#',$category );
                        $items[] = ['url' => $urlPrefix . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url)) . '/' . trans('routes.' . str_replace('-', '_', $rewrite_url_cat)), 'title' => trans('categories.' . $rewrite_url_cat)];
                    ?>
                    @if ($count >= $min)
                        @include('theme.partials.header.navigation-item', ['items' => $items])
                    @endif
                    </li>
                    @endforeach
                    <li>
                        <a href="{{ url(trans_route($currentLocale, 'routes.boat_on_demand')) }}" title="{!! trans('navigation.boat_on_demand') !!}">
                            <strong  class="accent-color-danger">{!! trans('navigation.boat_on_demand') !!}</strong>
                        </a>
                    </li>
                </ul>
                @endif
            </li>
            @endforeach
            @endif
        </ul>
@if ('top-header-navigation' === $navigation_type || 'top-navigation' === $navigation_type)
    </div>
@else
    </nav>
@endif
<!-- End Navigation -->
@endif
