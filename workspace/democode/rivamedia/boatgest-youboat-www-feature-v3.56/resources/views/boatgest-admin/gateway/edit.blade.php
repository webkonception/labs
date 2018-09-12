<?php
    $ad_referrer = $scrapping_ads_details->ad_referrer ?: '';
    $ad_country_code = $scrapping_ads_details->ad_country_code ?: '';
    $ad_pageUrl = $scrapping_ads_details->ad_pageUrl ?: '';

    $ad_type = '';
    $ad_category = '';
    //if (preg_match("/http:/i", $ad_pageUrl)) {
//      $ad_title_rewrite_url = '';
    $ad_id = '';
    $ad_type_cat_url = $scrapping_ads_details->ad_type_cat_url ?: '';
    if($ad_type_cat_url) {
        list($nothing, $ad_type, $ad_category) = explode("/", $ad_type_cat_url);
    }
?>
@if($ad_type)
@else
<?php
    //$ad_pageUrl = str_replace('http://'.$row->ad_referrer . '/', '', $ad_pageUrl);
    /*
    $pageUrl = parse_url($ad_pageUrl);
    if($pageUrl) {
        list($ad_type, $ad_title_rewrite_url, $ad_id) = explode("/", $pageUrl['path']);
    }
    */
    $ad_pageUrl_cleaned = preg_replace("@^https?://[^/]+/@", "", $ad_pageUrl);
    if($ad_pageUrl_cleaned) {
        list($ad_type, $ad_title_rewrite_url, $ad_id) = explode("/", $ad_pageUrl_cleaned);
    }
    //}
?>
@endif
@if($ad_type)
<?php
$arrayFrom = ['Boat-Moorings','commercials','motorboats','motorboat','sailing','sailboat','Small-boats'];
$arrayReplace = ['pontoon-mooring','commercial-boats','power-boats','power-boats','sailing-boats','sailing-boats','ribs'];
$ad_type = str_replace($arrayFrom, $arrayReplace, $ad_type);
?>
@endif
@if($ad_category)
<?php
    //trailer
    if (preg_match("/trailer/i", $ad_category) || preg_match("/boattrailer/i", $ad_category) || preg_match("/boat-trailer/i", $ad_category)) {
        if('power-boats' == $ad_type || 'sailing-boats' == $ad_type ) {
            $ad_category = $ad_type;
            $ad_type = 'boat-trailers';
        }
    }
    //jet-skis
    if (preg_match("/jet-ski/i", $ad_category) || preg_match("/jetski/i", $ad_category)) {
        if('power-boats' == $ad_type || 'ribs' == $ad_type) {
            $ad_category = 'jet-skis';
            $ad_type = 'pwc';
        }
    }
    //house-boats
    if (preg_match("/houseboat/i", $ad_category) || preg_match("/house-boat/i", $ad_category)) {
        if('power-boats' == $ad_type || 'ribs' == $ad_type) {
            $ad_category = '';
            $ad_type = 'house-boats';
        }
    }
    //other
    if (preg_match("/Center-Consoles/i", $ad_category)) {
        if('power-boats' == $ad_type || 'ribs' == $ad_type) {
            $ad_type = 'other';
        }
    }
    //-motorboats$
    if (preg_match("/-motorboats$/i", $ad_category) || preg_match("/-motor-boats$/i", $ad_category)) {
        if('power-boats' == $ad_type) {
            $arrayFrom = ['-motorboats','-motor-boats'];
            $ad_category = str_replace($arrayFrom, '', $ad_category);
        }
    }
    //-sailboats$
    if (preg_match("/-sailing-boats$/i", $ad_category) || preg_match("/-sailboats$/i", $ad_category) || preg_match("/-sailingboats$/i", $ad_category)) {
        if('sailing-boats' == $ad_type) {
            $arrayFrom = ['-sailing-boats','-sailboats','-sailingboats'];
            $ad_category = str_replace($arrayFrom, '', $ad_category);
        }
    }
    //dive
    if (preg_match("/dive/i", $ad_category)) {
        if('ribs' == $ad_type) {
            $ad_category = 'diving-boats';
        }
    }
    //^rib-
    if (preg_match("/^rib-/i", $ad_category)) {
        if('ribs' == $ad_type) {
            $arrayFrom = ['rib-'];
            $ad_category = str_replace($arrayFrom, 'ribs-', $ad_category);
        }
    }
    //^taxi-
    if (preg_match("/^taxi-/i", $ad_category)) {
        if('commercial-boats' == $ad_type) {
            $arrayFrom = ['taxi-'];
            $ad_category = str_replace($arrayFrom, 'taxis-', $ad_category);
        }
    }
    //-boat$
    if (preg_match("/-boat$/i", $ad_category)) {
        $arrayFrom = ['-boat'];
        $ad_category = str_replace($arrayFrom, '-boats', $ad_category);
    }
    //-for-Sale$
    if (preg_match("/-for-sale$/i", $ad_category)) {
        $arrayFrom = ['-for-sale'];
        $ad_category = str_replace($arrayFrom, '', $ad_category);
    }

    $ad_type = strtolower($ad_type);
    $ad_category = strtolower($ad_category);
