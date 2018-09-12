<!-- Site Header V2 -->
<header class="site-header">
    <div class="container sp-cont">

        @include('theme.partials.header.site-logo')

        @include('theme.partials.header.header-right-v2')

        {{--@include('theme.partials.header.top-navigation')--}}
        @include('theme.partials.header.navigation', ['navigation_type'=>'top-navigation'])

    </div>
</header>
<!-- End Site Header V2 -->