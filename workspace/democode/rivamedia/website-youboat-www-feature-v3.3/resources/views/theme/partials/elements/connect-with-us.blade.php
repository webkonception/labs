<?php
    $two_col = isset($block_format) && 'two-col' === $block_format ? true : false;
?>
<!-- Connect with us -->
<section class="connect-with-us widget-block">
@if ($two_col)
    <div class="row">
@endif
    @if ($two_col)
        <div class="col-md-6 col-sm-6">
    @endif
            <h4><i class="fa fa-rss"></i> {!! trans('elements.connect-with-us.title') !!}</h4>
            @if(Session::has('newsletter_message'))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert {!! Session::has('newsletter_message.type') ? 'alert-' . Session::get('newsletter_message.type') : 'alert-info' !!}">
                            <p><strong class="{!! Session::has('newsletter_message.type') ? 'text-' . Session::get('newsletter_message.type') : '' !!}">{!! Session::get('newsletter_message.text') !!}</strong></p>
                        </div>
                    </div>
                </div>
            @endif
            @if ('success' !== Session::get('newsletter_message.type'))
            {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.newsletter'), 'class'=>'form-horizontal', 'autocomplete'=>'on')) !!}
                {!! csrf_field() !!}
                {!! Form::hidden('country_code', $country_code) !!}
                <div class="{{ $errors->has('email') ? 'has-error' : '' }}">
                    {!! Form::email('email', isset($email) ? $email : old('email'), ['class'=>'form-control', 'placeholder'=>trans('elements.connect-with-us.subcribe_via_email'), 'required'=>'required']) !!}
                    @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>
                {!! Form::button(trans('elements.connect-with-us.subcribe'), ['type'=>'submit', 'class'=>'btn btn-sm btn-primary']) !!}
                <span class="meta-data">{!! trans('elements.connect-with-us.dont_worry') !!}</span>
            {!! Form::close() !!}
            @endif

            {{--@if (Session::has('newsletter_message'))
                {!! Session::forget('newsletter_message') !!}
            @endif--}}

        @if ($two_col)
        </div>
        @else
        <hr>
        @endif
        @if ($two_col)
        <div class="col-md-6 col-sm-6">
        @endif
            @if (config('youboat.'. $country_code .'.theme.helper_plugins.tweetie'))
                <div class="col-sm-12 well well-white">
                    @include('theme.partials.elements.block.twitter-block', ['tweets_count'=>isset($tweets_count) ? $tweets_count : 2])
                </div>
            @endif
        @if ($two_col)
        </div>
        @endif

    @if ($two_col)
    </div>
    @endif
</section>
