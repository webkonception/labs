@include('partials.header')
@include('partials.navbar')
<div class="clearfix"></div>

@yield('content')

@if (Session::has('message'))
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-md-offset-1">
                <div class="note @if (Session::has('message.type'))note-{{ Session::get('message.type') }} @else note-info @endif">
                    <p><strong class="{!! Session::has('message.type') ? 'text-' . Session::get('message.type') : '' !!}">{!! Session::get('message.text') !!}</strong></p>
                </div>
            </div>
        </div>
    </div>
    {{ Session::forget('message') }}
@endif

{{--@include('partials.circular-navigation')--}}

<div class="scroll-to-top hidden">
    <i class="fa fa-arrow-up"></i>
</div>
@include('partials.javascripts')

@yield('javascript')
@include('partials.footer')
