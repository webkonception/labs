<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.password_reset_msg') !!}:
    <br>
    {{ url('password/reset/'.$token) }}
</p>

<hr>

<p>
    <strong>{!! trans('emails.greetings_team_message') !!}</strong>
</p>

<p>
    {!! trans('emails.visit_us') !!}: <a href="{{ url('/') }}" title="{!! $website_name !!}">{!! trans('navigation.click_here') !!}</a>
</p>