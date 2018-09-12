@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-danger">
                    <div class="panel-heading"><h3>Error 403 Forbidden</h3></div>

                    <div class="panel-body bg-warning text-center">
                        <h4 class="text-danger"><i class="fa fa-warning fa-fw"></i>No Permission to Access</h4>
                        <p class="lead">You need to login !</p>
                        <p class="text-center">
                            {!! htmlspecialchars_decode(link_to_action('Auth\AuthController@getLogin', trans('navigation.login') . '<i class="fa fa-sign-in fa-fw"></i>', [], ['class' => 'btn btn-block btn-lg btn-success', 'title' => ucfirst(trans('navigation.login'))])) !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
