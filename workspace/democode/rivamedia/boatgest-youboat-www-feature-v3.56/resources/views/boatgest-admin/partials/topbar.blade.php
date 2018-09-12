<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner">
        <div class="navbar-header">
            <?php
                //$imgLogo = Html::image('assets/img/picto-boat.jpg', env('APP_NAME'), ['class' => 'logo logo visible-lg-inline', 'width' => 50]);
                //$imgLogo = app('html')->image('assets/img/picto-boat.jpg', env('APP_NAME'), ['class' => 'logo logo visible-lg-inline', 'width' => '50']);
                //$imgLogo = image('assets/img/picto-boat.jpg', env('APP_NAME'), ['class' => 'logo', 'width' => '50']);
            ?>
            {!! htmlspecialchars_decode(link_to(
                url(config('quickadmin.homeRoute')),
                //$imgLogo . env('APP_NAME'),
                '<i class="fa fa-ship fa-2x fa-fw"></i><h1>' . env('APP_NAME') .'</h1>',
                ['class' => 'navbar-brand']
            )) !!}
        </div>
        <a href="#"
           class="menu-toggler responsive-toggler"
           data-toggle="collapse"
           data-target=".navbar-collapse">
        </a>

        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">

            </ul>
        </div>
    </div>
</div>