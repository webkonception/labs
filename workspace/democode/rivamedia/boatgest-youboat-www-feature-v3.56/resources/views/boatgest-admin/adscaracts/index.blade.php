<?php
    $superUser = ('admin' == Auth::user()->type || 'commercial' == Auth::user()->type) ? true : false;
    //$superUser = true;
    $_countryCode = config('youboat.' . $country_code . '.country_code') ?: 'GB';
    $CountryLocaleFull = Search::getCountryLocaleFull($_countryCode);
    $pricing_currency = !empty($CountryLocaleFull['currency']) ? $CountryLocaleFull['currency'] : 'GBP';
    $locale = 'en-GB';
    if(!empty($CountryLocaleFull['locales'])) {
        list($locale) = explode(',', $CountryLocaleFull['locales']);
    }
    setlocale(LC_MONETARY, $locale);
    $currentLocale = isset($currentLocale) ? $currentLocale : LaravelLocalization::getCurrentLocale();

    $pageTitle = ucfirst(
            preg_replace(
                    '/([a-z0-9])?([A-Z])/', '$1 $2',
                    str_replace(
                            ['Controller', 'BodCaracts'],
                            ['', 'Boat On Demand'],
                            $currentController
                   )
           )
   );
    if('QuickadminController' !== $currentController && 'DashboardController' != $currentController) {
        $pageTitle .= ' : ';
        $pageTitle .= ucfirst(
                preg_replace(
                        '/([a-z0-9])?([A-Z])/', '$1 $2',
                        str_replace(
                                ['Controller', 'index', 'edit', 'create', 'show'],
                                [
                                        '',
                                        'Listing',
                                        'Edit<i class="fa fa-edit fa-fw"></i>',
                                        'Create<i class="fa fa-plus-circle fa-fw"></i>',
                                        'Detail<i class="fa fa-eye fa-fw"></i>'
                                ],
                                $currentAction
                       )
               )
       );
    }

    //@TODO : mutualize code $boat_locations for $districts & $counties
    $boat_locations = config('youboat.'. $country_code .'.locations');
    $boat_locations_counties = $boat_locations['counties'];
    $counties = [];
    foreach($boat_locations_counties as $key => $county) {
        $counties[$key] = $county;
    }

    //@if(isset($adstype['rewrite_url']) || isset($manufacturerengine['rewrite_url'])) {
    $label_txt_manufacturers = trans('filters.manufacturers') . '/' . trans('filters.shipyards');
    if(isset($adstype['rewrite_url'])) {
        //@if(preg_match('/engine/', $adstype['rewrite_url']) || isset($manufacturerengine['rewrite_url'])) {
        if(preg_match('/engine/', $adstype['rewrite_url'])) {
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
    $onlyTheseDates = '';
    foreach($ad_dates as $key => $value) {
        $onlyTheseDates = "'" . $value . "',";
    }
    $onlyTheseDates = preg_replace('/,$/', '', $onlyTheseDates);
?>

@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.adscaracts.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>
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
@if($total_ads>0)
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">List</h3>
        </div>
        <div class="panel-body table-responsive">
            <div class="panel-group" id="filters" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsefilters" aria-expanded="true" aria-controls="collapsefilters" class="btn btn-primary ">
                        <h4 class="panel-title">
                            {!! trans('filters.advanced_search_filters') !!}<span class="fa fa-arrow-down fa-fw"></span>
                        </h4>
                        </a>
                    </div>
                    <section id="collapsefilters" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                        <div class="row">
                            @if(isset($adstypes) || isset($categories) || isset($subcategories))
                                <div class="col-sm-2">
                                    @if(isset($adstypes))
                                        <?php
                                        $label_txt = trans('filters.adstypes');
                                        $attributes = [
                                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                                //'class' => 'form-control select2 nosort',
                                                'class' => 'form-control nosort',
                                                'id' => 'adstypes_id'
                                        ];

                                        $css_state = '';
                                        if(!count($adstypes) > 0) {
                                            $attributes['disabled'] = 'disabled';
                                            $css_state .= 'collapse ';
                                        }

                                        if(!empty($adstype['id'])) {
                                            $css_state = 'has-success';
                                        }
                                        ?>

                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('adstypes_id', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('adstypes_id', $adstypes, old('adstypes_id'), $attributes) !!}
                                        </div>
                                    @endif

                                    @if(isset($categories))
                                        <?php
                                        $label_txt = trans('filters.categories');
                                        $attributes = [
                                            //'data-ajax--url'=>"/ajax-gateway_category",
                                                'data-parent' => !empty($adstype['id']) ? $adstype['id'] : '',
                                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                                //'class' => 'form-control select2',
                                                'class' => 'form-control',
                                                'id' => 'categories_ids'
                                        ];

                                        $css_state = '';
                                        if(!count($categories) > 0) {
                                            $attributes['disabled'] = 'disabled';
                                            $css_state .= 'collapse ';
                                        }

                                        if(!empty($category['id'])) {
                                            $css_state .= 'has-success';
                                        }
                                        ?>
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('categories_ids', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('categories_ids', $categories, !empty($category['id']) ? $category['id'] : old('categories_ids'), $attributes) !!}
                                        </div>
                                    @endif

                                    @if(isset($subcategories))
                                        <?php
                                        $label_txt = trans('filters.subcategories');
                                        $attributes = [
                                            //'data-ajax--url'=>"/ajax-gateway_subcategory",
                                                'data-parent' => !empty($category['id']) ? $category['id'] : '',
                                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                                //'class' => 'form-control select2',
                                                'class' => 'form-control',
                                                'id' => 'subcategories_ids'
                                        ];

                                        $css_state = '';
                                        if(!count($subcategories) > 0) {
                                            $attributes['disabled'] = 'disabled';
                                            $css_state .= 'collapse ';
                                        }

                                        if(!empty($subcategory['id'])) {
                                            $css_state = 'has-success';
                                        }
                                        ?>
                                        <div class="form-group subcategory {!! $css_state !!}">
                                            {!! Form::label('subcategories_ids', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('subcategories_ids', $subcategories, !empty($subcategory['id']) ? $subcategory['id'] : old('subcategories_ids'), $attributes) !!}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if(isset($manufacturers) || isset($models))
                                <div class="col-sm-2">
                                    @if(isset($manufacturers))
                                        <?php
                                        /*if(!empty($manufacturers)) {
                                            $array = json_decode(json_encode($manufacturers), true);
                                            asort($array);
                                            $manufacturers = $array;
                                        }*/
                                        $manufacturers_id = old('manufacturers_id', isset($manufacturer['id']) ? $manufacturer['id'] : '');
                                        $label_txt = $label_txt_manufacturers;
                                        $attributes = [
                                            //'data-ajax--url' => '/ajax-gateway_manufacturer',
                                                'data-placeholder' => $label_txt,
                                                'placeholder' => $label_txt,
                                                'class' => 'form-control select2',
                                                'id' => 'manufacturers_id'
                                        ];

                                        $css_state = '';
                                        if(!count($manufacturers) > 0) {
                                            $attributes['disabled'] = 'disabled';
                                            $css_state .= 'collapse ';
                                        }
                                        if(!empty($manufacturer['id'])) {
                                            $css_state = 'has-success';
                                        }
                                        ?>

                                        <div class="form-group manufacturer {!! $css_state !!}">
                                            {!! Form::label('manufacturers_id', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('manufacturers_id', !empty($manufacturers_id) ? [$manufacturers_id=>$manufacturer['name']] : [], $manufacturers_id, $attributes) !!}
                                        </div>
                                    @endif

                                    @if(isset($models))
                                        <?php
                                        if(!empty($models)) {
                                            $array = json_decode(json_encode($models), true);
                                            asort($array);
                                            $models = $array;
                                        }
                                        $models_id = old('models_id', isset($model['id']) ? $model['id'] : '');
                                        $label_txt = trans('filters.models');
                                        $attributes = [
                                                'data-parent' => !empty($manufacturer['id']) ? $manufacturer['id'] : '',
                                                'data-placeholder' => $label_txt,
                                                'placeholder' => $label_txt,
                                                'class' => 'form-control',
                                                'id' => 'models_id'
                                        ];

                                        $css_state = '';
                                        if(!count($models) > 0 && !isset($models_id)) {
                                            $attributes['disabled'] = 'disabled';
                                            $css_state .= 'collapse ';
                                        }

                                        if(!empty($model['id'])) {
                                            $css_state = 'has-success';
                                            //$attributes['data-selected'] = $model['id'];
                                        }
                                        ?>
                                        <div class="form-group model {!! $css_state !!}">
                                            {!! Form::label('models_id', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('models_id', $models, $models_id, $attributes) !!}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if(isset($dealers_names) || isset($ad_titles))
                                <div class="col-sm-2">
                                    @if(isset($dealers_names))
                                        <?php
                                        $ad_dealer_name = old('ad_dealer_name', isset($ad_dealer_name) ? $ad_dealer_name : '');
                                        $label_txt = 'Dealer';
                                        $attributes = [
                                                'data-placeholder' => $label_txt,
                                                'placeholder' => $label_txt,
                                                'class' => 'form-control select2',
                                                'id' => 'ad_dealer_name'
                                        ];

                                        $css_state = '';
                                        if(!count($dealers_names) > 0 && !isset($ad_dealer_name)) {
                                            $attributes['disabled'] = 'disabled';
                                            $css_state .= 'collapse ';
                                        }

                                        if(!empty($ad_dealer_name)) {
                                            $css_state = 'has-success';
                                            //$attributes['data-selected'] = $model['id'];
                                        }
                                        ?>
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('ad_dealer_name', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('ad_dealer_name', $dealers_names, $ad_dealer_name, $attributes) !!}
                                        </div>
                                    @endif
                                    @if(isset($ad_titles))
                                        <?php
                                        $ad_title = old('ad_title', isset($ad_title) ? $ad_title : '');
                                        $label_txt = 'Ads\'s Title';
                                        $attributes = [
                                                'data-placeholder' => $label_txt,
                                                'placeholder' => $label_txt,
                                                'class' => 'form-control select2    ',
                                                'id' => 'ad_title'
                                        ];

                                        $css_state = '';
                                        if(!count($ad_titles) > 0 && !isset($ad_title)) {
                                            $attributes['disabled'] = 'disabled';
                                            $css_state .= 'collapse ';
                                        }

                                        if(!empty($ad_title)) {
                                            $css_state = 'has-success';
                                        }
                                        ?>
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('ad_title', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('ad_title', $ad_titles, $ad_title, $attributes) !!}
                                        </div>
                                    @endif
                                </div>
                            @endif
                            <div class="col-sm-3">
                                @if(isset($ad_prices))
                                    <?php
                                    $ad_price = old('ad_price', isset($ad_price) ? $ad_price : '');
                                    $label_txt = 'Prices';
                                    $attributes = [
                                            'data-placeholder' => $label_txt,
                                            'placeholder' => $label_txt,
                                            'class' => 'form-control select2',
                                            'id' => 'ad_price'
                                    ];

                                    $css_state = '';
                                    if(!count($ad_prices) > 0 && !isset($ad_price)) {
                                        $attributes['disabled'] = 'disabled';
                                        $css_state .= 'collapse ';
                                    }

                                    if(!empty($ad_price)) {
                                        $css_state = 'has-success';
                                        //$attributes['data-selected'] = $model['id'];
                                    }
                                    ?>
                                    <div class="col-xs-12">
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('ad_price', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('ad_price', $ad_prices, $ad_price, $attributes) !!}
                                        </div>
                                    </div>
                                @endif

                                @if (isset($ad_prices))
                                    <?php
                                    $prices = [];
                                    /*foreach ($ad_prices as $key => $value) {
                                        //$prices[$key] = $key . ' (' . $value['count'] . ')';
                                        $prices[$key] = $value;
                                    }*/
                                    $prices = $ad_prices;
                                    $label_txt = 'Min. ' . trans('filters.price');
                                    $attributes = [
                                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                            //'class' => 'form-control select2',
                                            'class' => 'form-control range select2',
                                            'id' => 'min_ad_price'
                                    ];

                                    $css_state = '';
                                    if (!count($ad_prices) > 0) {
                                        $attributes['disabled'] = 'disabled';
                                        $css_state .= 'collapse ';
                                    }

                                    if (!empty($min_ad_price)) {
                                        $css_state = 'has-success';
                                    }
                                    ?>
                                    <div class="col-xs-6">
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('min_ad_price', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('min_ad_price', $prices, !empty($min_ad_price) ? $min_ad_price : old('min_ad_price'), $attributes) !!}
                                        </div>
                                    </div>
                                    <?php
                                    $label_txt = 'Max. ' . trans('filters.price');
                                    $attributes = [
                                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                            //'class' => 'form-control select2',
                                            'class' => 'form-control range select2',
                                            'id' => 'max_ad_price'
                                    ];

                                    $css_state = '';
                                    if (!count($ad_prices) > 0) {
                                        $attributes['disabled'] = 'disabled';
                                        $css_state .= 'collapse ';
                                    }

                                    if (!empty($max_ad_price)) {
                                        $css_state = 'has-success';
                                    }
                                    ?>
                                    <div class="col-xs-6">
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('max_ad_price', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('max_ad_price', array_reverse($prices,true), !empty($max_ad_price) ? $max_ad_price : old('max_ad_price'), $attributes) !!}
                                        </div>
                                    </div>
                                @endif
                                @if(isset($status_states))
                                    <?php
                                    $status = old('status', isset($status) ? $status : '');
                                    $label_txt = 'Status';
                                    $attributes = [
                                            'data-placeholder' => $label_txt,
                                            'placeholder' => $label_txt,
                                            'class' => 'form-control',
                                            'id' => 'status'
                                    ];

                                    $css_state = '';
                                    if(!count($status_states) > 0 && !isset($status)) {
                                        $attributes['disabled'] = 'disabled';
                                        $css_state .= 'collapse ';
                                    }

                                    if(!empty($status)) {
                                        $css_state = 'has-success';
                                    }
                                    ?>
                                    <div class="col-xs-12">
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('status', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('status', $status_states, $status, $attributes) !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if(isset($ad_dates))
                                    <?php
                                    $ad_date = old('updated_at', isset($ad_date) ? $ad_date : '');
                                    $label_txt = trans('boat_on_demand.deposit_date');
                                    $attributes = [
                                            'data-placeholder' => $label_txt,
                                            'placeholder' => $label_txt,
                                            'class' => 'form-control select2',
                                            'id' => 'updated_at'
                                    ];
                                    $attributesInput = [
                                            'data-placeholder' => $label_txt,
                                            'placeholder' => $label_txt,
                                            'class' => 'form-control',
                                            'id' => 'updated_at'
                                    ];
                                    $css_state = '';
                                    if(!count($ad_dates) > 0 && !isset($ad_date)) {
                                        $attributes['disabled'] = 'disabled';
                                        $css_state .= 'collapse ';
                                    }

                                    if(!empty($ad_date)) {
                                        $css_state = 'has-success';
                                    }
                                    ?>
                                    <div class="col-xs-12">
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('updated_at', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::text('updated_at', $ad_date, $attributesInput) !!}
                                            {{--{!! Form::select('updated_at', $ad_dates, $ad_date, $attributes) !!}--}}
                                        </div>
                                    </div>
                                @endif

                                @if (isset($ad_years_built))
                                    <?php
                                    $years = [];
                                    foreach ($ad_years_built as $key => $value) {
                                        //$years[$key] = $key . ' (' . $value['count'] . ')';
                                        $years[$key] = $key;
                                    }

                                    $label_txt = 'Min. ' . trans('filters.year_built');
                                    $attributes = [
                                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                            //'class' => 'form-control select2',
                                            'class' => 'form-control range select2',
                                            'id' => 'min_year_built'
                                    ];

                                    $css_state = '';
                                    if (!count($ad_years_built) > 0) {
                                        $attributes['disabled'] = 'disabled';
                                        $css_state .= 'collapse ';
                                    }

                                    if (!empty($min_year_built)) {
                                        $css_state = 'has-success';
                                    }
                                    ?>
                                    <div class="col-xs-6">
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('min_year_built', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('min_year_built', $years, !empty($min_year_built) ? $min_year_built : old('min_year_built'), $attributes) !!}
                                        </div>
                                    </div>
                                    <?php
                                    $label_txt = 'Max. ' . trans('filters.year_built');
                                    $attributes = [
                                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                            //'class' => 'form-control select2',
                                            'class' => 'form-control range select2',
                                            'id' => 'max_year_built'
                                    ];

                                    $css_state = '';
                                    if (!count($ad_years_built) > 0) {
                                        $attributes['disabled'] = 'disabled';
                                        $css_state .= 'collapse ';
                                    }

                                    if (!empty($max_year_built)) {
                                        $css_state = 'has-success';
                                    }
                                    ?>
                                    <div class="col-xs-6">
                                        <div class="form-group {!! $css_state !!}">
                                            {!! Form::label('max_year_built', $label_txt, ['class'=>'control-label']) !!}
                                            {!! Form::select('max_year_built', array_reverse($years,true), !empty($max_year_built) ? $max_year_built : old('max_year_built'), $attributes) !!}
                                        </div>
                                    </div>
                                @endif

                                <?php
                                $label_txt = trans('filters.min_length') . ' (m)';
                                $attributes = [
                                        'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                        'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                        //'class' => 'form-control select2 nosort',
                                        'class' => 'form-control nosort range',
                                        'id' => 'min_length'
                                ];

                                $css_state = '';
                                if (!empty($boat_min_length)) {
                                    $css_state = 'has-success';
                                }
                                ?>
                                <div class="col-xs-6">
                                    <div class="form-group {!! $css_state !!}">
                                        {!! Form::label('min_length', $label_txt, ['class'=>'control-label']) !!}
                                        {!! Form::selectRange('min_length', 1, 16, !empty($boat_min_length) ? $boat_min_length : old('min_length'), $attributes) !!}
                                        {{--<input type="range" name="min_length_range" id="min_length_range" min="0" max="14" step="1" value="0">--}}
                                    </div>
                                </div>
                                <?php
                                $label_txt = trans('filters.max_length') . ' (m)';
                                $attributes = [
                                        'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                        'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                        //'class' => 'form-control select2 nosort',
                                        'class' => 'form-control nosort range',
                                        'id' => 'max_length'
                                ];

                                $css_state = '';
                                if (!empty($boat_max_length)) {
                                    $css_state = 'has-success';
                                }
                                ?>
                                <div class="col-xs-6">
                                    <div class="form-group {!! $css_state !!}">
                                        {!! Form::label('max_length', $label_txt, ['class'=>'control-label']) !!}
                                        {!! Form::selectRange('max_length', 16, 1,  !empty($boat_max_length) ? $boat_max_length : old('max_length'), $attributes) !!}
                                        {{--<input type="range" name="max_length_range" id="max_length_range" min="0" max="14" step="1" value="0">--}}
                                    </div>
                                </div>

                                <?php
                                $label_txt = trans('filters.min_width') . ' (m)';
                                $attributes = [
                                        'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                        'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                        //'class' => 'form-control select2 nosort',
                                        'class' => 'form-control nosort range',
                                        'id' => 'min_width'
                                ];

                                $css_state = '';
                                if (!empty($boat_min_width)) {
                                    $css_state = 'has-success';
                                }
                                ?>
                                <div class="col-xs-6">
                                    <div class="form-group {!! $css_state !!}">
                                        {!! Form::label('min_width', $label_txt, ['class'=>'control-label']) !!}
                                        {!! Form::selectRange('min_width', 1, 6, !empty($boat_min_width) ? $boat_min_width : old('min_width'), $attributes) !!}
                                        {{--<input type="range" name="min_width_range" id="min_width_range" min="0" max="14" step="1" value="0">--}}
                                    </div>
                                </div>
                                <?php
                                $label_txt = trans('filters.max_width') . ' (m)';
                                $attributes = [
                                        'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                        'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                        //'class' => 'form-control select2 nosort',
                                        'class' => 'form-control nosort range',
                                        'id' => 'max_width'
                                ];

                                $css_state = '';
                                if (!empty($boat_max_width)) {
                                    $css_state = 'has-success';
                                }
                                ?>
                                <div class="col-xs-6">
                                    <div class="form-group {!! $css_state !!}">
                                        {!! Form::label('max_width', $label_txt, ['class'=>'control-label']) !!}
                                        {!! Form::selectRange('max_width', 6, 1,  !empty($boat_max_width) ? $boat_max_width : old('max_width'), $attributes) !!}
                                        {{--<input type="range" name="max_width_range" id="max_width_range" min="0" max="14" step="1" value="0">--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </section>
                </div>
            </div>

            <table id="ads_list" class="table table-hover"
                   data-search="true"
                   data-cookie="true"
                   data-show-toggle="true"
                   data-show-columns="true"
                   data-pagination="true"
                   data-page-list="[10, 25, 50, 100]"
                   data-sort-name="updated_at"
                   data-sort-order="desc"
                   data-id-table="ads_list"
            >
                <thead></thead>
                <tbody></tbody>
                <tfoot></tfoot>
            </table>
            @if($isAdmin)
                <div class="col-xs-12">
                    {!! Form::checkbox('delete_all', 1, false, ['class' => 'mass']) !!} Select all
                    <button class="btn btn-danger btn-exception" id="delete">
                        <i class="fa fa-trash-o fa-fw"></i>Delete checked
                    </button>
                </div>
            </div>
            {!! Form::open(['route' => config('quickadmin.route') . '.adscaracts.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
            <input type="hidden" id="send" name="toDelete">
            {!! Form::close() !!}
            @endif
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                @if(!empty($total_ads) && $total_ads > 0)
                   <p><strong class="total_results">{!! $total_ads . ' ' . trans('pagination.results') !!}</strong></p>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">List</h3>
        </div>
        <div class="panel-body text-danger">
            No entries found.
        </div>
    </div>
@endif

@endsection

@section('javascript')
    <script src="/assets/vendor/youboat/js/filters_search.js"></script>
    <script>
        var token = $('[name="csrf-token"]').attr('content');

        // Extend the default Number object with a formatMoney() method:
        // usage: someVar.formatMoney(decimalPlaces, symbol, thousandsSeparator, decimalSeparator)
        // defaults: (2, "$", ",", ".")
        /*Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
            places = !isNaN(places = Math.abs(places)) ? places : 2;
            symbol = symbol !== undefined ? symbol : "$";
            thousand = thousand || ",";
            decimal = decimal || ".";
            var number = this,
                    negative = number < 0 ? "-" : "",
                    i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
                    j = (j = i.length) > 3 ? j % 3 : 0;
            return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
        };*/
        //Minified version of prototype method
        Number.prototype.formatMoney=function(d,g,h,a){d=!isNaN(d=Math.abs(d))?d:2;g=g!==undefined?g:"$";h=h||",";a=a||".";var f=this,c=f3?b%3:0;return g+c+(b?e.substr(0,b)+h:"")+e.substr(b).replace(/(\d{3})(?=\d)/g,"$1"+h)+(d?a+Math.abs(f-e).toFixed(d).slice(2):"");};
        // Remove non-numeric chars (except decimal point/minus sign):
        //priceVal = parseFloat(price.replace(/[^0-9-.]/g, '')); // 12345.99

        // Create our number formatter.
        var formatter = new Intl.NumberFormat('{!! $locale !!}', {
            style: 'currency',
            currency: '{!! $pricing_currency !!}',
            currencyDisplay: 'symbol',
            //useGrouping:false,
            minimumFractionDigits: 0,
        });

        var formatterMoney = function(value) {
            return value.toLocaleString('{!! $locale !!}', { style: 'currency', currency: '{!! $pricing_currency !!}' });
        }

        function formatRepo (repo) {
            if (repo.loading) {
                var $Repo = $(
                        '<i class="fa fa-circle-o-notch fa-spin fa-fw"></i><span class="sr-only">Loading...</span>'
                );
                return $Repo;
            }
            return repo.text;
        }

        function formatRepoSelection (repo) {
            return repo.text;
        }
        //
        // Pipelining function for DataTables. To be used to the `ajax` option of DataTables
        //
        $.fn.dataTable.pipeline = function(opts) {
            // Configuration options
            var conf = $.extend({
                pages: 1,     // number of pages to cache
                url: '',      // script url
                data: null,   // function or object with parameters to send to the server
                              // matching how `ajax.data` works in DataTables
                method: 'GET' // Ajax HTTP method
            }, opts);

            // Private variables for storing the cache
            var cacheLower = -1;
            var cacheUpper = null;
            var cacheLastRequest = null;
            var cacheLastJson = null;

            return function(request, drawCallback, settings) {
                var ajax          = false;
                request.page = Math.ceil(request.start / request.length) + 1;
                var requestcurrentPage = request.page;
                var requestStart  = request.start;
                var drawStart     = request.start;
                var requestLength = request.length;
                var requestEnd    = requestStart + requestLength;

                if(settings.clearCache) {
                    // API requested that the cache be cleared
                    ajax = true;
                    settings.clearCache = false;
                }
                else if(cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                    // outside cached data - need to make a request
                    ajax = true;
                }
                else if(JSON.stringify(request.order)   !== JSON.stringify(cacheLastRequest.order) ||
                        JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                        JSON.stringify(request.search)  !== JSON.stringify(cacheLastRequest.search)
               ) {
                    // properties changed(ordering, columns, searching)
                    ajax = true;
                }

                // Store the request for checking next time around
                cacheLastRequest = $.extend(true, {}, request);

                if(ajax) {
                    // Need data from the server
                    if(requestStart < cacheLower) {
                        requestStart = requestStart -(requestLength*(conf.pages-1));

                        if(requestStart < 0) {
                            requestStart = 0;
                        }
                    }

                    cacheLower = requestStart;
                    cacheUpper = requestStart + (requestLength * conf.pages);

                    request.start = requestStart;
                    request.length = requestLength*conf.pages;

                    // Provide the same `data` options as DataTables.
                    if($.isFunction(conf.data)) {
                        // As a function it is executed with the data object as an arg
                        // for manipulation. If an object is returned, it is used as the
                        // data object to submit
                        var d = conf.data(request);
                        if(d) {
                            $.extend(request, d);
                        }
                    }
                    else if($.isPlainObject(conf.data)) {
                        // As an object, the data given extends the default
                        $.extend(request, conf.data);
                    }

                    settings.jqXHR = $.ajax({
                        "type":     conf.method,
                        "url":      conf.url,
                        "data":     request,
                        "dataType": "json",
                        "cache":    false,
                        "success":  function(json) {
                            cacheLastJson = $.extend(true, {}, json);

                            if(cacheLower != drawStart) {
                                json.data.splice(0, drawStart-cacheLower);
                            }
                            if(requestLength >= -1) {
                                json.data.splice(requestLength, json.data.length);
                            }

                            drawCallback(json);
                        }
                    });
                } else {
                    json = $.extend(true, {}, cacheLastJson);
                    json.draw = request.draw; // Update the echo for each response
                    json.data.splice(0, requestStart-cacheLower);
                    json.data.splice(requestLength, json.data.length);

                    drawCallback(json);
                }
            }
        };

        // Register an API method that will empty the pipelined data, forcing an Ajax
        // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
        $.fn.dataTable.Api.register('clearPipeline()', function() {
            return this.iterator('table', function(settings) {
                settings.clearCache = true;
            });
        });
        var addDatasToColums = function(data) {
            var $Filters = $('#filters');
            var $MinAdPrice = $('#min_ad_price');
            var $MaxAdPrice = $('#max_ad_price');
            var $MinYearBuilt = $('#min_year_built');
            var $MaxYearBuilt = $('#max_year_built');
            var $MinLength = $('#min_length');
            var $MaxLength = $('#max_length');
            var $MinWidth = $('#min_width');
            var $MaxWidth = $('#max_width');
            var $MinEnginePower = $('#min_engine_power');
            var $MaxEnginePower = $('max_engine_power');
            var $TypeEnginePower = $('type_engine_power');

            var $oElmRange = $('#filters .range');

            $oElmRange.each(function() {
                var $This = $(this);
                var array = [];
                var range = {};
                if($This.val()) {
                    array['data'] = $This.attr('id');
                    array['search'] = ['regex',false];
                    array['search'] = ['value',$This.val()];
                    range[$This.attr('id')] = $This.val();
                    data.columns.push(array);
                    $.extend(data,range);
                }
            });
            return data;
        };

        $.fn.dataTable.fillTable = function(settings) {
            var api = new $.fn.dataTable.Api(settings);
            /*api.columns('.select-filter').every(function(indexColumn) {
                //console.log('indexColumn',indexColumn);
                var column = this;
                var column_data = column.data();
                $(column.footer()).find('br,select,.select2').remove();
                var $Selects = $('<br><select class="select-filter select2" id="' + column_data.context[0].aoColumns[indexColumn].data + '"><option value=""></option></select>');
                $Selects.appendTo($(column.footer())).on('change', function() {
                    //var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    //column.search(val ? '^'+val+'$' : '', true, false).draw();
                    column.search($(this).val()).draw();
                });
                var removeEmptySelect = true;
                column_data.each(function(value, index) {
                    removeEmptySelect = ('undefined' != typeof value && '' != value) ? false : true;
                });
                if(removeEmptySelect) {
                    $(column.footer()).find('br,select,.select2').remove();
                }
                column_data
                        .sort()
                        .unique()
                        .each(function(value, index) {
                    if('undefined' != typeof value && '' != value) {
                        var label = value;
                        var title = this.context[0].aoColumns[indexColumn].field;

                        if(/{!! mb_strtolower(trans('filters.price')) !!}/.test(title))
                        {
                            label = formatter.format(value);
                        }
                        $Selects.append('<option value="' + value + '">' + label + '</option>');
                    }
                });
            });*/
            api.columns('.search-filter').every(function(indexColumn) {
                var column = this;
                var column_data = column.data();
                var column_field = column_data.context[0].aoColumns[indexColumn].field;

                var title = column_data.context[0].aoColumns[indexColumn].sTitle;
                var $Inputs = $('<input> ', {/*id: column_field, name: column_field,*/ placeholder:"{!! trans('navigation.form_enter_placeholder') !!}"});
                $(column.footer()).html('Search ' + title + '<br>');
                $Inputs.appendTo($(column.footer())).on('keyup', function() {
                    if(column.search() !== this.value) {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        //column.search(val ? '^'+val+'$' : '', true, false).draw();
                        column.search(this.value).draw();
                    }
                });
            });
            /*$('.select-filter.select2').select2({
                allowClear: true,
                minimumResultsForSearch: 10,
                minimumInputLength: 2,
                sorter: customSorter,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection,
                escapeMarkup: function (markup) {
                    return markup; // let our custom formatter work
                }
            });*/
            /*api.columns('.select-filter').every(function() {
                var that = this;

                // Create the select list and search operation
                var select = $('<select />')
                        .appendTo(
                                this.footer()
                       )
                        .on('change', function() {
                            that
                                    .search($(this).val())
                                    .draw();
                        });

                // Get the search data for the first column and add to the select list
                this
                        .cache('search')
                        .sort()
                        .unique()
                        .each(function(d) {
                            select.append($('<option value="'+d+'">'+d+'</option>'));
                        });
            });*/
        };

        //
        // DataTables initialisation
        //
        $(document).ready(function() {
            function getColumnNumberByName(oTable, name) {
                return oTable.column(name + ":name").index();
            }

            $('#collapsefilters').collapse('hide');

            var $AdsList = $('#ads_list');
            var table = $AdsList.DataTable({
                "dom": '<"#toolbar.well well-white"<"tools row"<"info col-sm-4"><"buttons col-sm-8 text-right"B>><"search row"<"col-sm-6"l><"col-sm-6"f>>>r<"col-sm-12 table-responsive"t><"bottom row"<"col-sm-6"i><"col-sm-6"p>>',
                "cookie": true,
                "bStateSave": false,
                "fnStateSaveParams": function(settings, data) {
                    data.search.search = "";
                },
                "lengthMenu": [10, 25, 30, 50, 100],
                buttons: [
                    //'pageLength',
                   {
                        autoClose: true,
                        extend: 'colvis',
                        text:      '<i class="fa fa-eye"></i> Show / Hide',
                        titleAttr: 'Show / Hide',
                        className: 'sorting',
                        columns: ':gt(0),.sorting',
                        postfixButtons: [ 'colvisRestore' ]
                    },
                    //'copy', 'csv', 'excel', 'pdf', 'print',
                    {
                        text:      '<i class="fa fa-refresh"></i> Reload',
                        titleAttr: 'Reload',
                        className: '',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload(null, true);
                        }
                    },
                    {
                        extend:    'print',
                        text:      '<i class="fa fa-print"></i> Print',
                        titleAttr: 'Print',
                        className: ''
                        /*,autoPrint: false,*/
                        /*,customize: function(win) {
                         $(win.document.body)
                         .css('font-size', '10pt')
                         .prepend(
                         '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                        );

                         $(win.document.body).find('table')
                         .addClass('compact')
                         .css('font-size', 'inherit');
                         }*/
                    },
                    {
                        extend:    'copyHtml5',
                        exportOptions: {
                            columns: [ 1, ':visible' ]
                        },
                        text:      '<i class="fa fa-files-o"></i> Copy',
                        titleAttr: 'Copy',
                        className: 'blue'
                    },
                    {
                        extend:    'excelHtml5',
                        exportOptions: {
                            columns: [ 1, ':visible' ]
                        },
                        text:      '<i class="fa fa-file-excel-o"></i> Excel',
                        titleAttr: 'Excel',
                        className: 'green'
                    },
                    {
                        extend:    'csvHtml5',
                        exportOptions: {
                            columns: [ 1, ':visible' ]
                        },
                        text:      '<i class="fa fa-file-text-o"></i> CSV',
                        titleAttr: 'CSV',
                        className: 'purple',
                        fieldSeparator: ';',
                        extension: '.csv'
                    },
                    {
                        extend:    'pdfHtml5',
                        exportOptions: {
                            columns: [ 1, ':visible' ]
                        },
                        text:      '<i class="fa fa-file-pdf-o"></i> PDF',
                        titleAttr: 'PDF',
                        className: 'red',
                        footer: false,
                        orientation: 'landscape',
                        pageSize: 'A4',
                        download: 'open'
                        ,message: '{!! $pageTitle !!}'
                        ,customize: function(doc) {
                            // Splice the image in after the header, but before the table
                            doc.content.splice(1, 0, {
                                margin: [ 0, 0, 0, 12 ],
                                alignment: 'center',
                                //image: '/assets/vendor/youboat/img/logo-small-uk.jpg',
                                fit: [100, 100],
                                image: 'data:image/jpeg;base64,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'
                            });
                            // Data URL generated by http://dataurl.net/#dataurlmaker
                        }
                    }
                ],
                "pagingType": "simple_numbers",
                /*initComplete: function(settings, json) {
                    var fillTable = new $.fn.dataTable.fillTable(settings);
                    var api = new $.fn.dataTable.Api(settings);
                    api.order([ getColumnNumberByName(table, 'updated_at'), 'desc' ]).draw();
                },*/
                "language": {
                    buttons: {
                        colvis: 'Columns visibility',
                        colvisRestore: 'Show all',
                        /*copyTitle: 'Ajouté au presse-papiers',
                        copyKeys: 'Appuyez sur <i>ctrl</i> ou <i>\u2318</i> + <i>C</i> pour copier les données du tableau à votre presse-papiers. <br><br>Pour annuler, cliquez sur ce message ou appuyez sur Echap.',
                        copySuccess: {
                            _: 'Copiés %d rangs',
                            1: 'Copié 1 rang'
                        }*/
                    },
                    "decimal": "",
                    //"emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    //"infoEmpty": "No entries to show",
                    "infoFiltered": "(filtered from _MAX_ records)",
                    //"infoPostFix": "All records shown are derived from real information.",
                    "thousands": ",",
                    "lengthMenu": "Show _MENU_ entries",
                    "loadingRecords": "Please wait - Loading...",
                    "processing": "Processing...",
                    "search": "Search:",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    },
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                },
                "infoCallback": function(settings, start, end, max, total, pre) {
                    var api = this.api();
                    var pageInfo = api.page.info();
                    $("#toolbar .info").html(pre);
                    return 'Page '+ (pageInfo.page+1) +' of '+ pageInfo.pages;
                },
                "processing": true,
                "serverSide": true,
                "ajax": $.fn.dataTable.pipeline({
                    data: function(data) {
                        data = addDatasToColums(data);
                        return data;
                    },
                    url: '/ajax-get_ad_list',
                    pages: 1 // number of pages to cache
                }),
                "deferRender": true,
                "createdRow": function(row, data, dataIndex) {
                    css_status = '';
                    strike = '';

                    switch(data.status) {
                        default:
                            status = '';
                            strike = '';
                            break;
                        case 'closed':
                            status = 'alert alert-warning';
                            strike = 'text-decoration: line-through';
                            $(row).attr('style', strike);
                            break;
                        case 'mailing':
                            status = 'bg-info bg-mailing';
                            break;
                        case 'no_answer':
                            status = 'bg-warning bg-no_answer';
                            break;
                        case 'nok':
                            status = 'alert alert-danger';
                            strike = 'text-decoration: line-through';
                            $(row).attr('style', strike);
                            break;
                        case 'ok':
                            status = 'bg-success bg-ok';
                            break;
                        case 'phone_pb':
                            status = 'bg-warning bg-phone_pb';
                            break;
                        case 'recall':
                            status = 'bg-info bg-recall';
                            break;
                        case 'inactive':
                            status = 'bg-warning';
                            break;
                        case 'active' :
                            status = 'bg-success';
                            break;
                    }
                    $(row).addClass(status);
                },
                columns: [
                    @if($isAdmin)
                    { data: "id", title: 'Select', sortable: false, orderable: false, visible:true, className: "no-sort exception",
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).empty();


                            $(td).append('<input type="checkbox" name="del-' + cellData + '" id="del-' + cellData + '" value="1" class="single" data-id="' + cellData + '">');
                        }
                    },
                    @endif
                    { data: "id", title: 'Actions', sortable: false, orderable: false, visible:true, className: "no-sort exception",
                        createdCell: function(td, cellData, rowData, row, col) {
                            $btnEdit = $('<a>', { 'href': "/{!! config('quickadmin.route') !!}/adscaracts/" + cellData + "/edit", 'class': "btn btn-block btn-xs btn-primary"}).html('<i class="fa fa-pencil fa-fw"></i>Edit</a>');
                            $(td).empty().append($btnEdit);

                            {{--@if($isAdmin)--}}
                            $btnDelete = $('<form>', { 'method': "POST", 'action': "/{!! config('quickadmin.route') !!}/adscaracts/" + cellData , 'accept-charset': "UTF-8", 'onsubmit': "return confirm('Confirm deletion');" });
                            $btnDelete
                                .append($('<input>', { 'name': "_method", 'type': "hidden", 'value': "DELETE"}))
                                .append($('<input>', { 'name': "_token", 'type': "hidden", 'value': token}))
                                .append($('<button>', { 'type': "submit", 'class': "btn btn-block btn-xs btn-danger"}).html('<i class="fa fa-trash-o fa-fw"></i>Delete'));
                            $(td).append('<hr>').append($btnDelete);
                            {{--@endif--}}
                            $btnStats = $('<a>', { 'href': "/{!! config('quickadmin.route') !!}/statistics/ad-" + cellData, 'class': "btn btn-block btn-xs btn-default purple", 'target': "_blank"}).html('<i class="fa fa-line-chart fa-fw"></i>Stats</a>');
                            $(td).append('<hr>').append($btnStats);
                        }
                    },
                    @if($superUser){
                        data: "status", title: 'Status',
                        /*createdCell: function(td, cellData, rowData, row, col) {
                            //console.log(row);
                            //console.log(col);
                            //console.log(cellData);
                            //console.log(rowData);
                            //$(td).parent('tr').addClass(cellData);
                        }*/
                    },@endif
                    @if($superUser)
                    { data: "ad_dealer_name", name: "ad_dealer_name", title: 'Dealer name', className: "search-filter"},
                    @endif
                    @if($superUser)
                    { data: "dealerscaracts_id", name: "dealerscaracts_id", title: "hidden", visible:false, className: "exception"},
                    @endif
                    { data: "ad_title", name: "ad_title", title: 'Ad title', className: "search-filter"},
                    { data: "adstypes_name", name: "adstypes_name", title: "{!! trans('filters.adstype') !!}", className: "search-filter"},
                    { data: "adstypes_id", name: "adstypes_id", title: 'hidden', sortable: false, orderable: false, visible:false, className: "exception"},
                    { data: "categories_name", name: "categories_name", title: "{!! trans('filters.category') !!}", className: "search-filter"},
                    { data: "categories_ids", name: "categories_ids", title: "hidden", visible:false, className: "exception"},
                    @if($superUser)
                    { data: "subcategories_name", name: "subcategories_name", title: "{!! trans('filters.subcategory') !!}", className: "search-filter"},
                    @endif
                    @if($superUser)
                    { data: "subcategories_ids", name: "subcategories_ids", title: "hidden", visible:false, className: "exception"},
                    @endif
                    { data: "manufacturers_name", name: "manufacturers_name", title: "{!! trans('filters.manufacturer') !!} / {!! trans('filters.shipyard') !!}", className: "search-filter"},
                    { data: "manufacturers_id", name: "manufacturers_id", title: "hidden", visible:false, className: "exception"},
                    { data: "models_name", name: "models_name", title: "{!! trans('filters.model') !!}", className: "search-filter"},
                    { data: "models_id", name: "models_id", title: "hidden", visible:false, className: "exception"},
                    { data: "ad_price", name: "ad_price", title: 'Price',
                        "render":
                            function(data, type, row) {
                                //console.log('type ', type);
                                if ((typeof data === 'string' || data instanceof String) && isNaN(data)) {
                                    //console.log('string ', data);
                                    return data;
                                } else {
                                    //console.log('number ', data);
                                    return formatter.format(data);
                                }
                            },
                        className: "search-filter"
                    },
                    //{ data: "updated_at", name: "updated_at", title: "{!! trans('boat_on_demand.deposit_date') !!}", className: "search-filter"},
                    { data: "updated_at", name: "updated_at", title: "{!! trans('boat_on_demand.deposit_date') !!}", className: "search-filter",
                        "render":
                                function(data, type, row) {
                                    if ((typeof data === 'string' || data instanceof String) && isNaN(data)) {
                                        return moment(data).format('YYYY-MM-DD');
                                    } else {
                                        return data;
                                    }
                                }
                    },
                    @if($superUser)
                    { data: "sell_type", name: "sell_type", title: 'Sell type', className: "search-filter"},
                    @endif
                    { data: "countries_name", name: "countries_name", title: 'Country', className: "search-filter"},
                    { data: "countries_id", name: "countries_id", title: "hidden", visible:false, className: "exception"},
                ]
                //,"order": [],
            });

            table.order([[getColumnNumberByName(table, 'updated_at'), 'desc'], [@if($superUser) 2 @else 1 @endif, 'asc']]).draw();

            // Setup - add a text input to each footer cell
            /*$('tfoot th', $AdsList).each(function() {
                var title = $(this).text();
                //$(this).html('<input type="text" placeholder="Search ' + title + '" />');
                $(this).html('Search ' + title + '<br><input type="text" placeholder="{!! trans('navigation.form_enter_placeholder') !!}" />');
            });*/

            $('tbody', $AdsList).on('mouseenter', 'td', function() {
                if('undefined' != typeof table.cell(this).index()) {
                    var colIdx = table.cell(this).index().column;
                    var rowIdx = table.cell(this).index().row;
                    $(table.cells().nodes()).removeClass('bg-white strong');
                    $(table.column(colIdx).nodes()).addClass('bg-white');

                    $(table.row(rowIdx).nodes()).find('td').addClass('strong bg-white');
                }
            });

            // Apply the search
            table.columns().every(function() {
                var column = this;
                /*$('input', this.footer()).on('keyup', function() {
                    //console.log('column.search()');
                    //console.log(column.search());
                    if(column.search() !== this.value) {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        //column.search(val ? '^'+val+'$' : '', true, false).draw();
                        column.search(this.value).draw();
                    }
                });*/

                $('select', this.header()).on('change', function() {
                    if(column.search() !== this.value) {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        //column.search(val ? '^'+val+'$' : '', true, false).draw();
                        column.search(this.value).draw();
                    }
                });
            });
            /*
            $('#filters select').not('.select2').select2({
                allowClear: true,
                minimumResultsForSearch: 10,
                sorter: customSorter,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection,
                escapeMarkup: function (markup) {
                    return markup; // let our custom formatter work
                }
            });
            */

            var FiltersSelect2 = function(){$('#filters select.select2').select2({allowClear: true, minimumResultsForSearch: 10})};
            $('#collapsefilters').on('shown.bs.collapse', function() {
                console.log("$('#collapsefilters').on('show.bs.collapse'");
                FiltersSelect2();
            });

            $('#filters select').not('.range').on('change', function() {
                var $This = $(this);
                var value = this.value;
                var id =  this.id;
                $('input[name="' + id + '"]').val('');
                var index = table.column(id + ":name").index();

                var column = table.column(index);
                column.search(value).draw();
            });
            $('#filters .range').on('change', function() {
                var $This = $(this);
                var value = this.value;
                var id =  this.id;
                table.draw();
            });

            /*$('#filters select.select2').on("select2:unselect", function (e) {
                //console.log("select2:unselect", e);
            });*/

            /*table.on('init',  function(e, settings) {
                console.info('===========');
                console.info('init');
            });*/
            /*table.on('order',  function(e, settings) {
                console.info('===========');
                console.info('Order');
            });*/
            /*table.on('draw', function(e, settings) {
                console.info('===========');
                console.info('draw');
            });*/
            ////table.on('processing', function(e, settings, processing) {
                //console.info('===========');
                //console.info('processing', processing);
                //settings.oInit.initComplete(settings);
                //var fillTable = new $.fn.dataTable.fillTable(settings);
                /*var api = new $.fn.dataTable.Api(settings);
                api.columns('.select-filter').every(function() {
                    var that = this;

                    // Create the select list and search operation
                    var select = $('<select />')
                            .appendTo(
                                    this.footer()
                            )
                            .on('change', function() {
                                that
                                        .search($(this).val())
                                        .draw();
                            });

                    // Get the search data for the first column and add to the select list
                    this
                            .cache('search')
                            .sort()
                            .unique()
                            .each(function(d) {
                                select.append($('<option value="'+d+'">'+d+'</option>'));
                            });
                });*/
            ////});
            table.on('search', function(e, settings) {
                //console.info('===========');
                //console.info('Search');
                $('#toolbar .info').find('.search').remove();
                $('#toolbar .info').html('<div class="search">Currently applied global search: '+table.search()+'</div>');
            });
            table.on('page',   function(e, settings) {
                console.info('===========');
                console.info('Page');
                var info = table.page.info();
                $('#toolbar .info').find('.page').remove();
                $('#toolbar .info').html('<div class="page">Showing page: '+info.page+' of '+info.pages+'</div>');
            });

            function available(date) {
                dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
                if ($.inArray(dmy, availableDates) != -1) {
                    return true;
                } else {
                    return false;
                }
            }

            var onlyTheseDates = [{!! $onlyTheseDates !!}];
            var updated_at_datetimepicker = $('#updated_at').datetimepicker({
                locale: '{{ $currentLocale }}',
                keepOpen: false,
                format: 'YYYY-MM-DD',
                tooltips: {
                    today: 'Go to today',
                    clear: 'Clear selection',
                    close: 'Close the picker',
                    selectMonth: 'Select Month',
                    prevMonth: 'Previous Month',
                    nextMonth: 'Next Month',
                    selectYear: 'Select Year',
                    prevYear: 'Previous Year',
                    nextYear: 'Next Year',
                    selectDecade: 'Select Decade',
                    prevDecade: 'Previous Decade',
                    nextDecade: 'Next Decade',
                    prevCentury: 'Previous Century',
                    nextCentury: 'Next Century'
                },
                enabledDates: onlyTheseDates,
                showClose: true,
                showClear: true,
                showTodayButton: true,
                widgetPositioning: {
                    vertical: 'bottom'
                }
            });
            /*updated_at_datetimepicker.on('dp.show', function(e) {
                console.log('dp.show', e);
            });*/
            updated_at_datetimepicker.on('dp.change', function(e) {
                if('undefined' != typeof e.date) {
                    var id =  this.id;
                    var index = table.column(id + ":name").index();
                    var column = table.column(index);
                    column.search(moment(e.date._d).format('YYYY-MM-DD')).draw();
                }
            });
            /*
            var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
            table.buttons().container().appendTo($('#toolbar .buttons'));
            */
        });
    </script>
@endsection