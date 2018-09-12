@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-danger">
                    <div class="panel-heading"><h3>Error 503</h3></div>

                    <div class="panel-body bg-warning text-center">
                        <h4 class="text-danger"><i class="fa fa-warning fa-fw"></i>Service Unavailable</h4>
                        <p class="lead">Request unsuccessful to the server being down or overloaded.<br>Be right back.</p>
                        <ul class="row">
                            <li class="col-sm-6">{!! htmlspecialchars_decode(link_to(url(config('quickadmin.homeRoute')), trans('navigation.back_to') . ' ' . trans('navigation.dashboard') . '<i class="fa fa-dashboard fa-fw"></i>',['class' => 'btn btn-primary', 'title' => ucfirst(trans('navigation.dashboard'))])) !!}</li>
                            <li class="col-sm-6">{!! htmlspecialchars_decode(link_to_action('Auth\AuthController@getLogin', trans('navigation.login') . '<i class="fa fa-sign-in fa-fw"></i>', [], ['class' => 'btn btn-block btn-lg btn-success', 'title' => ucfirst(trans('navigation.login'))])) !!}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
