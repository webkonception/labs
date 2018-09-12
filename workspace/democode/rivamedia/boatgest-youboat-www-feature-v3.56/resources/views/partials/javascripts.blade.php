@if (App::isLocal())
<script src="{{ asset('assets/vendor/jquery/jquery-1.12.1.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/3.3.6/js/bootstrap.min.js') }}"></script>
@else
<script src="https://code.jquery.com/jquery-1.12.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
@endif
<script src="{{ asset('assets/vendor/youboat/js/youboat.js') }}"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

