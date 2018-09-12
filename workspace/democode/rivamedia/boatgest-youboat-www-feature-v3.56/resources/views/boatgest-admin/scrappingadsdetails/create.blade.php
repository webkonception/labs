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

    {!! Form::open(array('route' => config('quickadmin.route') . '.scrappingadsdetails.store', 'id' => 'form-with-validation', 'role'=>'form', 'class' => 'form-horizontal')) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_title', 'ad_title', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_title', old('ad_title', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_ref', 'ad_ref', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_ref', old('ad_ref', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_type_cat_url', 'ad_type_cat_url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_type_cat_url', old('ad_type_cat_url', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_type_cat_name', 'ad_type_cat_name', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_type_cat_name', old('ad_type_cat_name', null), ['class'=>'form-control']) !!}
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
                        {!! Form::text('ad_manufacturer_name', old('ad_manufacturer_name', null), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_manufacturer_url', 'ad_manufacturer_url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_manufacturer_url', old('ad_manufacturer_url', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_model_name', 'ad_model_name', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_model_name', old('ad_model_name', null), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_model_url', 'ad_model_url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_model_url', old('ad_model_url', null), ['class'=>'form-control']) !!}
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
                        {!! Form::text('ad_price', old('ad_price', null), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_price_descr', 'ad_price_descr', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_price_descr', old('ad_price_descr', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_location', 'ad_location', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_location', old('ad_location', null), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_mooring_country', 'ad_mooring_country', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_mooring_country', old('ad_mooring_country', null), ['class'=>'form-control']) !!}
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
                        {!! Form::text('ad_dealer_name', old('ad_dealer_name', null), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_dealer_url', 'ad_dealer_url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_dealer_url', old('ad_dealer_url', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_phones', 'ad_phones', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_phones', old('ad_phones', null), ['class'=>'form-control']) !!}
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
                        {!! Form::text('ad_sale', old('ad_sale', null), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_sale_type_condition', 'ad_sale_type_condition', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_sale_type_condition', old('ad_sale_type_condition', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_sales_status', 'ad_sales_status', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_sales_status', old('ad_sales_status', null), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('ad_year_built', 'ad_year_built', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_year_built', old('ad_year_built', null), ['class'=>'form-control']) !!}
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
                        {!! Form::text('ad_width', old('ad_width', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_length', 'ad_length', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_length', old('ad_length', null), ['class'=>'form-control']) !!}
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
                        {!! Form::textarea('ad_description_full', old('ad_description_full', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            {{--<div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_description_caracts', 'ad_description_caracts', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_description_caracts', old('ad_description_caracts', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>--}}

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_description_caracts_labels', 'ad_description_caracts_labels', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_description_caracts_labels', old('ad_description_caracts_labels', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_description_caracts_values', 'ad_description_caracts_values', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_description_caracts_values', old('ad_description_caracts_values', null), ['class'=>'form-control']) !!}
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
                        {!! Form::textarea('ad_specifications_full', old('ad_specifications_full', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_specifications_caracts', 'ad_specifications_caracts', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_specifications_caracts', old('ad_specifications_caracts', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_specifications_caracts_values', 'ad_specifications_caracts_values', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_specifications_caracts_values', old('ad_specifications_caracts_values', null), ['class'=>'form-control']) !!}
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
                        {!! Form::textarea('ad_features_full', old('ad_features_full', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_features_caracts', 'ad_features_caracts', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_features_caracts', old('ad_features_caracts', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_features_caracts_categories', 'ad_features_caracts_categories', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_features_caracts_categories', old('ad_features_caracts_categories', null), ['class'=>'form-control']) !!}
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
                        {!! Form::text('ad_photo', old('ad_photo', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_photos_thumbs', 'ad_photos_thumbs', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_photos_thumbs', old('ad_photos_thumbs', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_photos', 'ad_photos', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_photos', old('ad_photos', null), ['class'=>'form-control']) !!}
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
                        {!! Form::text('ad_propulsion', old('ad_propulsion', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_nb_engines', 'ad_nb_engines', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ad_nb_engines', old('ad_nb_engines', null), ['class'=>'form-control']) !!}
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
                        {!! Form::text('ad_pageUrl', old('ad_pageUrl', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.scrappingadsdetails.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection