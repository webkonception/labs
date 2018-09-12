<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.hello') !!} @if (isset($name)){!! strtoupper($name) !!}@endif,
    <br>
    {!! trans('emails.welcome_to') !!} {!! $website_name !!}.
</p>

<p>
    {!! trans('emails.enquiry_confirmation_msg') !!} {{--[@if (isset($reference))Ref.: {!! $reference !!}@endif]--}}
</p>

{!! !empty($ad_url) && !empty($ad_title) ? '<p>' . trans('navigation.about') . ' ' . link_to($ad_url, $ad_title, []) . (!empty($ad_budget) ? ' (' . $ad_budget .')' : ' ') . '</p>' : null !!}

<hr>
<h3><u>{!! trans('emails.your_informations') !!}:</u></h3>

<p>
    @if (isset($details) && count($details) > 0)
        @foreach($details as $key => $val)
            @if (isset($val) && $val != '')
                <strong>- {!! ucfirst(trans('validation.attributes.' . $key)) !!}:</strong>&nbsp;
                <?php
                    if (preg_match("/description/", $key)) {
                        $val = nl2br($val);
                    }
                ?>
                {!! $val !!}
                <br>
            @endif
        @endforeach
    @endif
</p>

<hr>

<p>
    <strong>{!! trans('emails.greetings_team_message') !!}</strong>
</p>

<p>
    {!! trans('emails.visit_us') !!}: <a href="{{ url('/') }}" title="{!! $website_name !!}">{!! trans('navigation.click_here') !!}</a>
</p>