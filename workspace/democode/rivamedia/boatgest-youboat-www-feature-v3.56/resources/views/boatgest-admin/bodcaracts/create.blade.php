<?php
    //$country_code = $bodcaracts->country_code;
    $website_name = !empty($website_name) ? $website_name : config('youboat.' . $country_code . '.website_name');
?>
@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    @if ($errors->any())
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <ul>
                        {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <hr>

    {!! Form::open(array('route' => config('quickadmin.route') . '.bodcaracts.store', 'id' => 'form-with-validation', 'role'=>'form', 'class' => 'form-horizontal')) !!}
        {!! Form::hidden('country_code', $country_code) !!}
        {!! Form::hidden('currency', config('youboat.'. $country_code .'.currency')) !!}

        @include('boatgest-admin.bodcaracts.elements.filters-form-bod', [])

        @include('boatgest-admin.bodcaracts.elements.recovery-form', ['form_action'=>'boat_on_demand_create'])

        @include('boatgest-admin.bodcaracts.elements.contact-informations-form', ['form_action'=>'boat_on_demand_create', 'countries'=>$countries, 'website_name'=>$website_name])

        <hr>

        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-exception']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.bodcaracts.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection

@section('javascript')
    <script src="{{ asset('assets/vendor/youboat/js/filters_search.js') }}"></script>
    <script src="{{ asset('assets/vendor/youboat/js/filters_recovery.js') }}"></script>
@endsection