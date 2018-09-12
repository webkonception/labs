<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.getnotified_title', ['type_request' => isset($type_request) ? $type_request : 'a notification request']) !!}
    <br>
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
    <br>
    @if (isset($reference))
        <strong>- Ref.:</strong> {!! $reference !!}
    @endif
</p>

    {!! !empty($ad_url) && !empty($ad_title) ? '<p>' . trans('navigation.about') . ' ' . link_to($ad_url, $ad_title, []) . '</p>' : null !!}

    @if (isset($details) && count($details) > 0)
        <hr>
        <h3><u>{!! trans('emails.details') !!}:</u></h3>
        <p>
        @foreach($details as $key => $val)
            @if (isset($val) && $val != '')
                <strong>- {!! ucfirst(str_replace('_', ' ', $key)) !!}:</strong>&nbsp;
                <?php
                    if (preg_match("/description/", $key)) {
                        $val = nl2br($val);
                    }
                ?>
                {!! $val !!}
                <br>
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