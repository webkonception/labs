<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.hello') !!} @if (isset($name)){!! strtoupper($name) !!}@endif,
    <br>
    {!! trans('emails.welcome_to') !!} {!! $website_name !!}.
</p>

<p>
    {!! trans('emails.welcome_request_information_msg') !!}
    <br>
    {!! trans('emails.welcome_notification_msg') !!}
</p>

<hr>

<p>
    <strong>{!! trans('emails.greetings_team_message') !!}</strong>
</p>

<p>
    {!! trans('emails.visit_us') !!}: <a href="{{ url('/') }}" title="{!! $website_name !!}">{!! trans('navigation.click_here') !!}</a>
</p>