?>
@endif

<?php
    $ad_model_name = $scrapping_ads_details->ad_model_name ?: '';
    if ($ad_model_name) {
        //$ad_model_name = $scrapping_ads_details->ad_model_name;
        list($ad_model_name) = explode(";", $ad_model_name);
    }
?>

<?php
    $ad_title = $scrapping_ads_details->ad_title ?: '';
    $ad_ref = $scrapping_ads_details->ad_ref ?: '';
    $ad_type_cat_name = $scrapping_ads_details->ad_type_cat_name ?: '';
    $ad_manufacturer_name = $scrapping_ads_details->ad_manufacturer_name ?: '';
    $ad_manufacturer_url = $scrapping_ads_details->ad_manufacturer_url ?: '';
    $ad_model_url = $scrapping_ads_details->ad_model_url ?: '';
    $ad_price = $scrapping_ads_details->ad_price ?: '';
    $ad_price_descr = $scrapping_ads_details->ad_price_descr ?: '';
?>

<?php
    $ad_location = '';
    $ad_country = '';
    $ad_region = '';
    $ad_county = '';
    $ad_city = '';
    $ad_location = $scrapping_ads_details->ad_location ?: '';

    if ($ad_location) {
        //$ad_location = $scrapping_ads_details->ad_location;
        $explode = explode(",", $ad_location);
        if (count($explode) > 0) {
            //list($region,$county,$country) = explode(";", $ad_location;
            if(is_array($explode)) {
                $ad_country = array_pop($explode);
            }
            if(is_array($explode)) {
                $ad_county = array_pop($explode);
            }
            if(is_array($explode)) {
                $ad_region = array_pop($explode);
            }
        }
    }
?>

