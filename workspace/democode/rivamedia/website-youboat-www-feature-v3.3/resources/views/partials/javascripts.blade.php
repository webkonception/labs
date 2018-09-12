{{--<script src="//ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js" async></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/webfont/1.6.24/webfontloader.js" async></script>--}}
<script src="{!! asset('assets/js/webfonts.js') !!}" async></script>

@if (App::isLocal())
<script src="{!! asset('assets/vendor/jquery/jquery-1.12.1.min.js') !!}" defer></script>
<script src="{!! asset('assets/vendor/bootstrap/3.3.6/js/bootstrap.min.js') !!}" defer></script>
@else
<script src="//code.jquery.com/jquery-1.12.1.min.js" defer></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous" defer></script>
@endif
<script src="{{  asset('assets/vendor/youboat/js/youboat.js') }}" defer></script>