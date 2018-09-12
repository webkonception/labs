<script src="{!! asset('assets/js/webfonts.js') !!}" async></script>

@if (App::isLocal())
<script src="{!! asset('assets/vendor/jquery/jquery-1.12.1.min.js') !!}" defer></script>
<script src="{!! asset('assets/vendor/bootstrap/3.3.6/js/bootstrap.min.js') !!}" defer></script>

<!-- Plugin JavaScript -->
<script src="{!! asset('assets/vendor/select2/4.0.2/js/select2.min.js') !!}" defer></script>

<script src="{!! asset('assets/vendor/moment/2.13.0/min/moment-with-locales.min.js') !!}" defer></script>
<script src="{!! asset('assets/vendor/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js') !!}" defer></script>

<script src="{!! asset('assets/vendor/wow/1.1.2/wow.min.js') !!}" defer></script>
<script src="{!! asset('assets/vendor/jquery.countdown/2.1.0/jquery.countdown.min.js') !!}" defer></script>
<script src="{!! asset('assets/vendor/vegas/2.2.0/vegas.min.js') !!}" defer></script>
@else
<script src="//code.jquery.com/jquery-1.12.1.min.js" defer></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous" defer></script>

<!-- Plugin JavaScript -->
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js" defer></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment-with-locales.min.js" defer></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js" defer></script>


<script src="//cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js" defer></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.1.0/jquery.countdown.min.js" defer></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/vegas/2.2.0/vegas.min.js" defer></script>
@endif
<!-- Custom Theme JavaScript -->
<script src="{!! asset('assets/theme/helper-plugins/helper-plugins.js') !!}" defer></script>
<script src="{!! asset('assets/vendor/youboat/js/youboat.js') !!}" defer></script>
<script src="{!! asset('assets/vendor/youboat/landing/js/landing.js') !!}" defer></script>