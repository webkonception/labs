<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.hello') !!},
    <br>
    {!! trans('emails.welcome_to') !!} {!! $website_name !!}.
</p>

<p>
    {!! trans('emails.search_notification_confirmation_msg') !!}
    <br>
    {!! trans('emails.contact_soon_msg') !!}
</p>

<hr>
<h3><u>{!! trans('emails.your_informations') !!}:</u></h3>

<p>
    @if (isset($reference))
        <strong>- {!! trans('emails.email') !!}:</strong> {!! $email !!}
        <br>
    @endif
    {{--@if (isset($reference))
        <strong>- Ref.:</strong> {!! $reference !!}
        <br>
    @endif--}}
</p>
    @if (isset($details) && count($details) > 0)
        <hr>
        <h4><u>{!! trans('emails.your_search_criteria') !!}:</u></h4>
        <p>
        @foreach($details as $key => $val)
            @if (isset($val) && $val != '')
                <strong>- {!! ucfirst(str_replace('_', ' ', $key)) !!}:</strong>&nbsp;
                {!! $val !!}
                <br>
            @endif
        @endforeach
        </p>
    @endif

<hr>

<p>
    <strong>{!! trans('emails.greetings_team_message') !!}</strong>
</p>

<p>
    {!! trans('emails.visit_us') !!}: <a href="{{ url('/') }}" title="{!! $website_name !!}">{!! trans('navigation.click_here') !!}</a>
</p>