@include('landing.partials.head')
<body id="landing-layout" class="wow fadeIn">

    <div class="overlay"></div>

    <div class="clearfix"></div>

    @if(Session::has('message'))
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-md-offset-1">
                <div class="alert @if (Session::has('message.type'))alert-{{ Session::get('message.type') }} @else alert-info @endif">
                    <p><strong class="{!! Session::has('message.type') ? 'text-' . Session::get('message.type') : '' !!}">{!! Session::get('message.text') !!}</strong></p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @yield('content')

    @include('landing.partials.javascripts')

    @yield('javascript')

</body>
</html>
@if (Session::has('message'))
    {!! Session::forget('message') !!}
@endif

@if (Session::has('getnotified_message'))
    {!! Session::forget('getnotified_message') !!}
@endif
