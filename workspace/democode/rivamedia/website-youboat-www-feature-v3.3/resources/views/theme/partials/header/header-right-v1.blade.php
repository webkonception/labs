<?php
    $topnav =  '    <div class="topnav dd-menu">' .
                '       <ul class="top-navigation">' .
                '           <li><a href="' . url(trans_route($currentLocale, 'routes.sell')) . '" title="' . trans('navigation.want_to_publish_your_ad') . '"><span class="text-success"><span class="hidden-xs">' . trans('navigation.want_to_publish_your_ad') . '</span><i class="hidden-xs fa fa-tag fa-flip-horizontal"></i><i class="visible-xs hidden-sm fa fa-3x fa-tag fa-flip-horizontal"></i></span></a></li>' .
                '       </ul>' .
                '       <ul class="top-navigation">' .
                '           <li><a href="' . url(trans_route($currentLocale, 'routes.for_sale')) . '" title="' . trans('navigation.buy') . '"><span class="hidden-xs accent-color">' . trans('navigation.buy') . '</span><i class="hidden-xs fa fa-cart-arrow-down accent-color"></i><i class="visible-xs hidden-sm  fa fa-3x fa-cart-arrow-down accent-color"></i></a></li>' .
                '           <li><a href="' . url(trans_route($currentLocale, 'routes.boat_on_demand')) . '" title="' . trans('navigation.boat_on_demand') . '" class="accent-color-danger"><span class="hidden-xs"><strong>' . trans('navigation.boat_on_demand') . '</strong></span><i class="hidden-xs fa fa-search-plus"></i><i class="visible-xs hidden-sm fa fa-3x fa-search-plus"></i></a></li>' .
                '       </ul>' .
                '       <div class="clearfix"></div>' .
                '</div>';
?>
@if (!$agent->isMobile())
<div class="col-xs-8 col-sm-9 header-right">
@else
<div class="header-right">
@endif
    @if (!$agent->isMobile() && $ad_banners)
        <div class="text-right">
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90', 'topnav' => $topnav])
        </div>
    @else
        {{--@include('theme.partials.header.user-login-panel', ['login_version'=>'v1'])--}}
        {!! $topnav !!}
    @endif
</div>