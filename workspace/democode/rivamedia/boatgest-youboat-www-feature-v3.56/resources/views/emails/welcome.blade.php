<h2>{!! ENV('APP_NAME') !!}</h2>

<p>
    Hey {{ $username }}, Welcome to our website. <br>

</p>

<hr>

<p>
    <u>Your informations:</u>
    <br>
    <strong>- Your account has been created !<br><br>
    <strong>- Username :</strong> {!! $username !!}
    <br>
    <strong>- Password :</strong> {!! $password !!}
    <br>
</p>

<p>
    Please click <a href="{!! url('/login?username=' . $username) !!}">Here</a> to login<br>
</p>