@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <h4>{!! trans('navigation.welcome') !!} </h4>
    @if ($isAdmin)
        @include(config('quickadmin.route') . '.partials.inbox')
    @endif
    <div class="well">
        <div class="row">
            <div class="col-sm-6"><strong>{!! ucfirst(trans('validation.attributes.username')) !!}</strong> : {!! Auth::user()->username !!}</div>
            <div class="col-sm-6"><strong>{!! ucfirst(trans('validation.attributes.email')) !!}</strong> : {!! Auth::user()->email !!}</div>
        </div>
    </div>
    {!! Auth::user()->username !!}
    {!! Auth::user()->email !!}
    <?php
    //if(!empty($usercaracts)) var_dump($usercaracts);
    ?>
@endsection