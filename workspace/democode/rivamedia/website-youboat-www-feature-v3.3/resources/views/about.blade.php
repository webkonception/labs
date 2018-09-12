@extends('layouts.theme')
<?php
    $metas_title = trans('metas.about_us_title');
    $metas_description = trans('metas.about_us_desc');
    $metas = [
            'metas_title' => $metas_title . ' - ' . $website_name,
            'metas_description' => $metas_description
        //,'metas_keywords' => $metas_keywords
    ];
?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <p class="well well-info lead text-justify">
                <i class="fa fa-shield fa-5x pull-left" aria-hidden="true"></i>
                {!! trans('landing.landing_text_02') !!}
                {!! trans('landing.landing_text_03') !!}
                <br>
                <br>
                {!! trans('landing.landing_text_04') !!}
            </p>
        </div>
    </div>
    <div class="row text-center">
        <div class="col-md-4 col-sm-4">
            <div class="well well-white">
                <i class="fa fa-4x fa-search pull-left"></i>
                <p class="text-justify">{!! trans('landing.landing_text_05') !!}</p><br>
                <p class="panel-footer text-center">
                    <strong>{!! trans('landing.landing_text_06') !!}</strong>
                </p>
                <p class="text-center">
                    {!! link_trans_route('for_sale', 'navigation.for_sale', ['class'=>'btn btn-block btn-danger btn-lg']) !!}
                </p>
            </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="well well-white">
                <i class="fa fa-4x fa-ship pull-left"></i>
                <p class="text-justify">{!! trans('landing.landing_text_07') !!}</p><br>
                <p class="panel-footer text-center">
                    <strong>{!! trans('landing.landing_text_08') !!}</strong>
                </p>
                <p class="text-center">
                    {!! link_trans_route('boat_on_demand', 'navigation.boat_on_demand', ['class'=>'btn btn-block btn-success btn-lg']) !!}
                </p>
            </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="well well-white">
                <i class="fa fa-4x fa-tags pull-left"></i>
                <p class="text-justify">{!! trans('landing.landing_text_09') !!}</p><br>
                <p class="panel-footer text-center">
                    <strong>{!! trans('landing.landing_text_10') !!}</strong>
                </p>
                <p class="text-center">
                    {!! link_trans_route('sell', 'navigation.sell', ['class'=>'btn btn-block btn-primary btn-lg']) !!}
                </p>
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
