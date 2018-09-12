<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.hello') !!} @if (isset($name)){!! strtoupper($name) !!}@endif,
    <br>
    {!! trans('emails.welcome_to') !!} {!! $website_name !!}.
</p>

<p>
    {!! trans('emails.contact_confirmation_msg') !!}
    <br>
    {!! trans('emails.contact_soon_msg') !!}
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
    @if (isset($phone))
    <strong>- {!! trans('emails.phone')!!}:</strong> {!! $phone !!}
    <br>
    @endif
    @if (isset($user_message))
    <strong>- {!! trans('emails.message')!!}:</strong>
    <br>
    {!! $user_message !!}
    @endif
</p>

<hr>

<p>
    <strong>{!! trans('emails.greetings_team_message') !!}</strong>
</p>

<p>
    {!! trans('emails.visit_us') !!}: <a href="{{ url('/') }}" title="{!! $website_name !!}">{!! trans('navigation.click_here') !!}</a>
</p>