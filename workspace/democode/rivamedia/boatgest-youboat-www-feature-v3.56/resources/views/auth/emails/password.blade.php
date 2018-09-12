<?php
    use App\CustomersCaracts;
    use App\CommercialsCaracts;
    use App\DealersCaracts;
    use App\PrivatesCaracts;
    $user_id = $user->id;
    $user_type = $user->type;
    switch($user_type) {
        case 'admin':
            $usercaracts = [];
            break;
        case 'private':
            $usercaracts = PrivatesCaracts::where('user_id', $user_id)->get();
            break;
        case 'dealer':
            $usercaracts = DealersCaracts::where('user_id', $user_id)->get();
            break;
        case 'customer':
            $usercaracts = CustomersCaracts::where('user_id', $user_id)->get();
            break;
        case 'commercial':
            $usercaracts = CommercialsCaracts::where('user_id', $user_id)->get();
            break;
    }
    $array = json_decode(json_encode($usercaracts), true);
    if(!empty($array[0])) {
        $user_caracts = $array[0];
    }

    $email = urlencode($user->getEmailForPasswordReset());
    $name = !empty($user_caracts["denomination"]) ? mb_strtoupper($user_caracts["denomination"]) . '<br>' : '';
    $name .= !empty($user_caracts["firstname"]) ? ucfirst(mb_strtolower($user_caracts["firstname"])) . ' ' : '';
    $name .= !empty($user_caracts["name"]) ? $user_caracts["name"] : '';

    $reset_link = ucfirst(link_trans_url('password/reset', 'navigation.reset', ['token' => $token, 'email' => $email]));
    $website_url = '';
    $password_reset_link = link_trans_url('password/reset', 'emails.password_reset_msg', ['token' => $token, 'email' => $email]);
?>
<h2>{!! ENV('APP_NAME') !!}</h2>

<h3>{!! trans('passwords.password_reset_email_title') !!}</h3>

<p>
    {!! trans('emails.hello') !!} {!! $name !!},
</p>

<p>
    {!! trans('passwords.password_reset_email_txt', [
        'reset_link' => $reset_link,
        'website_url' => $website_url,
        'password_reset_link' => $password_reset_link,
    ]) !!}
</p>

<p>
    <strong>{!! trans('emails.greetings_team_message') !!}</strong>
</p>
