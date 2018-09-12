<?php

//            $url = route($currentRoute);
//            debug('$url');
//            debug($url);
//            $actionName = Route::getCurrentRoute()->getActionName();
//            debug('$actionName');
//            debug($actionName);
//            $action = Route::getCurrentRoute()->getAction();
//            debug('$action');
//            debug($action);
//            $action = Route::currentRouteAction();
//            debug('$action');
//            debug($action);
//            //$urlAction = action($action);
//            //debug($urlAction);
//            $fulllUrl = $url = app('request')->url();
//            debug('$fulllUrl');
//            debug($fulllUrl);
//            $currentPath= Route::getFacadeRoot()->current()->uri();
//            debug('$currentPath');
//            debug($currentPath);
//debug('__DIR__');
//debug(__DIR__);
?>
<?php
    $pageTitle = ucfirst(
            preg_replace(
                    '/([a-z0-9])?([A-Z])/', '$1 $2',
                    str_replace(
                            ['Controller', 'BodCaracts', 'AdsCaracts'],
                            ['', 'Boat On Demand', ucfirst(trans('metas.ads'))],
                            $currentController
                    )
            )
    );
    if ('QuickadminController' !== $currentController && 'DashboardController' != $currentController) {
        $pageTitle .= '&nbsp;:&nbsp';
        $pageTitle .= ucfirst(
                preg_replace(
                        '/([a-z0-9])?([A-Z])/', '$1 $2',
                        str_replace(
                                ['Controller', 'index', 'edit', 'create', 'show'],
                                [
                                        '',
                                        'Listing',
                                        'Edit<i class="fa fa-edit fa-fw"></i>',
                                        'Create<i class="fa fa-plus-circle fa-fw"></i>',
                                        'Detail<i class="fa fa-eye fa-fw"></i>'
                                ],
                                $currentAction
                        )
                )
        );
    }
?>

@include(config('quickadmin.route') . '.partials.header')
@include(config('quickadmin.route') . '.partials.topbar')
<div class="clearfix"></div>
<div class="page-container">

    @include(config('quickadmin.route') . '.partials.sidebar')

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title inline pull-left">
                        {!! $pageTitle !!}
                    </h3>
                    {{--@if (!preg_match('/index/', $currentRoute) || !preg_match('/show/', $currentRoute))--}}
                    @if (preg_match('/edit/', $currentRoute) || preg_match('/create/', $currentRoute))
                        {{--{!! htmlspecialchars_decode(link_to(url()->previous(), '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), ['class' => 'btn btn-default pull-right'])) !!}--}}
                        {!! htmlspecialchars_decode(link_to_route(str_replace(['edit','create'], ['index','index'], $currentRoute), '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
                    @endif
                </div>

            </div>

            @if ('QuickadminController' !== $currentController && 'DashboardController' != $currentController)
            @include(config('quickadmin.route') . '.partials.breadcrumb')
            @endif

            <div class="row" id="content">
                <div class="col-sm-12">

                    @if (Session::has('message'))
                        <div class="note note-info">
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    @endif

                    @yield('content')

                </div>
            </div>

        </div>

        <?php
            $pattern = '/index/';
        ?>
        @if (preg_match($pattern, $currentRoute) || preg_match('/actions/', $currentRoute))
            @include(config('quickadmin.route') . '.partials.inbox')
        @endif
    </div>
</div>

<div class="scroll-to-top"
     style="display: none;">
    <i class="fa fa-arrow-up"></i>
</div>
@include(config('quickadmin.route') . '.partials.javascripts')

@yield('javascript')
@include(config('quickadmin.route') . '.partials.footer')


