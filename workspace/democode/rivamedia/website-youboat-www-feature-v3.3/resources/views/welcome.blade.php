@extends('layouts.theme')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ trans('navigation.welcome') }}</h2></div>

                <div class="panel-body">
                @if(Session::has('register_message'))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert @if (Session::has('register_message.type'))alert-{{ Session::get('register_message.type') }} @else alert-info @endif">
                                <p><strong class="{!! Session::has('register_message.type') ? 'text-' . Session::get('register_message.type') : '' !!}">{!! Session::get('register_message.text') !!}</strong></p>
                            </div>
                        </div>
                    </div>
                    {{--{!! Session::forget('register_message') !!}--}}
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{--
@section('javascript')
    @if($errors->any() || Session::has('errors') || Session::has('message') || Session::has('newsletter_message'))
        <script>$(document).ready(function(){$("#msgModal").modal('show');});</script>
    @endif
@endsection--}}
