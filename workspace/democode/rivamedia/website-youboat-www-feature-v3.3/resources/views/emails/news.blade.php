<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.hello') !!} @if (isset($name)){!! strtoupper($name) !!}@endif,
    <br>
    {!! trans('emails.welcome_to') !!} {!! $website_name !!}.
</p>

<p>
    {!! trans('emails.thanks_news_create') !!}
</p>

<hr>
<h3><u>{!! trans('emails.your_news_details') !!}:</u></h3>

<?php
    $output = '';
    if (isset($details) && count($details) > 0) {
        foreach($details as $key => $val) {
            if (isset($val) && !empty($val)) {
                if (preg_match("/author_/", $key)) {
                    //$key = str_replace('author_', '', $key);
                    $key = $val = '';
                }

                if (!empty($key)) {
                    if(Lang::has('validation.attributes.' . $key)) {
                        $key = trans('validation.attributes.' . $key);
                    } else if(Lang::has('filters.' . $key)) {
                        $key = trans('filters.' . $key);
                    }
                    $key = ucfirst($key);
                    $key = '<strong>- ' . $key . ':</strong>&nbsp;';
                }

                if (preg_match("/description/", $key)) {
                    $val = nl2br($val);
                }
                $output .= $key;
                $output .= !empty($val) ? $val . '<br><br>' : '';
            }
        }
    }
?>
{!! $output !!}

<hr>

<p>
    <strong>{!! trans('emails.greetings_team_message') !!}</strong>
</p>

<p>
    {!! trans('emails.visit_us') !!}: <a href="{{ url('/') }}" title="{!! $website_name !!}">{!! trans('navigation.click_here') !!}</a>
</p>
