<?php
    if (!empty($datasRequest) && count($datasRequest)>0) {

        $sell_type              = isset($sell_type) ? $sell_type : (!empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : null);
        $adstype                = isset($adstype) ? $adstype : (!empty($datasRequest['adstypes_id']) ? Search::getAdsTypeById ($datasRequest['adstypes_id']) : null);
        $category               = isset($category) ? $category : (!empty($datasRequest['categories_ids']) ? Search::getCategoryById ($datasRequest['categories_ids']) : null);
        $subcategory            = isset($subcategory) ? $subcategory : (!empty($datasRequest['subcategories_ids']) ? Search::getSubcategoryById ($datasRequest['subcategories_ids']) : null);

        $manufacturer           = isset($manufacturer) ? $manufacturer : (!empty($datasRequest['manufacturers_id']) ? Search::getManufacturerById ($datasRequest['manufacturers_id']) : null);

        $model                  = isset($model) ? $model : (!empty($datasRequest['models_id']) ? Search::getModelById ($datasRequest['models_id']) : null);

        $boat_min_length        = isset($boat_min_length) ? $boat_min_length : (!empty($datasRequest['min_length']) ? $datasRequest['min_length'] : null);
        $boat_max_length        = isset($boat_max_length) ? $boat_max_length : (!empty($datasRequest['max_length']) ? $datasRequest['max_length'] : null);

        $boat_min_width         = isset($boat_min_width) ? $boat_min_width : (!empty($datasRequest['min_width']) ? $datasRequest['min_width'] : null);
        $boat_max_width         = isset($boat_max_width) ? $boat_max_width : (!empty($datasRequest['max_width']) ? $datasRequest['max_width'] : null);

        $min_year_built         = isset($min_year_built) ? $min_year_built : (!empty($datasRequest['min_year_built']) ? $datasRequest['min_year_built'] : null);
        $max_year_built         = isset($max_year_built) ? $max_year_built : (!empty($datasRequest['max_year_built']) ? $datasRequest['max_year_built'] : null);

        $min_ad_price           = isset($min_ad_price) ? $min_ad_price : (!empty($datasRequest['min_ad_price']) ? $datasRequest['min_ad_price'] : null);
        $max_ad_price           = isset($max_ad_price) ? $max_ad_price : (!empty($datasRequest['max_ad_price']) ? $datasRequest['max_ad_price'] : null);

        $budget                 = isset($budget) ? $budget : (!empty($datasRequest['budget']) ? $datasRequest['budget'] : null);
        $country                = isset($country) ? $country : (!empty($datasRequest['countries_id']) ? Search::getCountry ($datasRequest['countries_id']) : null);
        $region                 = isset($region) ? $region : (!empty($datasRequest['regions_id']) ? $datasRequest['regions_id'] : null);
        $county                 = isset($county) ? $county : (!empty($datasRequest['counties_id']) ? $datasRequest['counties_id'] : null);

        $description            = isset($description) ? $description : (!empty($datasRequest['description']) ? $datasRequest['description'] : null);
        $with_marina_berth      = isset($with_marina_berth) ? $with_marina_berth : (!empty($datasRequest['with_marina_berth']) ? $datasRequest['with_marina_berth'] : '');
        $agree_similar          = isset($agree_similar) ? $agree_similar : (!empty($datasRequest['agree_similar']) ? $datasRequest['agree_similar'] : '');
    } else {
        $country = Search::getCountry(config('youboat.'. $country_code .'.country_code'));
    }

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
    $curYear = date("Y");
