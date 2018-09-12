<!-- Top Header -->
<header class="top-header mmenu">
    <div class="container sp-cont">

        <ul class="pull-right social-icons social-icons-colored">
            <li class="facebook"><a href="{!! config('youboat.' . $country_code . '.facebook') !!}" class="blank"><i class="fa fa-facebook"></i></a></li>
            <li class="twitter"><a href="{!! config('youboat.' . $country_code . '.twitter') !!}" class="blank"><i class="fa fa-twitter"></i></a></li>
            {{--<li class="googleplus"><a href="#" class="blank"><i class="fa fa-google-plus"></i></a></li>--}}
            {{--<li class="linkedin"><a href="#" class="blank"><i class="fa fa-linkedin"></i></a></li>--}}
        </ul>

        {{--@include('theme.partials.header.top-header-navigation')--}}
        @include('theme.partials.header.navigation', ['navigation_type'=>'top-header-navigation'])

        <a href="#" class="visible-sm visible-xs" id="menu-toggle"><i class="fa fa-bars"></i></a>

    </div>
</header>
<!-- End Top Header -->