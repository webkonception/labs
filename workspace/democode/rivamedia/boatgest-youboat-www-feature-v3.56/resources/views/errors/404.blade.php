@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-danger">
                    <div class="panel-heading"><h3>Error 404</h3></div>

                    <div class="panel-body bg-warning text-center">
                        <h4 class="text-danger"><i class="fa fa-warning fa-fw"></i>The page cannot be found</h4>
                        <p class="lead">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                        <p class="text-center">
                            {!! htmlspecialchars_decode(link_to(url(config('quickadmin.homeRoute')), trans('navigation.back_to') . ' ' . trans('navigation.dashboard') . '<i class="fa fa-dashboard fa-fw"></i>',['class' => 'btn btn-primary', 'title' => ucfirst(trans('navigation.dashboard'))])) !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
