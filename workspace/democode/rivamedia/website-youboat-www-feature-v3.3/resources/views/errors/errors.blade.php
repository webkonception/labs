<?php
    $error_code = isset($error_code) ? $error_code : '500';
    $error_content = isset($error_content) ? '<p>' . $error_content . '</p>' : '';
?>
@extends('layouts.theme')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-danger">
                    <div class="panel-heading clearfix"><h3 class="">{!! trans('errors/' . $error_code .'.error_title') !!} <strong class="pull-right text-danger"><i class="fa fa-warning fa-fw"></i>{!! trans('errors/' . $error_code .'.error_message') !!} </strong></h3></div>

                    <div class="panel-body bg-warning text-center">

                        <p class="lead">{!! trans('errors/' . $error_code .'.error_detail') !!}</p>
                        {!! $error_content !!}
                        {{--<p>{{$exception->getMessage()}}</p>--}}
                        {!! htmlspecialchars_decode(link_to(
                            '/',
                            trans('navigation.back_to_home') . '<i class="fa fa-home fa-fw"></i>',
                            ['class' => 'btn btn-default', 'title' => trans('navigation.back_to_home')]
                        )) !!}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