?>
<section class="well well-sm well-success alert-success">
    <h3 class="strong">{!! trans('boat_on_demand.title_description') !!}</h3>
    <div class="well well-sm well-white">
        <h4 class="strong">{!! trans('boat_on_demand.features_title') !!}</h4>
        <section>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $adstypes_id = old('adstypes_id', !empty($bodcaracts->adstypes_id)?$bodcaracts->adstypes_id:'');
                    $label_txt = trans('filters.adstypes');
                    $attributes = [
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control nosort',
                            'id' => 'adstypes_id'
                    ];

                    $css_state = '';
                    if (!empty($adstypes_id)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('adstypes_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                        <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                            {!! Form::select('adstypes_id', $adstypes, $adstypes_id,  $attributes) !!}
                        </div>
                        @if ($isAdmin)
                            <div class="col-xs-4 col-sm-3">
                                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.adstypes.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $categories_ids = old('categories_ids', !empty($bodcaracts->categories_ids)?$bodcaracts->categories_ids:'');
                    $label_txt = trans('filters.categories');
                    $attributes = [
                            'data-ajax--url'=>"categories",
                            'data-parent' => !empty($adstypes_id) ? $adstypes_id : '',
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control',
                            'id' => 'categories_ids'
                    ];

                    $css_state = '';
                    if (!empty($categories_ids)) {
                        $css_state .= 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('categories_ids', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                        <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                            {!! Form::select('categories_ids', $categories, $categories_ids, $attributes) !!}
                        </div>
                        @if ($isAdmin)
                            <div class="col-xs-4 col-sm-3">
                                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.categories.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php
                    $subcategories_ids = old('subcategories_ids', !empty($bodcaracts->subcategories_ids)?$bodcaracts->subcategories_ids:'');
                    $label_txt = trans('filters.subcategories');
                    $attributes = [
                            'data-ajax--url'=>"subcategories",
                            'data-parent' => !empty($categories_ids) ? $categories_ids : '',
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control',
                            'id' => 'subcategories_ids'
                    ];

                    $css_state = '';
                    if (!empty($subcategories_ids)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('subcategories_ids', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                        <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                            {!! Form::select('subcategories_ids', $subcategories, $subcategories_ids, $attributes) !!}
                        </div>
                        @if ($isAdmin)
                            <div class="col-xs-4 col-sm-3">
                                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.subcategories.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $manufacturers_id = old('manufacturers_id', !empty($bodcaracts->manufacturers_id)?$bodcaracts->manufacturers_id:'');
                    $label_txt = $label_txt_manufacturers;
                    $attributes = [
                            'data-ajax--url'=>"/ajax-manufacturer",
                            'data-placeholder' => $label_txt,
                            'placeholder' => $label_txt,
                            'class' => 'form-control select2',
                            'id' => 'manufacturers_id'
                    ];

                    $css_state = '';
                    if (!empty($manufacturers_id)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('manufacturers_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                        <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                            {!! Form::select('manufacturers_id', !empty($manufacturer['id']) ? [$manufacturer['id']=>$manufacturer['name']] : [], !empty($manufacturer['id']) ? $manufacturer['id'] : $manufacturers_id,  $attributes) !!}
                        </div>
                        @if ($isAdmin)
                            <div class="col-xs-4 col-sm-3">
                                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.manufacturers.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php
                    if(!empty($models)) {
                        $array = json_decode(json_encode($models), true);
                        asort($array);
                        $models = $array;
                    }
                    $models_id = old('models_id', !empty($bodcaracts->models_id)?$bodcaracts->models_id:'');
                    $label_txt = trans('filters.models');
                    $attributes = [
                            'ajax-url'=>"/ajax-models",
                            'data-parent' => !empty($manufacturers_id) ? $manufacturers_id : '',
                            'data-placeholder' => $label_txt,
                            'placeholder' => $label_txt,
                            'class' => 'form-control',
                            'id' => 'models_id'
                    ];

                    $css_state = '';
                    if (!empty($models_id)) {
                        $css_state = 'has-success';
                        //$attributes['data-selected'] = $models_id;
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('models_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                        <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                            {!! Form::select('models_id', $models, $models_id, $attributes) !!}
                        </div>
                        @if ($isAdmin)
                            <div class="col-xs-4 col-sm-3">
                                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.models.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $min_year_built = old('min_year_built', !empty($bodcaracts->min_year_built)?$bodcaracts->min_year_built:'');
                    $label_txt = 'Min. ' . trans('filters.year_built');
                    $attributes = [
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control',
                            'id' => 'min_year_built'
                    ];

                    $css_state = '';

                    if (!empty($min_year_built)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('min_year_built', $label_txt, ['class'=>'col-xs-8 col-sm-5 control-label']) !!}
                        <div class="col-xs-4 col-sm-7">
                            {!! Form::selectRange('min_year_built', 1970, $curYear, $min_year_built,  $attributes) !!}
                        </div>
                    </div>
                    <?php
                    $max_year_built = old('max_year_built', !empty($bodcaracts->max_year_built)?$bodcaracts->max_year_built:'');
                    $label_txt = 'Max. ' . trans('filters.year_built');
                    $attributes = [
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control',
                            'id' => 'max_year_built'
                    ];

                    $css_state = '';

                    if (!empty($max_year_built)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('max_year_built', $label_txt, ['class'=>'col-xs-8 col-sm-5 control-label']) !!}
                        <div class="col-xs-4 col-sm-7">
                            {!! Form::selectRange('max_year_built', $curYear, 1970, $max_year_built,  $attributes) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php
                    $boat_min_length = old('min_length', !empty($bodcaracts->min_length)?$bodcaracts->min_length:'');
                    $label_txt = trans('filters.min_length') . ' (m)';
                    $attributes = [
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control nosort ',
                            'id' => 'min_length'
                    ];

                    $css_state = '';
                    if (!empty($boat_min_length)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('min_length', $label_txt, ['class'=>'col-xs-8 col-sm-5 control-label']) !!}
                        <div class="col-xs-4 col-sm-7">
                            {!! Form::selectRange('min_length', 3, 16, $boat_min_length, $attributes) !!}
                        </div>
                    </div>
                    <?php
                    $boat_max_length = old('max_length', !empty($bodcaracts->max_length)?$bodcaracts->max_length:'');
                    $label_txt = trans('filters.max_length') . ' (m)';
                    $attributes = [
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control nosort ',
                            'id' => 'max_length'
                    ];

                    $css_state = '';
                    if (!empty($boat_max_length)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('max_length', $label_txt, ['class'=>'col-xs-8 col-sm-5 control-label']) !!}
                        <div class="col-xs-4 col-sm-7">
                            {!! Form::selectRange('max_length', 16, 3, $boat_max_length, $attributes) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php
                    $boat_min_width = old('min_width', !empty($bodcaracts->min_width)?$bodcaracts->min_width:'');
                    $label_txt = trans('filters.min_width') . ' (m)';
                    $attributes = [
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control nosort ',
                            'id' => 'min_width'
                    ];

                    $css_state = '';
                    if (!empty($boat_min_width)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('min_width', $label_txt, ['class'=>'col-xs-8 col-sm-5 control-label']) !!}
                        <div class="col-xs-4 col-sm-7">
                            {!! Form::selectRange('min_width', 3, 6, $boat_min_width, $attributes) !!}
                        </div>
                    </div>
                    <?php
                    $boat_max_width = old('max_width', !empty($bodcaracts->max_width)?$bodcaracts->max_width:'');
                    $label_txt = trans('filters.max_width') . ' (m)';
                    $attributes = [
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control nosort ',
                            'id' => 'max_width'
                    ];

                    $css_state = '';
                    if (!empty($boat_max_width)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('max_width', $label_txt, ['class'=>'col-xs-8 col-sm-5 control-label']) !!}
                        <div class="col-xs-4 col-sm-7">
                            {!! Form::selectRange('max_width', 6, 3, $boat_max_width, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <hr>

        <h4 class="strong">{!! trans('boat_on_demand.place_of_navigation') !!}</h4>
        <section>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $countries_id =  old('countries_id', !empty($bodcaracts->countries_id)?$bodcaracts->countries_id:'');

                    $label_txt = ucfirst(trans('validation.attributes.country'));
                    $attributes = [
                            'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control countries',
                            'id' => 'countries_id'
                    ];
                    $css_state = '';
                    if (!count($countries) > 0) {
                        $attributes['disabled'] = 'disabled';
                        $css_state .= 'collapse ';
                    }
                    if (!empty($countries_id) || count($countries) === 1) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has('countries_id')) {
                        $css_state = 'has-error';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('countries_id', $label_txt, ['class'=>'col-sm-5 control-label']) !!}
                        <div class="col-sm-7">
                            {!! Form::select('countries_id', $countries, $countries_id, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $region = old('regions_id', !empty($bodcaracts->regions_id)?$bodcaracts->regions_id:'');
                    $label_txt = ucfirst(trans('validation.attributes.region'));
                    $attributes = [
                            'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control',
                            'id' => 'regions_id'
                    ];
                    $css_state = '';
                    if (!count($regions) > 0) {
                        $attributes['disabled'] = 'disabled';
                        $css_state .= 'collapse ';
                    }
                    if (!empty($region) || count($regions) === 1) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has('regions_id')) {
                        $css_state = 'has-error';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('regions_id', $label_txt, ['class'=>'col-sm-5 control-label']) !!}
                        <div class="col-sm-7">
                            @if (count($regions) === 1)
                                <?php
                                $array = json_decode(json_encode($regions), true);
                                $key = key($array);
                                ?>
                                {!! Form::text('region_val', $regions->first(), $attributes) !!}
                                {!! Form::hidden('regions_id', $key.'') !!}
                            @else
                                {!! Form::select('regions_id', $regions, $region, $attributes) !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php
                    $county = old('counties_id', !empty($bodcaracts->counties_id)?$bodcaracts->counties_id:'');
                    $label_txt = ucfirst(trans('validation.attributes.county'));
                    $attributes = [
                            'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control',
                            'id' => 'counties_id'
                    ];
                    $css_state = '';
                    if (!count($counties) > 0) {
                        $attributes['disabled'] = 'disabled';
                        $css_state .= 'collapse ';
                    }
                    if (!empty($county) || count($counties) === 1) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has('counties_id')) {
                        $css_state = 'has-error';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('counties_id', $label_txt, ['class'=>'col-sm-5 control-label']) !!}
                        <div class="col-sm-7">
                            @if (count($counties) === 1)
                                <?php
                                $array = json_decode(json_encode($counties), true);
                                $key = key($array);
                                ?>
                                {!! Form::text('county_val', $counties->first(), $attributes) !!}
                                {!! Form::hidden('counties_id', $key.'') !!}
                            @else
                                {!! Form::select('counties_id', $counties, $county, $attributes) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr>

        <h4 class="strong">{!! ucfirst(trans('boat_on_demand.additional_informations')) !!}</h4>
        <section>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $budget = old('budget', !empty($bodcaracts->budget)?$bodcaracts->budget:'');
                    $label_txt = ucfirst(trans('boat_on_demand.budget'));
                    $attributes = [
                            'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control',
                            'id' => 'budget'
                    ];
                    $css_state = '';
                    if (!empty($budget)) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has('budget')) {
                        $css_state = 'has-error';
                    }
                    $attributes['required'] = 'required';
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('budget', $label_txt, ['class'=>'col-xs-5 col-sm-5 control-label']) !!}
                        <div class="col-xs-7 col-sm-7">
                            <div class="input-group">
                            {!! Form::text('budget', $budget, $attributes) !!}
                                <span class="input-group-addon">{!! config('youboat.'. $country_code .'.currency') !!}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php
                    $sell_type = old('sell_type', !empty($bodcaracts->sell_type)?$bodcaracts->sell_type:'');
                    $label_txt = trans('selltype.sell_type');
                    $attributes = [
                            'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control',
                            'id' => 'sell_type'
                    ];
                    $css_state = '';
                    if (!count($selltypes) > 0) {
                        $attributes['disabled'] = 'disabled';
                        $css_state .= 'collapse ';
                    }
                    if (!empty($sell_type)) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has('sell_type')) {
                        $css_state = 'has-error';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('sell_type', $label_txt, ['class'=>'col-xs-5 col-sm-5 control-label']) !!}
                        <div class="col-xs-7 col-sm-7">
                            {!! Form::select('sell_type', $selltypes, $sell_type, $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $description = old('description', !empty($bodcaracts->description)?$bodcaracts->description:'');
                    $label_txt = ucfirst(trans('validation.attributes.comment'));
                    $attributes = [
                            'data-header' => trans('navigation.form_enter_placeholder'),
                            'placeholder' => trans('navigation.form_enter_placeholder'),
                            'class' => 'form-control',
                            'id' => 'description'
                    ];
                    $css_state = '';
                    if (!empty($description)) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has('description')) {
                        $css_state = 'has-error';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('description', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                        <div class="col-xs-12 col-sm-7">
                            {!! Form::textarea('description', $description, $attributes) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php
                    $with_marina_berth = old('with_marina_berth', !empty($bodcaracts->with_marina_berth)?$bodcaracts->with_marina_berth:'');
                    $label_txt = ucfirst(trans('boat_on_demand.with_marina_berth'));
                    $css_state = '';
                    if (!empty($with_marina_berth) && 1 === $with_marina_berth) {
                        $css_state = 'has-success';
                        $attributes = [
                                'id'=>'with_marina_berth',
                                'checked'=>'checked'
                        ];
                    } else {
                        $attributes = [
                                'id'=>'with_marina_berth'
                        ];
                    }
                    if ($errors->has('with_marina_berth')) {
                        $css_state = 'has-error';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('switch_with_marina_berth', $label_txt, ['class'=>'col-xs-9 col-sm-5 control-label']) !!}
                        <div class="col-xs-3 col-sm-7 material-switch">
                            {!! Form::checkbox('switch_with_marina_berth', 'active', ($with_marina_berth == 1) ? 'checked' : '', ['class'=>'switch', 'data-target'=>'with_marina_berth', 'data-default'=>0]) !!}
                            <label for="switch_with_marina_berth" class="label-success"></label>
                            <span></span>
                            {!! Form::hidden('with_marina_berth', $with_marina_berth, ['class'=>'form-control', 'id'=>'with_marina_berth']) !!}
                        </div>
                    </div>
                    <?php
                    $agree_similar = old('agree_similar', !empty($bodcaracts->agree_similar)?$bodcaracts->agree_similar:0);
                    $label_txt = ucfirst(trans('boat_on_demand.agree_similar'));
                    $css_state = '';
                    if (!empty($agree_similar) && 1 === $agree_similar) {
                        $css_state = 'has-success';
                        $attributes = [
                            'class'=>'form-control',
                            'id'=>'agree_similar',
                            'checked'=>'checked'
                        ];
                    } else {
                        $attributes = [
                            'class'=>'form-control',
                            'id'=>'agree_similar'
                        ];
                    }
                    if ($errors->has('with_marina_berth')) {
                        $css_state = 'has-error';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('switch_agree_similar', $label_txt, ['class'=>'col-xs-9 col-sm-5 control-label']) !!}
                        <div class="col-xs-3 col-sm-7 material-switch">
                            {!! Form::checkbox('switch_agree_similar', 'active', ($agree_similar == 1) ? 'checked' : '', ['class'=>'switch', 'data-target'=>'agree_similar', 'data-default'=>0]) !!}
                            <label for="switch_agree_similar" class="label-success"></label>
                            <span></span>
                            {!! Form::hidden('agree_similar', $agree_similar, ['class'=>'form-control', 'id'=>'agree_similar']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
