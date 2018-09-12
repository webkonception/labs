<?php
    $boat_locations = config('youboat.'. $country_code .'.locations');
    $boat_locations_regions = $boat_locations['regions'];
    $boat_locations_counties = $boat_locations['counties'];

    $regions = [];
    foreach($boat_locations_regions as $key => $val_region) {
        $regions[$key] = $val_region['name'];
    }

    $counties = [];
    foreach($boat_locations_counties as $key => $val_county) {
        $counties[$key] = $val_county;
    }

    $sell_type          = !empty($bodcaracts->sell_type) ? $bodcaracts->sell_type : null;
    $adstype            = !empty($bodcaracts->adstypes_id) ? Search::getAdsTypeById($bodcaracts->adstypes_id) : null;
    $category           = !empty($bodcaracts->categories_ids) ? Search::getCategoryById($bodcaracts->categories_ids) : null;
    $subcategory        = !empty($bodcaracts->subcategories_ids) ? Search::getSubcategoryById($bodcaracts->subcategories_ids) : null;

    $manufacturer       = !empty($bodcaracts->manufacturers_id) ? Search::getManufacturerById($bodcaracts->manufacturers_id) : null;
    $model              = !empty($bodcaracts->models_id) ? Search::getModelById($bodcaracts->models_id) : null;

    $country            = !empty($bodcaracts->countries_id) ? Search::getCountry($bodcaracts->countries_id) : null;

    $min_year_built     = !empty($bodcaracts->min_year_built) ? $bodcaracts->min_year_built : '';
    $max_year_built     = !empty($bodcaracts->max_year_built) ? $bodcaracts->max_year_built : '';
    $boat_min_length    = !empty($bodcaracts->min_length) ? $bodcaracts->min_length : '';
    $boat_max_length    = !empty($bodcaracts->max_length) ? $bodcaracts->max_length : '';
    $boat_min_width     = !empty($bodcaracts->min_width) ? $bodcaracts->min_width : '';
    $boat_max_width     = !empty($bodcaracts->max_width) ? $bodcaracts->max_width : '';
    $region             = !empty($bodcaracts->regions_id) ? $bodcaracts->regions_id : '';
    $county             = !empty($bodcaracts->counties_id) ? $bodcaracts->counties_id : '';

    $additional_informations = false;
    $budget             = !empty($bodcaracts->budget) ? $bodcaracts->budget : '';
    $description        = !empty($bodcaracts->description) ? $bodcaracts->description : '';
    $with_marina_berth  = !empty($bodcaracts->with_marina_berth) ? $bodcaracts->with_marina_berth : '';
    $agree_similar      = !empty($bodcaracts->agree_similar) ? $bodcaracts->agree_similar : 0;

    if(!empty($budget) || !empty($description) || !empty($with_marina_berth) ||!empty($agree_similar)) {
        $additional_informations = true;
    }

    $label_txt_manufacturers = trans('filters.manufacturers') . ' / ' . trans('filters.shipyards');
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
    $attributes = ['class' => 'form-control', 'readonly' => 'readonly'];
