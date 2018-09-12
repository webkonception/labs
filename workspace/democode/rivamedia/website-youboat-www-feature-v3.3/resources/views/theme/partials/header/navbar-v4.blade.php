<!-- Navbar V4 -->
<div class="navbar">
    <div class="container sp-cont">

        @if (config('youboat.'. $country_code .'.theme.tpl.currency_nav') || config('youboat.'. $country_code .'.theme.tpl.language_nav'))
        <ul class="pull-right additional-triggers">
            @if (config('youboat.'. $country_code .'.theme.tpl.currency_nav'))
            @include('theme.partials.header.currency')
            @endif

            @if (config('youboat.'. $country_code .'.theme.tpl.language_nav'))
            @include('theme.partials.header.language')
            @endif
        </ul>
        @endif

        @include('theme.partials.header.navigation', ['navigation_type'=>'main-navigation'])

        <a href="#" class="visible-sm visible-xs" id="menu-toggle"><i class="fa fa-bars"></i></a>

        {{--@include('theme.partials.elements.search.search-form')--}}

    </div>
</div>
<!-- End Navbar V4 -->