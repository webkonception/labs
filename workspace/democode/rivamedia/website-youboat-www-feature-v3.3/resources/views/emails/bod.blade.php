<h2>{!! $website_name !!}</h2>

<p>
    {!! trans('emails.hello') !!} {!! strtoupper($name) !!},
    <br>
    {!! !preg_match('/already_created/', $password) ? trans('emails.welcome_to') : trans('emails.welcome_back') !!} {!! $website_name !!}.
</p>

<p>
    {!! trans('emails.thanks_boat_on_demand') !!}
    <br>
    {!! trans('emails.contact_soon_msg') !!}
    <br><br>
    @if (isset($reference))
        <strong>- Ref.:</strong> {!! $reference !!}
    @endif
</p>

<hr>

<p>
    {!! trans('emails.modify_your_request', ['link_dashboard'=> link_trans_route('dashboard', 'navigation.click_here', [])]) !!}
    <br>
    <br>
    {!! trans('dashboard.your_login_informations') !!} :
    <br>
    @if (isset($email))
    <strong>- {!! trans('emails.email') !!}:</strong> {!! $email !!}
    <br>
    @endif
    @if (isset($password) && !preg_match('/already_created/', $password))
    <strong>- {!! trans('emails.password') !!}:</strong> {!! $password !!}
    <br>
    @else
    <?php
        $login_link = link_trans_url(trans_route($currentLocale, 'routes.login'), 'navigation.login', [], ['target' => '_blank']);
        $forgotten_password_link = link_trans_url(trans_route($currentLocale, 'routes.password_email'), 'passwords.textlink_reset_password', ['email' => $email], ['target' => '_blank']);
    ?>
    {!! trans('boat_on_demand.email_already_created', ['website_name'=>$website_name, 'login_link'=>$login_link]) !!}
    <br>
    {!! trans('boat_on_demand.email_lost_password', ['forgotten_password_link'=>$forgotten_password_link]) !!}
    @endif
    </p>

    <hr>
    <h3><u>{!! trans('emails.your_bod_description') !!}:</u></h3>

<?php
    $output = '';
    if (isset($details) && count($details) > 0) {
        $breaker = '';
        $breaker_trade_in = false;
        $breaker_address = false;
        $breaker_agree = false;
        foreach($details as $key => $val) {
            if (isset($val) && !empty($val)) {
                $more = '';
                if (preg_match("/ci_email/", $key) || preg_match("/password/", $key)) {
                    $key = $val = '';
                } else if (!preg_match("/with_marina_berth/", $key) && !preg_match("/agree/", $key) && !preg_match("/ci_email/", $key) && !preg_match("/password/", $key)) {
                    $key = str_replace('recovery', 'trade_in', $key);
                    if (preg_match("/agree/", $key)) {
                        $key = '';
                    } else if (preg_match("/first_name/", $key)) {
                        $breaker = !$breaker_address ? '<h4><u>' . trans('emails.your_details') . '</u></h4>' : '';
                        $breaker_address = true;
                    } else if (preg_match("/trade_in/", $key)) {
                        $breaker = !$breaker_trade_in ? '<h4><u>' . str_replace(['(', ')'], ['', ''], trans('filters.trade_in')) . '</u></h4>' : '';
                        $breaker_trade_in = true;
                        $more =  trans('filters.trade_in'). ' ';
                        $key = str_replace('trade_in_', '', $key);
                    } else {
                        $breaker = '';
                    }
                } else if (preg_match("/agree_emails/", $key)) {
                    $key = '';
                    $val = '<strong>- ' . $val . ':</strong>&nbsp;' . trans('filters.yes');
                    $breaker = !$breaker_agree ? '<hr>' : '';
                    $breaker_agree = true;
                } else {
                    $key = '';
                    $val = '<strong>- ' . $val . ':</strong>&nbsp;' . trans('filters.yes');
                    $breaker = '';
                    $breaker_trade_in = false;
                    $breaker_address = false;
                    $breaker_agree = false;
                }

                if (!empty($key)) {
                    if(Lang::has('validation.attributes.' . $key)) {
                        $key = trans('validation.attributes.' . $key);
                    } else if(Lang::has('filters.' . $key)) {
                        $key = trans('filters.' . $key);
                    }
                    $key = ucfirst($key) . ' ' . $more;
                    $key = '<strong>- ' . $key . ':</strong>&nbsp;';
                }

                if ($breaker_trade_in || $breaker_address || $breaker_agree) {
                    $output .= $breaker;
                }
                if (preg_match("/description/", $key)) {
                    $val = nl2br($val);
                }
                $output .= $key;
                $output .= !empty($val) ? $val . '<br>' : '';
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
