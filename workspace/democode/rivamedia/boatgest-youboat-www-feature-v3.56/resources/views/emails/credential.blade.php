<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.welcome_to') !!} {!! $website_name !!}.
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
</p>
<p>
    <strong>{!! trans('emails.account_created') !!}, {!! trans('emails.your_credentials') !!} :</strong>
    <br>
    <strong>- {!! trans('emails.username') !!}:</strong> {!! $username !!}
    <br>
    <strong>- {!! trans('emails.password') !!}:</strong> {!! $password !!}
    <br>
</p>
<p>
    <a href="{!! url(LaravelLocalization::transRoute('routes.login') . '?username=' . $username) !!}" title="{!! trans('navigation.login') !!}, {!! trans('navigation.click_here') !!}">{!! trans('navigation.login') !!}, {!! trans('navigation.click_here') !!}</a>
</p>

<hr>

<p>
    <strong>{!! trans('emails.greetings_team_message') !!}</strong>
</p>

<p>
    {!! trans('emails.visit_us') !!}:
    <br>
    <a href="{!! $website_url !!}}" title="YOUBOAT {!! mb_strtoupper($country_code) !!}">YOUBOAT {!! mb_strtoupper($country_code) !!}</a>
    <br>
    <a href="https://www.youboat.com/" title="YOUBOAT {!! mb_strtoupper($country_code) !!}">www.youboat.com</a>
</p>