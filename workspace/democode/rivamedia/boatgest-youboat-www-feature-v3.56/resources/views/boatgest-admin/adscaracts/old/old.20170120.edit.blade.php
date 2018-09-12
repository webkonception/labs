<?php
    $label_txt_manufacturers = trans('filters.manufacturers') . '/' . trans('filters.shipyards');
    /*

    if (isset($adstype['rewrite_url'])) {
        if (preg_match('/engine/', $adstype['rewrite_url'])) {
            $engines_inputs_disabled        = true;
            $not_engines_inputs_disabled    = false;
            $label_txt_manufacturers = trans('filters.manufacturers_engines');
        } else {
            $engines_inputs_disabled        = false;
            $not_engines_inputs_disabled    = true;
        }
    } else {
        $engines_inputs_disabled        = false;
        $not_engines_inputs_disabled    = false;
    }
    */

    $country_id = !empty($AdsCaracts->countries_id) ? $AdsCaracts->countries_id : '';
    //$ad_country_code = !empty($AdsCaracts->country_code) ? $AdsCaracts->country_code : '';
    $ad_id = !empty($AdsCaracts->id) ? $AdsCaracts->id : '';
    $ad_referrer = !empty($AdsCaracts->ad_referrer) ? $AdsCaracts->ad_referrer : '';

    //var_dump($AdsCaracts->created_at->timestamp);
    //var_dump($AdsCaracts->updated_at->timestamp);
    $photos_missing = false;
    if(empty($AdsCaracts->ad_photos)) {
        $photos_missing = true;
        $message = trans('ads_caracts.photos_missing');
    }

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
    {!! Form::model($AdsCaracts, array('files' => true, 'class' => 'form-horizontal', 'id' => 'form-with-validation', 'role'=>'form', 'novalidate' => 'novalidate', 'files'=>true, 'method' => 'PATCH', 'route' => array(config('quickadmin.route') . '.adscaracts.update', $ad_id))) !!}

    {{-- {!! Form::hidden('ads_id', $ad_id) !!} --}}
    {!! Form::hidden('id', $ad_id) !!}
    {!! Form::hidden('ad_referrer', $ad_referrer) !!}
    {!! Form::hidden('ad_country_code', $AdsCaracts->ad_country_code) !!}
    {!! Form::hidden('ad_ref', $AdsCaracts->ad_ref) !!}
    {!! Form::hidden('dealerscaracts_id', $AdsCaracts->dealerscaracts_id) !!}

    @if($isAdmin || 'commercial' == Auth::user()->type)
    <section class="row well well-sm well-white">
        <div class="col-sm-12">
            <?php
                $input_name = 'ad_dealer_name';
                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                $label_txt = ucfirst(trans('validation.attributes.denomination'));
                $attributes = [
                        'readonly'=>'readonly',
                        'class' => 'form-control',
                        'id' => $input_name
                ];
                $css_state = '';
                if (!empty($input_value)) {
                    $css_state = 'has-success';
                }
                if ($errors->has($input_name)) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-4 control-label strong text-primary']) !!}
                <div class="col-xs-12 col-sm-8">
                    {!! Form::text($input_name, $input_value, $attributes) !!}
                </div>
            </div>
        </div>
    </section>
    @else
        {!! Form::hidden('ad_dealer_name', $AdsCaracts->ad_dealer_name) !!}
    @endif
    <section class="row well well-sm well-warning text-center">
        <div class="col-sm-12 col-md-6 text-primary lead">
            <span class="strong badge badge-primary">Created at</span>
            {!! $AdsCaracts->created_at->format('Y-m-d H:i:s') !!}
        </div>
        <div class="col-sm-12 col-md-6 text-success lead">
            <span class="strong badge badge-success">Updated at</span>
            {!! $AdsCaracts->updated_at->format('Y-m-d H:i:s') !!}
        </div>
    </section>

    <section class="row well well-sm well-success">
        <div class="col-sm-12">
            <?php
            $input_name = 'ad_title';
            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
            $input_value = cleanString($input_value);
            $label_txt = ucfirst(trans('validation.attributes.title'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'readonly'=>'readonly',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => $input_name
            ];
            $css_state = '';
            if (!empty($input_value)) {
                $css_state = 'has-success';
            }
            if ($errors->has($input_name)) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-2 control-label strong text-primary']) !!}
                <div class="col-xs-12 col-sm-10 lead">
                    {!! Form::hidden($input_name, $input_value) !!}
                    <legend>
                        {!! $input_value !!}
                    </legend>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <?php
            $input_name = 'ad_url';
            $website_youboat_url = !empty($website_name) ? $website_name : config('youboat.' . $country_code . '.website_youboat_url');

            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
            $input_value = empty($input_value) && !empty($ad_id) ? getEffectiveUrl(url($website_youboat_url . '/buy/type/manufacturer/model/' . $ad_id)) : old($input_name);

            $label_txt = ucfirst(trans('validation.attributes.url'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'readonly' => 'readonly',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => $input_name
            ];
            $css_state = '';
            if (!empty($input_value)) {
                $css_state = 'has-success';
            }
            if ($errors->has($input_name)) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-2 control-label strong text-primary']) !!}
                <div class="col-xs-12 col-sm-10">
                    <legend>
                        <a href="{!! $input_value !!}" class="blank" title="{!! $input_value !!}">...{!! substr($input_value, strlen($input_value) - 36, 36) !!}<i class="fa fa-external-link-square fa-fw"></i></a>
                    </legend>
                </div>
            </div>
        </div>
    </section>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" {!! !$photos_missing ? 'class="active"' : '' !!}><a href="#ad_s_details" aria-controls="ad_s_details" role="tab" data-toggle="tab">{!! ucfirst(trans('ads_caracts.ad_s_details')) !!} <span class="text-danger">*</span><i class="fa fa-filter fa-fw"></i></a></li>
        <li role="presentation"><a href="#description" aria-controls="description" role="tab" data-toggle="tab">{!! ucfirst(trans('validation.attributes.description')) !!}  <span class="text-danger">*</span><i class="fa fa-paragraph fa-fw"></i></a></li>
        <li role="presentation"><a href="#specifications" aria-controls="specifications" role="tab" data-toggle="tab">{!! ucfirst(trans('validation.attributes.specifications')) !!}<i class="fa fa-list-alt fa-fw"></i></a></li>
        <li role="presentation"><a href="#features" aria-controls="features" role="tab" data-toggle="tab">{!! ucfirst(trans('validation.attributes.features')) !!}<i class="fa fa-list fa-fw"></i></a></li>
        <li role="presentation" {!! $photos_missing ? 'class="active"' : '' !!}>
            <a {!! $photos_missing ? 'class="bg-danger"' : '' !!} href="#photos" aria-controls="photos" role="tab" data-toggle="tab">
                {!! ucfirst(trans('ads_caracts.photos')) !!}<i class="fa fa-picture-o fa-fw"></i>
                @if($photos_missing)
                <i class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i>
                @endif
            </a>
        </li>
        <li role="presentation"><a href="#address" aria-controls="address" role="tab" data-toggle="tab">{!! ucfirst(trans('validation.attributes.address')) !!} <span class="text-danger">*</span><i class="fa fa-map-marker fa-fw"></i></a></li>
        <li><a name="required"><span class="label label-danger">* {!! trans('filters.required') !!}</span></a></li>
    </ul>

    @if ($photos_missing && !empty($message))
    <div class="row" id="content">
        <div class="col-sm-12">
            <div class="note note-danger">
                <p class=" text-danger"><i class="fa fa-exclamation-circle fa-2x fa-fw text-danger" aria-hidden="true"></i>{!! $message !!}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane {!! !$photos_missing ? 'active' : '' !!}" id="ad_s_details">
            <div class="row well well-white">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row well clearfix">
                            <?php
                                $select_values = $sell_types;
                                $input_name = 'sell_type';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $label_txt = ucfirst(trans('filters.' . $input_name));
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'required' => 'required',
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                                </div>
                            </div>
                            <?php
                                $select_values = $status;
                                $input_name = 'status';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $label_txt = ucfirst($input_name);
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'required' => 'required',
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row well clearfix">
                            <?php
                                $country_locale_full = Search::getCountryLocaleFull($country_id);
                                $currency = is_array($country_locale_full) && array_key_exists('currency', $country_locale_full) ? $country_locale_full['currency'] : config('youboat.' . $country_code . '.currency');
                                $input_name = 'ad_price';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $input_value = cleanString($input_value);
                                $label_txt = ucfirst(trans('filters.price'));
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'required' => 'required',
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    <div class="input-group">
                                        {!! Form::text($input_name, $input_value, $attributes) !!}
                                        {!! Form::hidden('currency', $currency) !!}
                                        <span class="input-group-addon">{!! $currency !!}</span>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $input_name = 'ad_price_descr';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $input_value = cleanString($input_value);
                                $label_txt = ucfirst(trans('filters.price')) . ' Desc.';
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::text($input_name, $input_value, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row well well-white">
                <div class="well clearfix">
                    <div class="col-sm-6">
                        <?php
                            $select_values = $adstypes;
                            $input_name = 'adstypes_id';
                            $adstypes_id = $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $label_txt = ucfirst(trans('filters.adstype'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                'required' => 'required',
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-7">
                                {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php
                            $select_values = $categories;
                            $input_name = 'categories_ids';
                            $categories_ids = $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $label_txt = ucfirst(trans('filters.category'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                    'data-ajax--url'=>"categories",
                                    'data-parent' => !empty($adstypes_id) ? $adstypes_id : '',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-7">
                                {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php
                            $select_values = $subcategories;
                            $input_name = 'subcategories_ids';
                            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $label_txt = ucfirst(trans('filters.subcategory'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                    'data-ajax--url'=>"subcategories",
                                    'data-parent' => !empty($categories_ids) ? $categories_ids : '',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                            ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-7">
                                {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row well well-white">
                @if((isset($manufacturers) && !empty($manufacturers)) || (isset($models) && !empty($models)))
                <div class="well clearfix">
                    @if(isset($manufacturers))
                    <div class="col-sm-6">
                        <?php
                            $select_values = $manufacturers;
                            $input_name = 'manufacturers_id';
                            $manufacturers_id = $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);

                            ////
                            $manufacturer       = !empty($input_value) ? Search::getManufacturerById ($input_value) : null;
                            $select_values      = !empty($manufacturer['id']) ? [$manufacturer['id']=>$manufacturer['name']] : [];
                            $input_value        = !empty($manufacturer['id']) ? $manufacturer['id'] : $input_value;
                            ////

                            //$label_txt = ucfirst(trans('filters.manufacturer'));
                            $label_txt = $label_txt_manufacturers;
                            //$placeholder = trans('navigation.form_enter_placeholder');
                            $placeholder = $label_txt_manufacturers;

                            $attributes = [
                                    'data-ajax--url'=>"/ajax-manufacturer",
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-7">
                                {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(isset($models))
                    <div class="col-sm-6">
                        <?php
                            $select_values = $models;
                            $input_name = 'models_id';
                            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $label_txt = ucfirst(trans('filters.model'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                    'ajax-url' => "/ajax-models",
                                    'data-parent' => !empty($manufacturers_id) ? $manufacturers_id : '',
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-7">
                                {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                    @if((isset($manufacturersengines) && !empty($manufacturersengines)) || (isset($modelsengines) && !empty($modelsengines)))
                <div class="well clearfix">
                    @if(isset($manufacturersengines))
                    <div class="col-sm-6">
                        <?php
                            $select_values = $manufacturersengines;
                            $input_name = 'manufacturersengines_id';
                            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $label_txt = ucfirst(trans('filters.manufacturer_engines'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-7">
                                {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(isset($modelsengines))
                    <div class="col-sm-6">
                        <?php
                            $select_values = $modelsengines;
                            $input_name = 'modelsengines_id';
                            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $label_txt = ucfirst(trans('filters.model_engines'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-7">
                                {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <div class="row well well-white">
                <div class="well clearfix">
                    <div class="col-sm-6">
                        <?php
                            $input_name = 'ad_width_meter';
                            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $input_value = cleanString($input_value);
                            $label_txt = ucfirst(trans('filters.width')) . ' Meter';
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-7">
                                {!! Form::selectRange($input_name, 1, 6, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php
                            $input_name = 'ad_length_meter';
                            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $input_value = cleanString($input_value);
                            $label_txt = ucfirst(trans('filters.length')) . ' Meter';
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-7">
                                {!! Form::selectRange($input_name, 1, 16, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="well clearfix">
                    <div class="col-sm-6 col-md-5">
                        <?php
                            $input_name = 'ad_propulsion';
                            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $input_value = cleanString($input_value);
                            $label_txt = ucfirst(trans('filters.propulsion'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-7 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-5">
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <?php
                            $input_name = 'ad_nb_engines';
                            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $input_value = cleanString($input_value);
                            $label_txt = ucfirst(trans('filters.nb_engines'));
                            $placeholder = '';
                            $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-8 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-4">
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    @if(isset($years_built))
                    <div class="col-sm-6 col-md-4">
                        <?php
                            $years = [];
                            foreach ($years_built as $key => $value) {
                                $years[$key] = $key;
                            }
                            $select_values = $years;
                            $input_name = 'ad_year_built';
                            $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                            $label_txt = ucfirst(trans('filters.year_built'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($input_value)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                            }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-7 control-label strong text-primary']) !!}
                            <div class="col-xs-12 col-sm-5">
                                {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="description">
            <div class="row well well-white">
                <div class="panel-group" id="accordion_description" role="tablist" aria-multiselectable="true">
                    <?php
                        $input_name = 'ad_description';
                        $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                        $label_txt = ucfirst(trans('validation.attributes.description'));
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'contenteditable' => "true",
                                'required' => 'required',
                                'size' => '30x6',
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $css_state = '';
                        $collapse_in = '';
                        $collapsed = 'collapsed';
                        if (!empty($input_value)) {
                            $css_state = 'has-success';
                            $collapse_in = 'in';
                            $collapsed = '';
                        }
                        if ($errors->has($input_name)) {
                            $css_state = 'has-error';
                        }
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title strong text-primary">
                                <a class="{!! $collapsed !!}" role="button" data-toggle="collapse" data-parent="#accordion_description" href="#collapse_ad_description" aria-expanded="true" aria-controls="collapse_ad_description">
                                    <strong>{!! $label_txt !!}</strong>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_ad_description" class="panel-collapse collapse {!! $collapse_in !!}" role="tabpanel" aria-labelledby="heading_ad_description">
                            <div class="panel-body {!! $css_state !!}">
                                <div class="input-group">
                                    {!! Form::textarea($input_name, preg_replace('/\s/', ' ', $input_value), $attributes) !!}
                                    <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                        $inputsCaracts = [];
                        $input_name = 'ad_description_caracts_labels';
                        $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                        $label_txt = ucfirst(trans('validation.attributes.description')) . ' ' . trans('validation.attributes.caracts_labels');
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $css_state = '';
                        $ad_description_caracts_labels = '';
                        if (!empty($input_value)) {
                            $css_state = 'has-success';
                            $ad_description_caracts_labels = explode(';', $input_value);
                        }
                        if ($errors->has($input_name) || empty($input_value)) {
                            $css_state = 'has-error';
                        }

                        //$inputsCaractsLabel = Form::label('', $label_txt, ['class'=>'control-label strong text-primary']);
                        if (isset($ad_description_caracts_labels) && is_array($ad_description_caracts_labels)) {
                            foreach($ad_description_caracts_labels as $key => $caracts_label) {
                                if(!empty($caracts_label)) {
                                    $caracts_label = trim(preg_replace('!\s+!', ' ', preg_replace('/:/', '', $caracts_label)));
                                    //$inputsCaractsLabel .= Form::text('ad_description_caracts_labels' . '[]', $caracts_label, $attributes) . "\n";
                                    $inputsCaracts[$key]['label'] = $caracts_label;
                                }
                            }
                        }

                        $input_name = 'ad_description_caracts_values';
                        $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                        $label_txt = ucfirst(trans('validation.attributes.description')) . ' ' . trans('validation.attributes.caracts_values');
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $css_state = '';
                        $ad_description_caracts_values = '';

                        if (!empty($input_value)) {
                            $css_state = 'has-success';
                            $ad_description_caracts_values = explode(';', $input_value);
                        }
                        if ($errors->has($input_name) || empty($input_value)) {
                            $css_state = 'has-error';
                        }
                        //$inputsCaractsValue = Form::label('', $label_txt, ['class'=>'control-label strong text-primary']);
                        if (isset($ad_description_caracts_values) && is_array($ad_description_caracts_values)) {
                            foreach($ad_description_caracts_values as $key => $caracts_value) {
                                if(!empty($caracts_value)) {
                                    $caracts_value = trim(preg_replace('!\s+!', ' ', preg_replace('/:/', '', $caracts_value)));
                                    //$inputsCaractsValue .= Form::text('ad_description_caracts_values' . '[]', $caracts_value, $attributes) . "\n";
                                    $inputsCaracts[$key]['value'] = $caracts_value;
                                }
                            }
                        }

                        $collapse_in = '';
                        $collapsed = 'collapsed';

                        if(count($inputsCaracts)>0) {
                            $collapse_in = 'in';
                            $collapsed = '';
                        }
                    ?>
                    <?php
                    //$caracts_type = 'ad_description';
                    $caracts_type = 'description';
                    $label_caracts_labels = ucfirst(trans('validation.attributes.description')) . ' ' . trans('validation.attributes.caracts_labels');
                    $label_caracts_values = ucfirst(trans('validation.attributes.description')) . ' ' . trans('validation.attributes.caracts_values');
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title strong text-primary">
                                <a class="{!! $collapsed !!}" role="button" data-toggle="collapse" data-parent="#accordion_description" href="#collapse_ad_description_caracts_labels" aria-expanded="true" aria-controls="collapse_ad_description_caracts_labels">
                                    <strong>{!! ucfirst(trans('navigation.add') . ' ' . trans('validation.attributes.description')) !!} attributes</strong>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_ad_description_caracts_labels" class="panel-collapse collapse {!! $collapse_in !!}" role="tabpanel" aria-labelledby="heading_ad_description_caracts_labels">
                            <div class="panel-body">
                                @include('boatgest-admin.adscaracts.blocks.caracts-labels-values-block', compact($caracts_type, $label_caracts_labels, $label_caracts_values, $inputsCaracts))
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="specifications">
            <div class="row well well-white">
                <div class="panel-group" id="accordion_specifications" role="tablist" aria-multiselectable="true">
                    <?php
                        $input_name = 'ad_specifications';
                        $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                        $label_txt = ucfirst(trans('validation.attributes.specifications'));
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'contenteditable' => "true",
                                'size' => '30x6',
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $css_state = '';
                        $collapse_in = '';
                        $collapsed = 'collapsed';
                        if (!empty($input_value)) {
                            $css_state = 'has-success';
                            $collapse_in = 'in';
                            $collapsed = '';
                        }
                        if ($errors->has($input_name)) {
                            $css_state = 'has-error';
                        }
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title strong text-primary">
                                <a class="{!! $collapsed !!}" role="button" data-toggle="collapse" data-parent="#accordion_specifications" href="#collapse_ad_specifications" aria-expanded="false" aria-controls="collapse_ad_specifications">
                                    <strong>{!! $label_txt !!}</strong>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_ad_specifications" class="panel-collapse collapse {!! $collapse_in !!}" role="tabpanel" aria-labelledby="heading_ad_specifications">
                            <div class="panel-body {!! $css_state !!}">
                                <div class="input-group">
                                    {!! Form::textarea($input_name, preg_replace('/\s/', ' ', $input_value), $attributes) !!}
                                    <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                        $inputsCaracts = [];
                        $input_name = 'ad_specifications_caracts_labels';
                        $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                        $label_txt = ucfirst(trans('validation.attributes.specifications')) . ' ' . trans('validation.attributes.caracts_labels');
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $css_state = '';
                        $ad_specifications_caracts_labels = '';
                        if (!empty($input_value)) {
                            $css_state = 'has-success';
                            $ad_specifications_caracts_labels = explode(';', $input_value);
                        }
                        if ($errors->has($input_name) || empty($input_value)) {
                            $css_state = 'has-error';
                        }

                        //$inputsCaractsLabel = Form::label('', $label_txt, ['class'=>'control-label strong text-primary']);
                        if (isset($ad_specifications_caracts_labels) && is_array($ad_specifications_caracts_labels)) {
                            foreach($ad_specifications_caracts_labels as $key => $caracts_label) {
                                if(!empty($caracts_label)) {
                                    $caracts_label = trim(preg_replace('!\s+!', ' ', preg_replace('/:/', '', $caracts_label)));
                                    //$inputsCaractsLabel .= Form::text('ad_specifications_caracts_labels' . '[]', $caracts_label, $attributes) . "\n";
                                    $inputsCaracts[$key]['label'] = $caracts_label;
                                }
                            }
                        }

                        $input_name = 'ad_specifications_caracts_values';
                        $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                        $label_txt = ucfirst(trans('validation.attributes.specifications')) . ' ' . trans('validation.attributes.caracts_values');
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $css_state = '';
                        $ad_specifications_caracts_values = '';
                        if (!empty($input_value)) {
                            $css_state = 'has-success';
                            $ad_specifications_caracts_values = explode(';', $input_value);
                        }
                        if ($errors->has($input_name) || empty($input_value)) {
                            $css_state = 'has-error';
                        }
                        //$inputsCaractsValue = Form::label('', $label_txt, ['class'=>'control-label strong text-primary']);
                        if (isset($ad_specifications_caracts_values) && is_array($ad_specifications_caracts_values)) {
                            foreach($ad_specifications_caracts_values as $key => $caracts_value) {
                                if(!empty($caracts_value)) {
                                    $caracts_value = trim(preg_replace('!\s+!', ' ', preg_replace('/:/', '', $caracts_value)));
                                    //$inputsCaractsValue .= Form::text('ad_specifications_caracts_values' . '[]', $caracts_value, $attributes) . "\n";
                                    $inputsCaracts[$key]['value'] = $caracts_value;
                                }
                            }
                        }

                        $collapse_in = '';
                        $collapsed = 'collapsed';

                        if(count($inputsCaracts)>0) {
                            $collapse_in = 'in';
                            $collapsed = '';
                        }
                    ?>
                    <?php
                    //$caracts_type = 'ad_specifications';
                    $caracts_type = 'specifications';
                    $label_caracts_labels = ucfirst(trans('validation.attributes.specifications')) . ' ' . trans('validation.attributes.caracts_labels');
                    $label_caracts_values = ucfirst(trans('validation.attributes.specifications')) . ' ' . trans('validation.attributes.caracts_values');
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title strong text-primary">
                                <a class="{!! $collapsed !!}" role="button" data-toggle="collapse" data-parent="#accordion_specifications" href="#collapse_ad_specifications_caracts_labels" aria-expanded="true" aria-controls="collapse_ad_specifications_caracts_labels">
                                    <strong>{!! ucfirst(trans('navigation.add') . ' ' . trans('validation.attributes.specifications')) !!} attributes</strong>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_ad_specifications_caracts_labels" class="panel-collapse collapse {!! $collapse_in !!}" role="tabpanel" aria-labelledby="heading_ad_specifications_caracts_labels">
                            <div class="panel-body">
                                @include('boatgest-admin.adscaracts.blocks.caracts-labels-values-block', compact($caracts_type, $label_caracts_labels, $label_caracts_values, $inputsCaracts))
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="features">
            <div class="row well well-white">
                <div class="panel-group" id="accordion_features" role="tablist" aria-multiselectable="true">
                    <?php
                        $input_name = 'ad_features';
                        $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                        $label_txt = ucfirst(trans('validation.attributes.features'));
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'contenteditable' => "true",
                                'size' => '30x6',
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $css_state = '';
                        $collapse_in = '';
                        $collapsed = 'collapsed';
                        if (!empty($input_value)) {
                            $css_state = 'has-success';
                            $collapse_in = 'in';
                            $collapsed = '';
                        }
                        if ($errors->has($input_name)) {
                            $css_state = 'has-error';
                        }
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title strong text-primary">
                                <a class="{!! $collapsed !!}" role="button" data-toggle="collapse" data-parent="#accordion_features" href="#collapse_ad_features" aria-expanded="true" aria-controls="collapse_ad_features">
                                    <strong>{!! $label_txt !!}</strong>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_ad_features" class="panel-collapse collapse {!! $collapse_in !!}" role="tabpanel" aria-labelledby="heading_ad_features">
                            <div class="panel-body {!! $css_state !!}">
                                <div class="input-group">
                                    {!! Form::textarea($input_name, preg_replace('/\s/', ' ', $input_value), $attributes) !!}
                                    <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                        $inputsCaracts = [];
                        $input_name = 'ad_features_caracts_categories';
                        $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old('ad_features_caracts_labels');
                        $input_name = 'ad_features_caracts_labels';
                        $label_txt = ucfirst(trans('validation.attributes.features')) . ' ' . trans('validation.attributes.caracts_labels');
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $css_state = '';
                        $ad_features_caracts_labels = '';
                        if (!empty($input_value)) {
                            $css_state = 'has-success';
                            $ad_features_caracts_labels = explode(';', $input_value);
                        }
                        if ($errors->has($input_name) || empty($input_value)) {
                            $css_state = 'has-error';
                        }

                        //$inputsCaractsLabel = Form::label('', $label_txt, ['class'=>'control-label strong text-primary']);
                        if (isset($ad_features_caracts_labels) && is_array($ad_features_caracts_labels)) {
                            foreach($ad_features_caracts_labels as $key => $caracts_label) {
                                if(!empty($caracts_label)) {
                                    $caracts_label = trim(preg_replace('!\s+!', ' ', preg_replace('/:/', '', $caracts_label)));
                                    //$inputsCaractsLabel .= Form::text('ad_features_caracts_labels' . '[]', $caracts_label, $attributes) . "\n";
                                    $inputsCaracts[$key]['label'] = $caracts_label;
                                }
                            }
                        }

                        $input_name = 'ad_features_caracts_values';
                        $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                        $label_txt = ucfirst(trans('validation.attributes.features')) . ' ' . trans('validation.attributes.caracts_values');
                        $placeholder = trans('navigation.form_enter_placeholder');
                        $attributes = [
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $css_state = '';
                        $ad_features_caracts_values = '';
                        if (!empty($input_value)) {
                            $css_state = 'has-success';
                            $ad_features_caracts_values = explode(';', $input_value);
                        }
                        if ($errors->has($input_name) || empty($input_value)) {
                            $css_state = 'has-error';
                        }
                        //$inputsCaractsValue = Form::label('', $label_txt, ['class'=>'control-label strong text-primary']);
                        if (isset($ad_features_caracts_values) && is_array($ad_features_caracts_values)) {
                            foreach($ad_features_caracts_values as $key => $caracts_value) {
                                if(!empty($caracts_value)) {
                                    $caracts_value = trim(preg_replace('!\s+!', ' ', preg_replace('/:/', '', $caracts_value)));
                                    //$inputsCaractsValue .= Form::text('ad_features_caracts_values' . '[]', $caracts_value, $attributes) . "\n";
                                    $inputsCaracts[$key]['value'] = $caracts_value;
                                }
                            }
                        }

                        $collapse_in = '';
                        $collapsed = 'collapsed';

                        if(count($inputsCaracts)>0) {
                            $collapse_in = 'in';
                            $collapsed = '';
                        }
                    ?>
                    <?php
                    //$caracts_type = 'ad_features';
                    $caracts_type = 'features';
                    $label_caracts_labels = ucfirst(trans('validation.attributes.features')) . ' ' . trans('validation.attributes.caracts_labels');
                    $label_caracts_values = ucfirst(trans('validation.attributes.features')) . ' ' . trans('validation.attributes.caracts_values');
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title strong text-primary">
                                <a class="{!! $collapsed !!}" role="button" data-toggle="collapse" data-parent="#accordion_features" href="#collapse_ad_features_caracts_labels" aria-expanded="true" aria-controls="collapse_ad_features_caracts_labels">
                                    <strong>{!! ucfirst(trans('navigation.add') . ' ' . trans('validation.attributes.features')) !!} attributes</strong>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_ad_features_caracts_labels" class="panel-collapse collapse {!! $collapse_in !!}" role="tabpanel" aria-labelledby="heading_ad_features_caracts_labels">
                            <div class="panel-body">
                                @include('boatgest-admin.adscaracts.blocks.caracts-labels-values-block', compact($caracts_type, $label_caracts_labels, $label_caracts_values, $inputsCaracts))
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane {!! $photos_missing ? 'active' : '' !!}" id="photos">
            <div class="row well well-white">
                <?php
                    $ad_id = !empty($ad_id) ? $ad_id : time();
                    $ad_title = !empty($AdsCaracts->ad_title) ? $AdsCaracts->ad_title : time();
                    $input_name = 'ad_photos';
                    //$input_value = !empty($AdsCaracts->ad_photos) ? $AdsCaracts->ad_photos : (!empty(old($input_name)) ? old($input_name) : '');
                    $input_value = !empty(old($input_name)) ? old($input_name) : (!empty($AdsCaracts->ad_photos) ? $AdsCaracts->ad_photos : '');
                    //$input_value = !empty($AdsCaracts->ad_photos) ? $AdsCaracts->ad_photos : old($input_name);
                ?>
                @include('boatgest-admin.adscaracts.blocks.additional-images-block', ['ad_country_code'=>$AdsCaracts->ad_country_code, 'ad_id'=>$ad_id, 'ad_referrer'=>$ad_referrer, 'ad_title'=>$ad_title, 'input_name'=>$input_name, 'input_value'=>$input_value ])
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="address">
            <div class="row well well-white">
                <div class="well clearfix">
                    <div class="row">
                        <div class="col-sm-6">
                        @if (isset($countries))
                            <?php
                                $input_name = 'country_code';
                                if(isset($AdsCaracts->$input_name)) {
                                    $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                }
                                $input_name = 'countries_id';
                                $input_value = !empty($input_value) ? $input_value : old($input_name);
                                $label_txt = ucfirst(trans('validation.attributes.country'));
                                $attributes = [
                                        'required' => 'required',
                                        'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                                        'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                        'class' => 'form-control countries select2',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!count($countries) > 0) {
                                    $attributes['disabled'] = 'disabled';
                                    $css_state .= 'collapse ';
                                }
                                if (!empty($input_value) || count($countries) === 1) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::select($input_name, $countries, $input_value, $attributes) !!}
                                </div>
                            </div>
                        @endif
                            <?php
                                $input_name = 'city';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $input_value = cleanString($input_value);
                                $label_txt = ucfirst(trans('validation.attributes.' . $input_name));
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::text($input_name, $input_value, $attributes) !!}
                                </div>
                            </div>
                            <?php
                                $input_name = 'zip';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $input_value = cleanString($input_value);
                                $label_txt = ucfirst(trans('validation.attributes.' . $input_name));
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::text($input_name, $input_value, $attributes) !!}
                                </div>
                            </div>

                            <?php
                                $input_name = 'ad_phones';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $_input_value = explode(';', $input_value);
                                if(is_array($_input_value)) {
                                    $input_value = $_input_value[0];
                                }
                                $input_value = preg_replace('/\s/', '', cleanString($input_value));
                                $label_txt = ucfirst(trans('validation.attributes.phone'));
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'required' => 'required',
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::text($input_name, $input_value, $attributes) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?php
                                $input_name = 'province';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $input_value = cleanString($input_value);
                                $label_txt = ucfirst(trans('validation.attributes.' . $input_name));
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::text($input_name, $input_value, $attributes) !!}
                                </div>
                            </div>
                            <?php
                                $input_name = 'region';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $input_value = cleanString($input_value);
                                $label_txt = ucfirst(trans('validation.attributes.' . $input_name));
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::text($input_name, $input_value, $attributes) !!}
                                </div>
                            </div>
                            <?php
                                $input_name = 'subregion';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                $input_value = cleanString($input_value);
                                $label_txt = ucfirst(trans('validation.attributes.' . $input_name));
                                $placeholder = trans('navigation.form_enter_placeholder');
                                $attributes = [
                                        'data-placeholder' => $placeholder,
                                        'placeholder' => $placeholder,
                                        'class' => 'form-control',
                                        'id' => $input_name
                                ];
                                $css_state = '';
                                if (!empty($input_value)) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    {!! Form::text($input_name, $input_value, $attributes) !!}
                                </div>
                            </div>
                            @if (isset($countries))
                            <?php
                                $input_name = 'ad_mooring_country';
                                $input_value = !empty($AdsCaracts->$input_name) ? $AdsCaracts->$input_name : old($input_name);
                                if(strlen($input_value)>2) {
                                    $getCountry = Search::getCountry($input_value, true);
                                    if(is_array($getCountry) && array_key_exists('code', $getCountry)) {
                                        $input_value = $getCountry['code'];
                                    }
                                }
                                $label_txt = ucfirst(trans('ads_caracts.mooring'));
                                $attributes_txt = [
                                    'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                    'class' => 'form-control',
                                    'id' => 'old_' . $input_name
                                ];
                                $attributes = [
                                    'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                    'class' => 'form-control countries select2',
                                    'id' => $input_name
                                ];
                                $css_state = '';
                                if (!count($countries) > 0) {
                                    $attributes['disabled'] = 'disabled';
                                    $css_state .= 'collapse ';
                                }
                                if (!empty($input_value) || count($countries) === 1) {
                                    $css_state = 'has-success';
                                }
                                if ($errors->has($input_name)) {
                                    $css_state = 'has-error';
                                }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                                <div class="col-xs-12 col-sm-7">
                                    @if(isset($getCountry) && is_array($getCountry) && !array_key_exists('code', $getCountry))
                                    {!! Form::text('old_' . $input_name, $input_value, $attributes_txt) !!}
                                    @endif
                                    {!! Form::select($input_name, $countries, $input_value, $attributes) !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="col-sm-12 text-center">
            {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . ucfirst(trans('navigation.update')), ['type' => 'submit', 'class' => 'btn btn-lg btn-success btn-exception']) !!}
            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.adscaracts.index', '<i class="fa fa-ban fa-fw"></i>' . ucfirst(trans('navigation.cancel')), [], ['class' => 'btn btn-default pull-right'])) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection

@section('javascript')
    {{--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
    <script>
        var placeholder_txt = '{!! trans('navigation.form_enter_placeholder') !!}';
        var delete_txt = '{!! trans('navigation.delete') !!}';
        var reload_txt = '{!! trans('navigation.reload') !!}';
    </script>
    <script src="{{ asset('assets/vendor/boatgest-admin/js/caracts.js') }}"></script>
    <script src="{{ asset('assets/vendor/youboat/js/filters_search.js') }}"></script>
    <script src="{{ asset('assets/vendor/youboat/js/filters_recovery.js') }}"></script>
@endsection