?>
<section class="well well-sm alert-success">
    <h3 class="strong">{!! trans('boat_on_demand.title_whished_boat_description') !!}</h3>
    <div class="well well-sm well-white">
        <h4 class="strong">{!! trans('boat_on_demand.features_title') !!}</h4>
        <section>
            <div class="row">
                @if(!empty($adstype))
                <div class="col-sm-6">
                    <?php
                    $label_txt = trans('filters.adstypes');
                    ?>
                    <div class="form-group">
                        {!! Form::label('adstype_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('adstype_name', $adstype['name'], $attributes) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6"></div>
                @endif

                @if(!empty($category))
                <div class="col-sm-6">
                    <?php
                    $label_txt = trans('filters.categories');
                    ?>
                    <div class="form-group">
                        {!! Form::label('category_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('category_name', $category['name'], $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif

                @if(!empty($subcategory))
                <div class="col-sm-6">
                    <?php
                    $label_txt = trans('filters.subcategories');
                    ?>
                    <div class="form-group">
                        {!! Form::label('subcategory_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('subcategory_name', $subcategory['name'], $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="row">
                @if(!empty($manufacturer))
                <div class="col-sm-6">
                    <?php
                    $label_txt = $label_txt_manufacturers;
                    ?>
                    <div class="form-group">
                        {!! Form::label('manufacturer_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('manufacturer_name', $manufacturer['name'], $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif

                @if(!empty($model))
                <div class="col-sm-6">
                    <?php
                    $label_txt = trans('filters.models');
                    ?>
                    <div class="form-group">
                        {!! Form::label('model_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('model_name', $model['name'], $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-sm-6">
                    @if(!empty($min_year_built))
                    <?php
                    $label_txt = 'Min. ' . trans('filters.year_built');
                    ?>
                    <div class="form-group">
                        {!! Form::label('min_year_built', $label_txt, ['class'=>'col-xs-7 col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-xs-5 col-sm-6 col-md-8">
                            {!! Form::text('min_year_built', $min_year_built, $attributes) !!}
                        </div>
                    </div>
                    @endif
                    @if(!empty($max_year_built))
                    <?php
                    $label_txt = 'Max. ' . trans('filters.year_built');
                    ?>
                    <div class="form-group">
                        {!! Form::label('max_year_built', $label_txt, ['class'=>'col-xs-7 col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-xs-5 col-sm-6 col-md-8">
                            {!! Form::text('max_year_built', $max_year_built, $attributes) !!}
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-sm-6">
                    @if(!empty($boat_min_length))
                    <?php
                    $label_txt = trans('filters.min_length') . ' (m)';
                    ?>
                    <div class="form-group">
                        {!! Form::label('min_length', $label_txt, ['class'=>'col-xs-7 col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-xs-5 col-sm-6 col-md-8">
                            {!! Form::text('min_length',$boat_min_length, $attributes) !!}
                        </div>
                    </div>
                    @endif
                    @if(!empty($boat_max_length))
                    <?php
                    $label_txt = trans('filters.max_length') . ' (m)';
                    ?>
                    <div class="form-group">
                        {!! Form::label('max_length', $label_txt, ['class'=>'col-xs-7 col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-xs-5 col-sm-6 col-md-8">
                            {!! Form::text('max_length', $boat_max_length, $attributes) !!}
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-sm-6">
                    @if(!empty($boat_min_width))
                    <?php
                    $label_txt = trans('filters.min_width') . ' (m)';
                    ?>
                    <div class="form-group">
                        {!! Form::label('min_width', $label_txt, ['class'=>'col-xs-7 col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-xs-5 col-sm-6 col-md-8">
                            {!! Form::text('min_width', $boat_min_width, $attributes) !!}
                        </div>
                    </div>
                    @endif
                    @if(!empty($boat_max_width))
                    <?php
                    $label_txt = trans('filters.max_width') . ' (m)';
                    ?>
                    <div class="form-group">
                        {!! Form::label('max_width', $label_txt, ['class'=>'col-xs-7 col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-xs-5 col-sm-6 col-md-8">
                            {!! Form::text('max_width', $boat_max_width, $attributes) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        <hr>

        <h4 class="strong">{!! trans('boat_on_demand.place_of_navigation') !!}</h4>
        <section>
            @if(!empty($country))
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $label_txt = ucfirst(trans('validation.attributes.country'));
                    ?>
                    <div class="form-group">
                        {!! Form::label('country_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('country_name', $country['name'], $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                @if(!empty($region))
                <div class="col-sm-6">
                    <?php
                    $label_txt = ucfirst(trans('validation.attributes.region'));
                    ?>
                    <div class="form-group">
                        {!! Form::label('regions_id', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('regions_id', $regions[$region], $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif
                @if(!empty($county))
                <div class="col-sm-6">
                    <?php
                    $label_txt = ucfirst(trans('validation.attributes.county'));
                    ?>
                    <div class="form-group">
                        {!! Form::label('counties_id', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('counties_id', $counties[$county], $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </section>

        @if($additional_informations)
        <hr>

        <h4 class="strong">{!! ucfirst(trans('boat_on_demand.additional_informations')) !!}</h4>
        <section>
            <div class="row">
                @if(!empty($budget))
                <div class="col-sm-6">
                    <?php
                    $label_txt = ucfirst(trans('boat_on_demand.budget'));
                    ?>
                    <div class="form-group">
                        {!! Form::label('budget', $label_txt, ['class'=>'col-xs-7 col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-xs-5 col-sm-6 col-md-8">
{{--                            {!! Form::text('budget', trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $budget))), $attributes) !!}--}}
                            {!! Form::text('budget', formatPrice($budget), $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif
                @if(!empty($sell_type))
                <div class="col-sm-6">
                    <?php
                    $label_txt = trans('selltype.sell_type');
                    ?>
                    <div class="form-group">
                        {!! Form::label('sell_type', $label_txt, ['class'=>'col-xs-7 col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-xs-5 col-sm-6 col-md-8">
                            {!! Form::text('sell_type', $sell_type, $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="row">
                @if(!empty($description))
                <div class="col-sm-12">
                    <?php
                    $label_txt = ucfirst(trans('validation.attributes.comment'));
                    ?>
                    <div class="form-group">
                        {!! Form::label('description', $label_txt, ['class'=>'col-sm-12 control-label']) !!}
                        <div class="col-sm-12">
                            {!! Form::textarea('description', $description, $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-sm-6">
                    <?php
                    $label_txt = ucfirst(trans('boat_on_demand.with_marina_berth'));
                    ?>
                    <div class="form-group clearfix">
                        {!! Form::label('switch_with_marina_berth', $label_txt, ['class'=>'col-xs-9 control-label']) !!}
                        <div class="col-xs-3 material-switch">
                            {!! Form::checkbox('switch_with_marina_berth', 'active', ($with_marina_berth == 1) ? 'checked' : '', ['class'=>'switch', 'disabled' => 'disabled', 'data-target'=>'with_marina_berth', 'data-default'=>0]) !!}
                            <label for="switch_with_marina_berth" class="label-success"></label>
                            <span></span>
                            {!! Form::hidden('with_marina_berth', $with_marina_berth, ['class'=>'form-control', 'id'=>'with_marina_berth']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php
                    $label_txt = ucfirst(trans('boat_on_demand.agree_similar'));
                    ?>
                    <div class="form-group clearfix">
                        {!! Form::label('switch_agree_similar', $label_txt, ['class'=>'col-xs-9 control-label']) !!}
                        <div class="col-xs-3 material-switch">
                            {!! Form::checkbox('switch_agree_similar', 'active', ($agree_similar == 1) ? 'checked' : '', ['class'=>'switch', 'disabled' => 'disabled', 'data-target'=>'agree_similar', 'data-default'=>0]) !!}
                            <label for="switch_agree_similar" class="label-success"></label>
                            <span></span>
                            {!! Form::hidden('agree_similar', $agree_similar, ['class'=>'form-control', 'id'=>'agree_similar']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>
</section>
