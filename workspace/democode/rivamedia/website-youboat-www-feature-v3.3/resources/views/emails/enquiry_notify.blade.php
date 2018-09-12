<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.hello') !!} @if (isset($dealer_name)){!! strtoupper($dealer_name) !!}@endif,
    <br>
</p>

<p>
    {!! trans('emails.getnotified_title', ['type_request' => isset($type_request) ? $type_request : 'a notification request']) !!}
    @if (isset($name))
    <strong>{!! $name !!}</strong>
    <br>
    @endif
</p>

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