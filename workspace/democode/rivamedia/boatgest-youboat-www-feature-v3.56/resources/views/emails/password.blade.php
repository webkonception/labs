<h2>{!! ENV('APP_NAME') !!}</h2>

<p>
    Click here to reset your password: {{ url('password/reset/'.$token) }}
</p>