<?php
    $ad_mooring_country = $scrapping_ads_details->ad_mooring_country ?: '';

    $ad_dealer_name = $scrapping_ads_details->ad_dealer_name ?: '';
    $ad_dealer_url = $scrapping_ads_details->ad_dealer_url ?: '';

    $ad_phones = $scrapping_ads_details->ad_phones ?: '';

    $ad_sale = $scrapping_ads_details->ad_sale ?: '';
    $ad_sale_type_condition = $scrapping_ads_details->ad_sale_type_condition ?: '';
    $ad_sales_status = $scrapping_ads_details->ad_sales_status ?: '';

    $ad_year_built = $scrapping_ads_details->ad_year_built ?: '';

    $ad_width = $scrapping_ads_details->ad_width ?: '';
    $ad_width_meter = '';
    if($ad_width) {
        list($ad_width_meter) = explode("/", $ad_width);
        $ad_width_meter = str_replace(' m', '', $ad_width_meter);
    }
    $ad_length = $scrapping_ads_details->ad_length ?: '';
    $ad_length_meter = '';
    if($ad_length) {
        list($ad_length_meter) = explode("/", $ad_length);
        $ad_length_meter = str_replace(' m', '', $ad_length_meter);
    }

    $ad_description_caracts = $scrapping_ads_details->ad_description_caracts ?: '';
    $ad_description_full = $scrapping_ads_details->ad_description_full ?: '';
    $ad_description_caracts_values = $scrapping_ads_details->ad_description_caracts_values ?: '';
    $ad_description_caracts_labels = $scrapping_ads_details->ad_description_caracts_labels ?: '';

        $ad_description = str_replace($ad_description_caracts, '', $ad_description_full);
        $ad_description_caracts_values = str_replace('; ', ';', $ad_description_caracts_values) . ';';
        $ad_description_caracts_labels = str_replace(':; ', ';', $ad_description_caracts_labels) . ';';

    $ad_specifications_caracts = $scrapping_ads_details->ad_specifications_caracts ?: '';
    $ad_specifications_full = $scrapping_ads_details->ad_specifications_full ?: '';
        $ad_specifications = str_replace($ad_specifications_caracts, '', $ad_specifications_full);

    $ad_specifications_caracts_values = $scrapping_ads_details->ad_specifications_caracts_values ?: '';

        $ad_specifications_caracts_labels = '';
        //$ad_specifications_caracts_values = $scrapping_ads_details->ad_specifications_caracts_values;
        if($ad_specifications_caracts) {
            //$ad_specifications_caracts = $scrapping_ads_details->ad_specifications_caracts;
            if($ad_specifications_caracts_values) {
                $ad_specifications_caracts_values_array = explode(";", $ad_specifications_caracts_values);
            }
            if(is_array($ad_specifications_caracts_values_array)) {
                $ad_specifications_caracts_labels = $ad_specifications_caracts;
                foreach($ad_specifications_caracts_values_array as $key => $value) {
                    $ad_specifications_caracts_labels = str_replace($value, ';', $ad_specifications_caracts_labels);
                }
                $ad_specifications_caracts_labels = str_replace('; ', ';', $ad_specifications_caracts_labels);
                $ad_specifications_caracts_values = str_replace('; ', ';', $ad_specifications_caracts_values) . ';';
            }
        }

    $ad_features_caracts = $scrapping_ads_details->ad_features_caracts ?: '';
    $ad_features_full = $scrapping_ads_details->ad_features_full ?: '';
        $ad_features = str_replace($ad_features_caracts, '', $ad_features_full);

    $ad_features_caracts_categories = $scrapping_ads_details->ad_features_caracts_categories ?: '';

        $ad_features_caracts_values = '';
        if($ad_features_caracts) {
            //$ad_features_caracts = $scrapping_ads_details->ad_features_caracts;
            if($ad_features_caracts_categories) {
                $ad_features_caracts_categories_array = explode(";", $ad_features_caracts_categories);
            }
            if(is_array($ad_features_caracts_categories_array)) {
                $ad_features_caracts_values = $ad_features_caracts;
                foreach($ad_features_caracts_categories_array as $key => $value) {
                    $ad_features_caracts_values = str_replace($value . ' ', '', $ad_features_caracts_values);
                }
                $ad_features_caracts_values .= ';';
                $ad_features_caracts_categories = $ad_features_caracts_categories . ';';
            }
        }

    $ad_photo = $scrapping_ads_details->ad_photo ?: '';
    $ad_photos_thumbs = $scrapping_ads_details->ad_photos_thumbs ?: '';
    $ad_photos = $scrapping_ads_details->ad_photos ?: '';

    $ad_propulsion = $scrapping_ads_details->ad_propulsion ?: '';
    $ad_nb_engines = $scrapping_ads_details->ad_nb_engines ?: '';
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

    {!! Form::model($scrapping_ads_details, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'role'=>'form', 'method' => 'PATCH', 'route' => array(config('quickadmin.route') . '.scrappingadsdetails.update', $scrapping_ads_details->id))) !!}
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
                    <?php
                    $status = !empty($user->status) ? $user->status : old('status', $user->status);
                    $default = ($status == 'active') ? 'inactive' : 'active';
                    ?>
                    {!! Form::label('switch_status', 'Status', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8 material-switch">
                        {!! Form::checkbox('switch_status', $status, ($status == 'active') ? 'checked' : '', ['class'=>'switch', 'data-target'=>'status', 'data-default'=>$default]) !!}
                        <label for="switch_status" class="label-success"></label>
                        <span></span>
                        {!! Form::hidden('status', $status, ['class'=>'form-control', 'id'=>'status']) !!}
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

            {{--<div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_specifications_caracts', 'ad_specifications_caracts', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_specifications_caracts', old('ad_specifications_caracts', $ad_specifications_caracts), ['class'=>'form-control', 'id'=>'ad_specifications_caracts']) !!}
                    </div>
                </div>
            </div>--}}
            <?php
            // ===
            /*
            $ad_specifications_caracts_labels = '';
            $ad_specifications_caracts_values = $scrapping_ads_details->ad_specifications_caracts_values;
            if($scrapping_ads_details->ad_specifications_caracts) {
                $ad_specifications_caracts = $scrapping_ads_details->ad_specifications_caracts;
                if($scrapping_ads_details->ad_specifications_caracts_values) {
                    $ad_specifications_caracts_values = explode(";", $scrapping_ads_details->ad_specifications_caracts_values);
                }
                if($ad_specifications_caracts_values) {
                    $ad_specifications_caracts_labels = $ad_specifications_caracts;
                    foreach($ad_specifications_caracts_values as $key => $value) {
                        $ad_specifications_caracts_labels = str_replace($value, ';', $ad_specifications_caracts_labels);
                    }
                    $ad_specifications_caracts_labels = str_replace('; ', ';', $ad_specifications_caracts_labels);
                    $ad_specifications_caracts_values = str_replace('; ', ';', $scrapping_ads_details->ad_specifications_caracts_values) . ';';
                }
            }
            */
            ?>


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

            {{--<div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('ad_features_caracts', 'ad_features_caracts', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::textarea('ad_features_caracts', old('ad_features_caracts', $ad_features_caracts), ['class'=>'form-control', 'id'=>'ad_features_caracts']) !!}
                    </div>
                </div>
            </div>--}}
            <?php
                //==
                /*
                $ad_features_caracts_categories = $scrapping_ads_details->ad_features_caracts_categories;
                $ad_features_caracts_values = '';
                if($scrapping_ads_details->ad_features_caracts) {
                    $ad_features_caracts = $scrapping_ads_details->ad_features_caracts;
                    if($scrapping_ads_details->ad_features_caracts_categories) {
                        $ad_features_caracts_categories = explode(";", $scrapping_ads_details->ad_features_caracts_categories);
                    }
                    if($ad_features_caracts_categories) {
                        $ad_features_caracts_values = $ad_features_caracts;
                        foreach($ad_features_caracts_categories as $key => $value) {
                            $ad_features_caracts_values = str_replace($value . ' ', '', $ad_features_caracts_values);
                        }
                        $ad_features_caracts_values .= ';';
                        $ad_features_caracts_categories = $scrapping_ads_details->ad_features_caracts_categories . ';';
                    }
                }
                */
            ?>
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