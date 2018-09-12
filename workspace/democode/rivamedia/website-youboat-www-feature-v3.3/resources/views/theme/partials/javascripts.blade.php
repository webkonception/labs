@if (App::isLocal())
    @include('theme.partials.assets.local-js')
    @include('theme.partials.assets.common-js')
@else
    {{--@include('theme.partials.assets.external-js')--}}
    <script src="//code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js" defer></script>

    @if(isset($view_name) && !preg_match('/sell/', $view_name))
    <script src="//cdnjs.cloudflare.com/ajax/libs/prettyPhoto/3.1.6/js/jquery.prettyPhoto.min.js" defer></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js" defer></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/flexslider/2.6.1/jquery.flexslider.min.js" defer></script>
    @endif

    <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAVLb_ZvJDsjKcOxkB6ylTiM3XUMLwdimk" async defer></script>

    @include('theme.partials.assets.external-common-js')
@endif
