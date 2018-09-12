<?php
    $label_txt_manufacturers = trans('filters.manufacturers') . ' / ' . trans('filters.shipyards');

    $photos_missing = '';
    $message = '';
    if(empty($ad_photos) && 'edit' === $form_action) {
        $photos_missing = true;
        $message = trans('ads_caracts.photos_missing');
    }
?>
@if(isset($user_caracts['id'])){!! Form::hidden('dealerscaracts_id', $user_caracts['id']) !!}@endif
@if(isset($user_caracts['denomination'])){!! Form::hidden('ad_dealer_name', $user_caracts['denomination']) !!}@endif

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" {!! isset($photos_missing) && !$photos_missing ? 'class="active"' : '' !!}><a class="required" href="#ad_s_details" aria-controls="ad_s_details" role="tab" data-toggle="tab">{!! ucfirst(trans('ads_caracts.ad_s_details')) !!} <span class="text-danger">*</span><i class="fa fa-filter fa-fw"></i></a></li>
    <li role="presentation"><a class="required" href="#description" aria-controls="description" role="tab" data-toggle="tab">{!! ucfirst(trans('validation.attributes.description')) !!}  <span class="text-danger">*</span><i class="fa fa-paragraph fa-fw"></i></a></li>
    <li role="presentation"><a href="#specifications" aria-controls="specifications" role="tab" data-toggle="tab">{!! ucfirst(trans('validation.attributes.specifications')) !!}<i class="fa fa-list-alt fa-fw"></i></a></li>
    <li role="presentation"><a href="#features" aria-controls="features" role="tab" data-toggle="tab">{!! ucfirst(trans('validation.attributes.features')) !!}<i class="fa fa-list fa-fw"></i></a></li>
    <li role="presentation" class="{!! isset($photos_missing) && $photos_missing ? 'active ' : '' !!}{!! isset($form_action) && 'edit' === $form_action ? '' : 'hidden' !!}">
        <a href="#photos" aria-controls="photos" role="tab" data-toggle="tab">
            {!! ucfirst(trans('ads_caracts.photos')) !!}
            <i class="fa fa-picture-o fa-fw"></i>
        </a>
    </li>
    <li role="presentation"><a class="required" href="#address" aria-controls="address" role="tab" data-toggle="tab">{!! ucfirst(trans('validation.attributes.address')) !!} <span class="text-danger">*</span><i class="fa fa-map-marker fa-fw"></i></a></li>
    <li><a name="required"><span class="label label-danger">* {!! trans('filters.required') !!}</span></a></li>
