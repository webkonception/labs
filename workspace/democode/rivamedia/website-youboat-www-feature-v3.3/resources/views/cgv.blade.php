@extends('layouts.theme')
<?php
$metas_title = trans('metas.cgv_title');
$metas_description = trans('metas.cgv_desc');
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
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h2 class="uppercase text-primary">{{ trans('navigation.cgv') }}</h2></div>

                <div class="panel-body">
                    <?php $j = 0; ?>
                    @for ($i=1;$i<18;$i++)
                    <?php $j++; ?>
                    @if (\Lang::has('cgv.article_' . $i .'_title'))
                    <h3 class="text-info">{!! $j !!}. {!! trans('cgv.article_' . $i .'_title') !!}</h3>
                    <blockquote>
                        <p>{!! trans('cgv.article_' . $i .'_text') !!}</p>
                    </blockquote>
                    @endif
                    @endfor
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
