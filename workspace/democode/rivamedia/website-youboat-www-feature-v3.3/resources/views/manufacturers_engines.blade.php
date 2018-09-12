@extends('layouts.theme')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ trans('navigation.manufacturers_engines') }}</h2></div>

                <div class="panel-body">
                    manufacturers_engines
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
