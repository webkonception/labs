<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.welcome_to') !!} {!! $website_name !!}.
</p>

<p>
    {!! trans('emails.newsletter_confirmation_msg') !!}
</p>

<hr>
<h3><u>{!! trans('emails.your_informations') !!}:</u></h3>

<p>
    @if (isset($name))
    <strong>- {!! trans('emails.name')!!}:</strong> {!! $name !!}
    <br>
    @endif
    @if (isset($email))
    <strong>- {!! trans('emails.email')!!}:</strong> {!! $email !!}
    <br>
    @endif
    <br>
    <strong>{!! trans('elements.connect-with-us.dont_worry') !!}</strong>
</p>

<hr>

<p>
    <strong>{!! trans('emails.greetings_team_message') !!}</strong>
</p>

<p>
    {!! trans('emails.visit_us') !!}: <a href="{{ url('/') }}" title="{!! $website_name !!}">{!! trans('navigation.click_here') !!}</a>
</p>