</ul>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane {!! !$photos_missing ? 'active' : '' !!}" id="ad_s_details">
        <div class="row well">
            <div class="col-sm-6">
                <?php
                    $select_values = $selltypes ;
                    $input_name = 'sell_type';
                    $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                        <div class="input-group">
                            {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
                <?php
                    $input_name = 'with_marina_berth';
                    $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                    //$with_marina_berth      = isset($with_marina_berth) ? $with_marina_berth : (!empty($datasRequest['with_marina_berth']) ? $datasRequest['with_marina_berth'] : null);
                    $label_txt = ucfirst(trans('sell.with_marina_berth'));
                    if (!empty($input_value) && '1' == $input_value) {
                        $css_state = 'has-success';
                        $attributes = [
                            //'data-placeholder' => $placeholder,
                            //'placeholder' => $placeholder,
                            //'class' => 'form-control',
                                'id' => $input_name
                        ];
                        $checkbox_attributes = [
                                'id'=>'checkbox_' . $input_name,
                                'checked'=>'checked'
                        ];
                    } else {
                        $attributes = [
                                'id'=>$input_name
                        ];
                        $checkbox_attributes = [
                                'id'=>'checkbox_' . $input_name
                        ];
                    }
                    $css_state = '';
                    if (!empty($input_value)) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has($input_name)) {
                        $css_state = 'has-error';
                    }
                ?>
                <div class="form-group {!! $css_state !!}">
                    <div class="col-xs-12 col-sm-7 col-sm-offset-5">
                        <div class="checkbox {!! $css_state !!}">
                            <label for="checkbox_{!! $input_name !!}">
                                {!! Form::checkbox('checkbox_' . $input_name, !empty($input_value) ? true : false, !empty($input_value) ? $input_value : old($input_name, 0), $checkbox_attributes) !!}
                                {!! Form::hidden($input_name, !empty($input_value) ? $input_value : old($input_name, 0), $attributes) !!}
                                {!! $label_txt !!}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <?php
                    $country_id = isset($country_id) ? $country_id : 77;
                    $country_locale_full = Search::getCountryLocaleFull($country_id);
                    $currency =  is_array($country_locale_full) && array_key_exists('currency', $country_locale_full) ? $country_locale_full['currency'] : config('youboat.' . $country_code . '.currency');
                    $input_name = 'ad_price';
                    $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
            </div>
        </div>
        <div class="row well">
            <div class="col-sm-6">
                <?php
                $select_values = $adstypes;
                $input_name = 'adstypes_id';
                $adstypes_id = $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                        <div class="input-group">
                            {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <?php
                $select_values = $categories;
                $input_name = 'categories_ids';
                $categories_ids = $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                        <div class="input-group">
                            {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <?php
                $select_values = $subcategories;
                $input_name = 'subcategories_ids';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                        <div class="input-group">
                            {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if((isset($manufacturers) && !empty($manufacturers)) || (isset($models) && !empty($models)))
        <div class="row well">
            @if(isset($manufacturers))
            <div class="col-sm-6">
                <?php
                $select_values = $manufacturers;
                $input_name = 'manufacturers_id';
                $manufacturers_id = $input_value = !empty($$input_name) ? $$input_name : old($input_name, '');

                $manufacturer       = !empty($input_value) ? Search::getManufacturerById ($input_value) : null;
                $select_values      = !empty($manufacturer['id']) ? [$manufacturer['id']=>$manufacturer['name']] : [];
                $input_value        = !empty($manufacturer['id']) ? $manufacturer['id'] : $input_value;

                //$label_txt = ucfirst(trans('filters.manufacturer'));
                $label_txt = $label_txt_manufacturers;
                //$placeholder = trans('navigation.form_enter_placeholder');
                $placeholder = $label_txt_manufacturers;
                $attributes = [
                        //'data-ajax--url'=>"/ajax-manufacturer",
                        'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-manufacturer'),
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
                    {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-6 control-label strong text-primary']) !!}
                    <div class="col-xs-12 col-sm-6">
                        <div class="input-group">
                            {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if(isset($models))
            <div class="col-sm-6">
                <?php
                $select_values = $models;
                $input_name = 'models_id';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                if(empty($manufacturers_id)) {
                    $attributes['disabled'] = 'disabled';
                }
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
                            {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif
        @if((isset($manufacturersengines) && !empty($manufacturersengines)) || (isset($modelsengines) && !empty($modelsengines)))
        <div class="row well">
            @if(isset($manufacturersengines))
            <div class="col-sm-6">
                <?php
                $select_values = $manufacturersengines;
                $input_name = 'manufacturersengines_id';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                        <div class="input-group">
                            {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if(isset($modelsengines))
            <div class="col-sm-6">
                <?php
                $select_values = $modelsengines;
                $input_name = 'modelsengines_id';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                        <div class="input-group">
                            {!! Form::select($input_name, $select_values, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <div class="row well">
            <div class="col-sm-4">
                <?php
                $input_name = 'ad_width_meter';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                $input_value = cleanString($input_value);
                $label_txt = ucfirst(trans('filters.width')) . ' Meter';
                $placeholder = trans('navigation.form_enter_placeholder');
                $min = 0;
                $max = 15;
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'min' => $min, 'max' => $max, 'step' => '0.1',
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
                            {!! Form::number($input_name, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <?php
                $input_name = 'ad_length_meter';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                $input_value = cleanString($input_value);
                $label_txt = ucfirst(trans('filters.length')) . ' Meter';
                $placeholder = trans('navigation.form_enter_placeholder');
                $min = 0;
                $max = 30;
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'min' => $min, 'max' => $max, 'step' => '0.1',
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
                            {!! Form::number($input_name, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <?php
                $input_name = 'ad_draft_meter';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                $input_value = cleanString($input_value);
                $label_txt = ucfirst(trans('filters.draft')) . ' Meter';
                $placeholder = trans('navigation.form_enter_placeholder');
                $min = 0;
                $max = 10;
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'min' => $min, 'max' => $max, 'step' => '0.1',
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
                            {!! Form::number($input_name, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row well">
            <div class="col-sm-6 col-md-4">
                <?php
                $input_name = 'ad_propulsion';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                    {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                    <div class="col-xs-12 col-sm-7">
                        <div class="input-group">
                            {!! Form::number($input_name, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <?php
                $input_name = 'ad_nb_engines';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                    {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-6 control-label strong text-primary']) !!}
                    <div class="col-xs-12 col-sm-6">
                        <div class="input-group">
                            {!! Form::selectRange($input_name, 1, 4, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            {{--@if(isset($years_built))--}}
            <div class="col-sm-6 col-md-4">
            <?php
                //                            $years = [];
                //                            foreach ($years_built as $key => $value) {
                //                                $years[$key] = $key;
                //                            }
                //                            $select_values = $years;
                $input_name = 'ad_year_built';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                    {!! Form::label($input_name, $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label strong text-primary']) !!}
                    <div class="col-xs-12 col-sm-7">
                        <div class="input-group">
                            {{--{!! Form::select($input_name, $select_values, $input_value, $attributes) !!}--}}
                            {!! Form::selectRange($input_name, \Carbon\Carbon::now()->format('Y'), 1800, $input_value, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            {{--@endif--}}
        </div>
        <div class="row step-navigation">
            <div class="col-xs-6">&nbsp;</div>
            <div class="col-xs-6 text-right"><a class="btn-next btn btn-info btn-lg" href="#description" aria-controls="description" role="tab" data-toggle="tab" title="{!! ucfirst(trans('validation.attributes.description')) !!}"><span class="hidden-xs">{!! trans('navigation.next') !!}</span>&raquo;</a></div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="description">
        <div class="panel-group" id="accordion_description" role="tablist" aria-multiselectable="true">
            <?php
                $input_name = 'ad_description';
                $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                $label_txt = ucfirst(trans('validation.attributes.description'));
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'contenteditable' => "true",
                        'required' => 'required',
                        'size' => '30x6',
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control required',
                        'id' => $input_name
                ];
                $css_state = '';
                $collapse_in = 'in';
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
                    <div class="panel-body">
                        <div class="form-group {!! $css_state !!}">
                            <div class="input-group">
                                {!! Form::textarea($input_name, $input_value, $attributes) !!}
                                <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $inputsCaracts = [];
            $input_name = 'ad_description_caracts_labels';
            //$input_value = !empty($$input_name) ? $$input_name : old($input_name);
            $input_value = !empty($datasRequest[$input_name]) ? $datasRequest[$input_name] : '';
            $label_txt = ucfirst(trans('validation.attributes.description')) . ' ' . trans('validation.attributes.caracts_labels');
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => $input_name
            ];
            //$css_state = '';
            $ad_description_caracts_labels = '';
            if (!empty($input_value)) {
                //$css_state = 'has-success';
                $ad_description_caracts_labels = explode(';', $input_value);
            }
            /*if ($errors->has($input_name) || empty($input_value)) {
                $css_state = 'has-error';
                $attributes['required'] = 'required';
            }*/

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
            //$input_value = !empty($$input_name) ? $$input_name : old($input_name);
            $input_value = !empty($datasRequest[$input_name]) ? $datasRequest[$input_name] : '';
            $label_txt = ucfirst(trans('validation.attributes.description')) . ' ' . trans('validation.attributes.caracts_values');
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => $input_name
            ];
            //$css_state = '';
            $ad_description_caracts_values = '';

            if (!empty($input_value)) {
                //$css_state = 'has-success';
                $ad_description_caracts_values = explode(';', $input_value);
            }
            /*if ($errors->has($input_name) || empty($input_value)) {
                $css_state = 'has-error';
                $attributes['required'] = 'required';
            }*/
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
                        @include('theme.partials.elements.sell.caracts-labels-values', compact($caracts_type, $label_caracts_labels, $label_caracts_values, $inputsCaracts))
                    </div>
                </div>
            </div>
        </div>
        <div class="row step-navigation">
            <div class="col-xs-6 col-sm-4"><a class="btn-prev btn btn-primary btn-md" href="#ad_s_details" aria-controls="ad_s_details" role="tab" data-toggle="tab" title="{!! trans('navigation.back_to') . ' ' . trans('ads_caracts.ad_s_details') !!}">&laquo;<span class="hidden-xs">{!! trans('navigation.previous') !!}</span></a></div>
            <div class="col-xs-6 col-sm-4 col-sm-offset-4 text-right"><a class="btn-next btn btn-info btn-lg" href="#specifications" aria-controls="specifications" role="tab" data-toggle="tab" title="{!! ucfirst(trans('validation.attributes.specifications')) !!}"><span class="hidden-xs">{!! trans('navigation.next') !!}</span>&raquo;</a></div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="specifications">
        <div class="panel-group" id="accordion_specifications" role="tablist" aria-multiselectable="true">
            <?php
            $input_name = 'ad_specifications';
            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
            $collapse_in = 'in';
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
                            {!! Form::textarea($input_name, $input_value, $attributes) !!}
                            <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $inputsCaracts = [];
            $input_name = 'ad_specifications_caracts_labels';
            //$input_value = !empty($$input_name) ? $$input_name : old($input_name);
            $input_value = !empty($datasRequest[$input_name]) ? $datasRequest[$input_name] : '';
            $label_txt = ucfirst(trans('validation.attributes.specifications')) . ' ' . trans('validation.attributes.caracts_labels');
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => $input_name
            ];
            //$css_state = '';
            $ad_specifications_caracts_labels = '';
            if (!empty($input_value)) {
                //$css_state = 'has-success';
                $ad_specifications_caracts_labels = explode(';', $input_value);
            }
            /*if ($errors->has($input_name) || empty($input_value)) {
                $css_state = 'has-error';
                $attributes['required'] = 'required';
            }*/

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
            //$input_value = !empty($$input_name) ? $$input_name : old($input_name);
            $input_value = !empty($datasRequest[$input_name]) ? $datasRequest[$input_name] : '';
            $label_txt = ucfirst(trans('validation.attributes.specifications')) . ' ' . trans('validation.attributes.caracts_values');
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => $input_name
            ];
            //$css_state = '';
            $ad_specifications_caracts_values = '';
            if (!empty($input_value)) {
                //$css_state = 'has-success';
                $ad_specifications_caracts_values = explode(';', $input_value);
            }
            /*if ($errors->has($input_name) || empty($input_value)) {
                $css_state = 'has-error';
                $attributes['required'] = 'required';
            }*/
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
                        @include('theme.partials.elements.sell.caracts-labels-values', compact($caracts_type, $label_caracts_labels, $label_caracts_values, $inputsCaracts))
                    </div>
                </div>
            </div>
        </div>
        <div class="row step-navigation">
            <div class="col-xs-6"><a class="btn-prev btn btn-primary btn-md" href="#description" aria-controls="description" role="tab" data-toggle="tab" title="{!! trans('navigation.back_to') . ' ' . trans('validation.attributes.description') !!}">&laquo;<span class="hidden-xs">{!! trans('navigation.previous') !!}</span></a></div>
            <div class="col-xs-6 text-right"><a class="btn-next btn btn-info btn-lg" href="#features" aria-controls="features" role="tab" data-toggle="tab" title="{!! ucfirst(trans('validation.attributes.features')) !!}"><span class="hidden-xs">{!! trans('navigation.next') !!}</span>&raquo;</a></div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="features">
        <div class="panel-group" id="accordion_features" role="tablist" aria-multiselectable="true">
            <?php
            $input_name = 'ad_features';
            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
            $collapse_in = 'in';
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
                            {!! Form::textarea($input_name, $input_value, $attributes) !!}
                            <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $inputsCaracts = [];
            //$input_name = 'ad_features_caracts_labels';
            $input_name = 'ad_features_caracts_categories';
            //$input_value = !empty($$input_name) ? $$input_name : old($input_name);
            $input_value = !empty($datasRequest[$input_name]) ? $datasRequest[$input_name] : '';
            $label_txt = ucfirst(trans('validation.attributes.features')) . ' ' . trans('validation.attributes.caracts_labels');
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => $input_name
            ];
            //$css_state = '';
            $ad_features_caracts_labels = '';
            if (!empty($input_value)) {
                //$css_state = 'has-success';
                $ad_features_caracts_labels = explode(';', $input_value);
            }
            /*if ($errors->has($input_name) || empty($input_value)) {
                $css_state = 'has-error';
                $attributes['required'] = 'required';
            }*/

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
            //$input_value = !empty($$input_name) ? $$input_name : old($input_name);
            $input_value = !empty($datasRequest[$input_name]) ? $datasRequest[$input_name] : '';
            $label_txt = ucfirst(trans('validation.attributes.features')) . ' ' . trans('validation.attributes.caracts_values');
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => $input_name
            ];
            //$css_state = '';
            $ad_features_caracts_values = '';
            if (!empty($input_value)) {
                //$css_state = 'has-success';
                $ad_features_caracts_values = explode(';', $input_value);
            }
            /*if ($errors->has($input_name) || empty($input_value)) {
                $css_state = 'has-error';
                $attributes['required'] = 'required';
            }*/
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
                        @include('theme.partials.elements.sell.caracts-labels-values', compact($caracts_type, $label_caracts_labels, $label_caracts_values, $inputsCaracts))
                    </div>
                </div>
            </div>
        </div>
        <div class="row step-navigation">
            <div class="col-xs-6"><a class="btn-prev btn btn-primary btn-md" href="#specifications" aria-controls="specifications" role="tab" data-toggle="tab" title="{!! trans('navigation.back_to') . ' ' . trans('validation.attributes.specifications') !!}">&laquo;<span class="hidden-xs">{!! trans('navigation.previous') !!}</span></a></div>
            <div class="col-xs-6 text-right"><a class="btn-next btn btn-info btn-lg" href="#address" aria-controls="address" role="tab" data-toggle="tab" title="{!! ucfirst(trans('validation.attributes.address')) !!}"><span class="hidden-xs">{!! trans('navigation.next') !!}</span>&raquo;</a></div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane {!! $photos_missing ? 'active ' : '' !!} {!! isset($form_action) && 'edit' === $form_action ? '' : 'hidden' !!}" id="photos">
        <div class="row {!! $photos_missing && !empty($message) ? '' : 'hidden' !!}" data-msg_not_uploaded="Photos not uploaded!" id="photos_missing">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <p class=" text-danger"><i class="fa fa-exclamation-circle fa-2x fa-fw text-danger" aria-hidden="true"></i>{!! $message !!}</p>
                </div>
            </div>
        </div>
        @if(isset($form_action) && 'edit' === $form_action)
        <div class="row well well-white">
            <?php
            $ad_id = !empty($adscaracts->id) ? $adscaracts->id : time();
            $ad_title = !empty($adscaracts->ad_title) ? $adscaracts->ad_title : time();
            $input_name = 'ad_photos';
            //$input_value = !empty($adscaracts->ad_photos) ? $adscaracts->ad_photos : (!empty(old($input_name)) ? old($input_name) : '');
            $input_value = !empty(old($input_name)) ? old($input_name) : (!empty($adscaracts->ad_photos) ? $adscaracts->ad_photos : '');
            //$input_value = !empty($adscaracts->ad_photos) ? $adscaracts->ad_photos : old($input_name);
            ?>
            @include('theme.partials.elements.sell.additional-images', ['ad_country_code'=>$adscaracts->ad_country_code, 'ad_id'=>$ad_id, 'ad_referrer'=>$ad_referrer, 'ad_title'=>$ad_title, 'input_name'=>$input_name, 'input_value'=>$input_value ])
        </div>
        @endif
    </div>

    <div role="tabpanel" class="tab-pane" id="address">
        <div class="well clearfix">
            <div class="row">
                <div class="col-sm-6">
                    @if (isset($countries))
                        <?php
                        $input_name = 'countries_id';
                        $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                                <div class="input-group">
                                    {!! Form::select($input_name, $countries, $input_value, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                    <?php
                    $input_name = 'city';
                    $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                            <div class="input-group">
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    <?php
                    $input_name = 'zip';
                    $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                            <div class="input-group">
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>

                    <?php
                    $input_name = 'ad_phones';
                    $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                    $_input_value = explode(';', $input_value);
                    if(is_array($_input_value)) {
                        $input_value = $_input_value[0];
                    }
                    $input_value = cleanString($input_value);
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
                            <div class="input-group">
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php
                    $input_name = 'province';
                    $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                            <div class="input-group">
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    <?php
                    $input_name = 'region';
                    $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                            <div class="input-group">
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    <?php
                    $input_name = 'subregion';
                    $input_value = !empty($$input_name) ? $$input_name : old($input_name);
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
                            <div class="input-group">
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    @if (isset($countries))
                        <?php
                        $input_name = 'ad_mooring_country';
                        $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                        $label_txt = ucfirst(trans('ads_caracts.mooring'));
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
                                <div class="input-group">
                                    {!! Form::select($input_name, $countries, $input_value, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row step-navigation">
            <div class="col-xs-6"><a class="btn-prev btn btn-primary btn-md" href="#features" aria-controls="features" role="tab" data-toggle="tab" title="{!! trans('navigation.back_to') . ' ' . trans('validation.attributes.features') !!}">&laquo;<span class="hidden-xs">{!! trans('navigation.previous') !!}</span></a></div>
            @if (!isset($form_action))
            <div class="col-xs-6 text-right"><a class="btn-next btn-step btn btn-success btn-lg" data-current="#step_01" data-target="#step_02" title="{!! trans('navigation.step') !!} 2">{!! trans('navigation.step') !!} 2 &raquo;</a></div>
            @endif
        </div>
    </div>
</div>