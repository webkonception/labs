<?php
/*
        'id',
        'ad_referrer',
        'ad_country_code',

        'ad_title',
        'ad_ref',

        'ad_type_cat_url',
        'ad_type',

        'ad_type_cat_name',
        'ad_category',

        'ad_manufacturer_name',
        'ad_manufacturer_url',

        'ad_model_name',
        'ad_model_url',

        'ad_price',
        'ad_price_descr',

        'ad_location',
        'ad_mooring_country',
        'ad_country',
        'ad_region',
        'ad_county',

        'ad_dealer_name',
        'ad_dealer_url',

        'ad_phones',

        'ad_sale',
        'ad_sale_type_condition',
        'ad_sales_status',

        'ad_year_built',

        'ad_width',
        'ad_length',

        'ad_width_meter',
        'ad_length_meter',

        'ad_description_full',
        'ad_description_caracts_labels',
        'ad_description_caracts_values',

        'ad_specifications_full',
        'ad_specifications_caracts_labels',
        'ad_specifications_caracts_values',

        'ad_features_full',
        'ad_features_caracts_categories',
        'ad_features_caracts_values',

        'ad_photo',
        'ad_photos_thumbs',
        'ad_photos',

        'ad_propulsion',
        'ad_nb_engines',

        'ad_pageUrl',

        // @TODO

        'dealerscaracts_id',
        'adstypes_id',
        'categories_ids',
        'subcategories_ids',

        'countries_id',
        'province',
        'region',
        'subregion',
        'city',
        'zip',

        'sell_type',

        'start_date',
        'end_date',
        'status'
*/
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

    {!! Form::model($gateway_details, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'role'=>'form', 'method' => 'PATCH', 'route' => array(config('quickadmin.route') . '.scrappingadsdetails.update', $gateway_details->id))) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_referrer', 'ad_referrer', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_referrer', old('ad_referrer', $ad_referrer), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_country_code', 'ad_country_code', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_country_code', old('ad_country_code', $ad_country_code), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_title', 'ad_title', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_title', old('ad_title', $ad_title), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_ref', 'ad_ref', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_ref', old('ad_ref', $ad_ref), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_type_cat_url', 'ad_type_cat_url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_type_cat_url', old('ad_type_cat_url', $ad_type_cat_url), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_type', 'ad_type', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">

                        {!! Form::text('ad_type', old('ad_type', $ad_type), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_type_cat_name', 'ad_type_cat_name', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_type_cat_name', old('ad_type_cat_name', $ad_type_cat_name), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_category', 'ad_category', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_category', old('ad_category', $ad_category), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_manufacturer_name', 'ad_manufacturer_name', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_manufacturer_name', old('ad_manufacturer_name', $ad_manufacturer_name), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_manufacturer_url', 'ad_manufacturer_url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_manufacturer_url', old('ad_manufacturer_url', $ad_manufacturer_url), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_model_name', 'ad_model_name', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_model_name', old('ad_model_name', $ad_model_name), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_model_url', 'ad_model_url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_model_url', old('ad_model_url', $ad_model_url), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_price', 'ad_price', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_price', old('ad_price', $ad_price), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_price_descr', 'ad_price_descr', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_price_descr', old('ad_price_descr', $ad_price_descr), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_location', 'ad_location', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_location', old('ad_location', $ad_location), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_mooring_country', 'ad_mooring_country', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_mooring_country', old('ad_mooring_country', $ad_mooring_country), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_country', 'ad_country', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_country', old('ad_country', $ad_country), ['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('ad_region', 'ad_region', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_region', old('ad_region', $ad_region), ['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('ad_county', 'ad_county', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_county', old('ad_country', $ad_county), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_dealer_name', 'ad_dealer_name', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_dealer_name', old('ad_dealer_name', $ad_dealer_name), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_dealer_url', 'ad_dealer_url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_dealer_url', old('ad_dealer_url', $ad_dealer_url), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_phones', 'ad_phones', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_phones', old('ad_phones', $ad_phones), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_sale', 'ad_sale', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_sale', old('ad_sale', $ad_sale), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_sale_type_condition', 'ad_sale_type_condition', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_sale_type_condition', old('ad_sale_type_condition', $ad_sale_type_condition), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_sales_status', 'ad_sales_status', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_sales_status', old('ad_sales_status', $ad_sales_status), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_year_built', 'ad_year_built', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_year_built', old('ad_year_built', $ad_year_built), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_width', 'ad_width', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_width', old('ad_width', $ad_width), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_length', 'ad_length', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_length', old('ad_length', $ad_length), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_width_meter', 'ad_width_meter', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_width_meter', old('ad_width_meter', $ad_width_meter), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_length_meter', 'ad_length_meter', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_length_meter', old('ad_length_meter', $ad_length_meter), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    {!! Form::label('ad_description_full', 'ad_description_full', ['class'=>'col-xs-3 col-sm-2 control-label']) !!}
                    <div class="col-xs-9 col-sm-10">
                        {!! Form::textarea('ad_description_full', old('ad_description_full', $ad_description), ['class'=>'form-control', 'id'=>'ad_description_full']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_description_caracts_labels', 'ad_description_caracts_labels', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_description_caracts_labels', old('ad_description_caracts_labels', $ad_description_caracts_labels), ['class'=>'form-control', 'id'=>'ad_description_caracts_labels']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_description_caracts_values', 'ad_description_caracts_values', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_description_caracts_values', old('ad_description_caracts_values', $ad_description_caracts_values), ['class'=>'form-control', 'id'=>'ad_description_caracts_values']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    {!! Form::label('ad_specifications_full', 'ad_specifications_full', ['class'=>'col-xs-3 col-sm-2 control-label']) !!}
                    <div class="col-xs-9 col-sm-10">
                        {!! Form::textarea('ad_specifications_full', old('ad_specifications_full', $ad_specifications), ['class'=>'form-control', 'id'=>'ad_specifications_full']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_specifications_caracts_labels', 'ad_specifications_caracts_labels', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_specifications_caracts_labels', old('ad_specifications_caracts_labels', $ad_specifications_caracts_labels), ['class'=>'form-control', 'id'=>'ad_specifications_caracts_labels']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_specifications_caracts_values', 'ad_specifications_caracts_values', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_specifications_caracts_values', old('ad_specifications_caracts_values', $ad_specifications_caracts_values), ['class'=>'form-control', 'id'=>'ad_specifications_caracts_values']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    {!! Form::label('ad_features_full', 'ad_features_full', ['class'=>'col-xs-3 col-sm-2 control-label']) !!}
                    <div class="col-xs-9 col-sm-10">
                        {!! Form::textarea('ad_features_full', old('ad_features_full', $ad_features), ['class'=>'form-control', 'id'=>'ad_features_full']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_features_caracts_categories', 'ad_features_caracts_categories', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_features_caracts_categories', old('ad_features_caracts_categories', $ad_features_caracts_categories), ['class'=>'form-control', 'id'=>'ad_features_caracts_categories']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_features_caracts_values', 'ad_features_caracts_values', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_features_caracts_values', old('ad_features_caracts_values', $ad_features_caracts_values), ['class'=>'form-control', 'id'=>'ad_features_caracts_values']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_photo', 'ad_photo', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_photo', old('ad_photo', $ad_photo), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_photos_thumbs', 'ad_photos_thumbs', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_photos_thumbs', old('ad_photos_thumbs', $ad_photos_thumbs), ['class'=>'form-control', 'id'=>'ad_photos_thumbs']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_photos', 'ad_photos', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_photos', old('ad_photos', $ad_photos), ['class'=>'form-control', 'id'=>'ad_photos']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_propulsion', 'ad_propulsion', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_propulsion', old('ad_propulsion', $ad_propulsion), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_nb_engines', 'ad_nb_engines', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_nb_engines', old('ad_nb_engines', $ad_nb_engines), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_pageUrl', 'ad_pageUrl', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_pageUrl', old('ad_pageUrl', $ad_pageUrl), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . ucfirst(trans('navigation.update')), ['type' => 'submit', 'class' => 'btn btn-lg btn-success btn-exception']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.scrappingadsdetails.index', '<i class="fa fa-ban fa-fw"></i>' . ucfirst(trans('navigation.cancel')), [], ['class' => 'btn btn-default pull-right'])) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection